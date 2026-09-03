<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Display the backup and restore management panel.
     */
    public function index(): View
    {
        $stats = [
            'categories' => Category::count(),
            'products' => Product::count(),
            'banners' => Schema::hasTable('banners') ? Banner::count() : 0,
            'quotes' => Schema::hasTable('quotes') ? Quote::count() : 0,
            'orders' => Schema::hasTable('orders') ? Order::count() : 0,
        ];

        return view('admin.backup.index', compact('stats'));
    }

    /**
     * Export / Download database & images backup.
     */
    public function export(Request $request): BinaryFileResponse|StreamedResponse|RedirectResponse
    {
        $type = $request->query('type', 'zip');

        $data = [
            'app_name' => config('app.name', 'TecnoStore'),
            'backup_version' => '1.0',
            'created_at' => now()->toIso8601String(),
            'company_settings' => Schema::hasTable('company_settings') ? CompanySetting::all() : [],
            'categories' => Category::all(),
            'products' => Product::all(),
            'banners' => Schema::hasTable('banners') ? Banner::all() : [],
            'quotes' => Schema::hasTable('quotes') ? Quote::with('items')->get() : [],
            'orders' => Schema::hasTable('orders') ? Order::with('items')->get() : [],
        ];

        $filenameBase = 'Copia-Seguridad-'.now()->format('Y-m-d-His');

        if ($type === 'json') {
            $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            return response()->streamDownload(function () use ($jsonContent) {
                echo $jsonContent;
            }, "{$filenameBase}.json", [
                'Content-Type' => 'application/json',
            ]);
        }

        // Create ZIP archive
        if (! class_exists('ZipArchive')) {
            return redirect()->back()->with('error', 'La extensión PHP ZipArchive no está habilitada en este servidor. Puedes descargar la copia en formato JSON.');
        }

        $tempDir = storage_path('app/temp_backups');
        if (! File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $zipPath = $tempDir."/{$filenameBase}.zip";
        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return redirect()->back()->with('error', 'No se pudo crear el archivo comprimido ZIP.');
        }

        // 1. Add JSON data
        $zip->addFromString('backup_data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        // 2. Add public storage images (products, banners, etc.)
        $publicStoragePath = storage_path('app/public');
        if (File::exists($publicStoragePath)) {
            $files = File::allFiles($publicStoragePath);
            foreach ($files as $file) {
                $relativePath = 'storage/'.$file->getRelativePathname();
                $zip->addFile($file->getRealPath(), $relativePath);
            }
        }

        $zip->close();

        return response()->download($zipPath, "{$filenameBase}.zip")->deleteFileAfterSend(true);
    }

    /**
     * Restore database & images from uploaded backup file.
     */
    public function restore(Request $request): RedirectResponse
    {
        $request->validate([
            'backup_file' => ['required', 'file', 'max:102400'], // max 100MB
        ]);

        $file = $request->file('backup_file');
        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, ['zip', 'json'])) {
            return redirect()->back()->with('error', 'El archivo debe ser de formato .ZIP o .JSON');
        }

        $jsonData = null;

        if ($extension === 'json') {
            $content = file_get_contents($file->getRealPath());
            $jsonData = json_decode($content, true);
        } else {
            // Unpack ZIP
            if (! class_exists('ZipArchive')) {
                return redirect()->back()->with('error', 'La extensión PHP ZipArchive no está disponible para descomprimir este archivo.');
            }

            $zip = new ZipArchive;
            if ($zip->open($file->getRealPath()) === true) {
                $tempExtractDir = storage_path('app/temp_restore_'.uniqid());
                File::makeDirectory($tempExtractDir, 0755, true);
                $zip->extractTo($tempExtractDir);
                $zip->close();

                // Read JSON data
                $jsonPath = $tempExtractDir.'/backup_data.json';
                if (File::exists($jsonPath)) {
                    $content = File::get($jsonPath);
                    $jsonData = json_decode($content, true);
                }

                // Restore Storage files
                $extractedStorage = $tempExtractDir.'/storage';
                if (File::exists($extractedStorage)) {
                    $targetStorage = storage_path('app/public');
                    if (! File::exists($targetStorage)) {
                        File::makeDirectory($targetStorage, 0755, true);
                    }
                    File::copyDirectory($extractedStorage, $targetStorage);
                }

                // Clean up temp extract directory
                File::deleteDirectory($tempExtractDir);
            } else {
                return redirect()->back()->with('error', 'No se pudo abrir el archivo ZIP proporcionado.');
            }
        }

        if (! is_array($jsonData)) {
            return redirect()->back()->with('error', 'El archivo de respaldo está dañado o no contiene un formato JSON válido.');
        }

        // Execute Database Restoration
        $stats = [
            'categories' => 0,
            'products' => 0,
            'banners' => 0,
            'quotes' => 0,
            'orders' => 0,
        ];

        try {
            // Disable foreign key checks across all DB engines
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF;');
            } elseif ($driver === 'pgsql') {
                DB::statement("SET session_replication_role = 'replica';");
            }

            // 1. Restore Company Settings
            if (! empty($jsonData['company_settings']) && Schema::hasTable('company_settings')) {
                foreach ($jsonData['company_settings'] as $settingData) {
                    $settingId = $settingData['id'] ?? 1;
                    $row = collect($settingData)->except(['id'])->toArray();
                    DB::table('company_settings')->updateOrInsert(['id' => $settingId], $row);
                }
            }

            // 2. Restore Categories (Pass 1: Insert all without parent_id to guarantee all IDs exist)
            if (isset($jsonData['categories']) && is_array($jsonData['categories'])) {
                foreach ($jsonData['categories'] as $cat) {
                    DB::table('categories')->updateOrInsert(
                        ['id' => $cat['id']],
                        [
                            'parent_id' => null,
                            'name' => $cat['name'],
                            'slug' => $cat['slug'],
                            'description' => $cat['description'] ?? null,
                            'created_at' => $cat['created_at'] ?? now(),
                            'updated_at' => $cat['updated_at'] ?? now(),
                        ]
                    );
                    $stats['categories']++;
                }

                // Pass 2: Assign parent_id once all IDs exist
                $allCategoryIds = DB::table('categories')->pluck('id')->toArray();
                foreach ($jsonData['categories'] as $cat) {
                    if (! empty($cat['parent_id']) && in_array($cat['parent_id'], $allCategoryIds)) {
                        DB::table('categories')->where('id', $cat['id'])->update([
                            'parent_id' => $cat['parent_id'],
                        ]);
                    }
                }
            }

            // 3. Restore Products
            if (isset($jsonData['products']) && is_array($jsonData['products'])) {
                $allCategoryIds = DB::table('categories')->pluck('id')->toArray();
                $hasStock = Schema::hasColumn('products', 'stock');
                $hasFeatured = Schema::hasColumn('products', 'is_featured');
                $hasMinPrice = Schema::hasColumn('products', 'min_price');

                foreach ($jsonData['products'] as $prod) {
                    $catId = (! empty($prod['category_id']) && in_array($prod['category_id'], $allCategoryIds)) ? $prod['category_id'] : null;

                    $productRow = [
                        'category_id' => $catId,
                        'name' => $prod['name'],
                        'slug' => $prod['slug'],
                        'description' => $prod['description'] ?? null,
                        'price' => $prod['price'] ?? 0,
                        'is_active' => (bool) ($prod['is_active'] ?? true),
                        'image' => $prod['image'] ?? null,
                        'created_at' => $prod['created_at'] ?? now(),
                        'updated_at' => $prod['updated_at'] ?? now(),
                    ];

                    if ($hasMinPrice) {
                        $productRow['min_price'] = $prod['min_price'] ?? null;
                    }
                    if ($hasStock) {
                        $productRow['stock'] = $prod['stock'] ?? 0;
                    }
                    if ($hasFeatured) {
                        $productRow['is_featured'] = (bool) ($prod['is_featured'] ?? false);
                    }

                    DB::table('products')->updateOrInsert(
                        ['id' => $prod['id']],
                        $productRow
                    );
                    $stats['products']++;
                }
            }

            // 4. Restore Banners
            if (isset($jsonData['banners']) && is_array($jsonData['banners']) && Schema::hasTable('banners')) {
                foreach ($jsonData['banners'] as $b) {
                    DB::table('banners')->updateOrInsert(
                        ['id' => $b['id']],
                        [
                            'title' => $b['title'] ?? null,
                            'subtitle' => $b['subtitle'] ?? null,
                            'image_url' => $b['image_url'],
                            'button_text' => $b['button_text'] ?? null,
                            'button_url' => $b['button_url'] ?? null,
                            'order' => $b['order'] ?? 0,
                            'is_active' => (bool) ($b['is_active'] ?? true),
                            'created_at' => $b['created_at'] ?? now(),
                            'updated_at' => $b['updated_at'] ?? now(),
                        ]
                    );
                    $stats['banners']++;
                }
            }

            // 5. Restore Quotes & Quote Items
            if (isset($jsonData['quotes']) && is_array($jsonData['quotes']) && Schema::hasTable('quotes')) {
                foreach ($jsonData['quotes'] as $q) {
                    DB::table('quotes')->updateOrInsert(
                        ['id' => $q['id']],
                        [
                            'quote_number' => $q['quote_number'],
                            'customer_name' => $q['customer_name'],
                            'customer_document' => $q['customer_document'] ?? null,
                            'customer_document_type' => $q['customer_document_type'] ?? 'DNI',
                            'customer_phone' => $q['customer_phone'] ?? null,
                            'customer_email' => $q['customer_email'] ?? null,
                            'customer_address' => $q['customer_address'] ?? null,
                            'valid_until' => $q['valid_until'] ?? null,
                            'notes' => $q['notes'] ?? null,
                            'status' => $q['status'] ?? 'emitida',
                            'subtotal' => $q['subtotal'] ?? 0,
                            'tax' => $q['tax'] ?? 0,
                            'total' => $q['total'] ?? 0,
                            'created_at' => $q['created_at'] ?? now(),
                            'updated_at' => $q['updated_at'] ?? now(),
                        ]
                    );

                    if (! empty($q['items']) && Schema::hasTable('quote_items')) {
                        DB::table('quote_items')->where('quote_id', $q['id'])->delete();
                        foreach ($q['items'] as $item) {
                            DB::table('quote_items')->insert([
                                'quote_id' => $q['id'],
                                'product_id' => $item['product_id'] ?? null,
                                'product_name' => $item['product_name'],
                                'quantity' => $item['quantity'],
                                'unit_price' => $item['unit_price'],
                                'subtotal' => $item['subtotal'],
                                'created_at' => $item['created_at'] ?? now(),
                                'updated_at' => $item['updated_at'] ?? now(),
                            ]);
                        }
                    }
                    $stats['quotes']++;
                }
            }

            // 6. Restore Orders & Order Items
            if (isset($jsonData['orders']) && is_array($jsonData['orders']) && Schema::hasTable('orders')) {
                foreach ($jsonData['orders'] as $o) {
                    DB::table('orders')->updateOrInsert(
                        ['id' => $o['id']],
                        [
                            'order_number' => $o['order_number'],
                            'customer_name' => $o['customer_name'],
                            'customer_document' => $o['customer_document'] ?? null,
                            'customer_document_type' => $o['customer_document_type'] ?? 'DNI',
                            'customer_phone' => $o['customer_phone'] ?? null,
                            'delivery_mode' => $o['delivery_mode'] ?? 'Recojo en Tienda',
                            'delivery_address' => $o['delivery_address'] ?? null,
                            'payment_method' => $o['payment_method'] ?? 'Yape',
                            'notes' => $o['notes'] ?? null,
                            'status' => $o['status'] ?? 'recibido',
                            'subtotal' => $o['subtotal'] ?? 0,
                            'total' => $o['total'] ?? 0,
                            'created_at' => $o['created_at'] ?? now(),
                            'updated_at' => $o['updated_at'] ?? now(),
                        ]
                    );

                    if (! empty($o['items']) && Schema::hasTable('order_items')) {
                        DB::table('order_items')->where('order_id', $o['id'])->delete();
                        foreach ($o['items'] as $item) {
                            DB::table('order_items')->insert([
                                'order_id' => $o['id'],
                                'product_id' => $item['product_id'] ?? null,
                                'product_name' => $item['product_name'],
                                'quantity' => $item['quantity'],
                                'unit_price' => $item['unit_price'],
                                'subtotal' => $item['subtotal'],
                                'created_at' => $item['created_at'] ?? now(),
                                'updated_at' => $item['updated_at'] ?? now(),
                            ]);
                        }
                    }
                    $stats['orders']++;
                }
            }

            // Re-enable foreign key checks
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON;');
            } elseif ($driver === 'pgsql') {
                DB::statement("SET session_replication_role = 'origin';");
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al restaurar la base de datos: '.$e->getMessage());
        }

        $msg = "¡Copia de seguridad restaurada con éxito! Se restablecieron: {$stats['categories']} categorías, {$stats['products']} productos, {$stats['banners']} banners, {$stats['quotes']} cotizaciones y {$stats['orders']} pedidos.";

        return redirect()->route('admin.backup.index')->with('success', $msg);
    }

    /**
     * Factory reset / Clear catalog data (preserves Admin user & company settings).
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'confirm_text' => ['required', 'in:RESTABLECER'],
        ]);

        try {
            DB::transaction(function () {
                if (DB::getDriverName() === 'mysql') {
                    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                } elseif (DB::getDriverName() === 'sqlite') {
                    DB::statement('PRAGMA foreign_keys = OFF;');
                }

                if (Schema::hasTable('order_items')) {
                    OrderItem::truncate();
                }
                if (Schema::hasTable('orders')) {
                    Order::truncate();
                }
                if (Schema::hasTable('quote_items')) {
                    QuoteItem::truncate();
                }
                if (Schema::hasTable('quotes')) {
                    Quote::truncate();
                }
                if (Schema::hasTable('products')) {
                    Product::truncate();
                }
                if (Schema::hasTable('categories')) {
                    Category::truncate();
                }
                if (Schema::hasTable('banners')) {
                    Banner::truncate();
                }

                if (DB::getDriverName() === 'mysql') {
                    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                } elseif (DB::getDriverName() === 'sqlite') {
                    DB::statement('PRAGMA foreign_keys = ON;');
                }
            });
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Error al restablecer la base de datos: '.$e->getMessage());
        }

        return redirect()->route('admin.backup.index')->with('success', 'El catálogo ha sido restablecido a cero exitosamente. Puedes subir una copia de seguridad o crear productos nuevos.');
    }
}
