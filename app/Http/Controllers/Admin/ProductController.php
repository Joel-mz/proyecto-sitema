<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ExcelImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        $categories = Category::with('subcategories')->whereNull('parent_id')->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $parentCategories = Category::with('subcategories')->whereNull('parent_id')->orderBy('name')->get();

        return view('admin.products.create', compact('parentCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'min_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99', 'lte:price'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240'],
            'image_url' => ['nullable', 'url', 'max:2048'],
        ], [
            'min_price.lte' => 'El precio mínimo no puede ser mayor que el precio de venta.',
            'image_url.url' => 'La URL de la imagen debe ser un enlace válido (ej. https://ejemplo.com/foto.jpg).',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['slug'] = Str::slug($validated['name']);

        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = "{$originalSlug}-{$counter}";
            $counter++;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->input('image_url');
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Product $product): View
    {
        $parentCategories = Category::with('subcategories')->whereNull('parent_id')->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'parentCategories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($product->id)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'min_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99', 'lte:price'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:10240'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'remove_image' => ['sometimes', 'boolean'],
        ], [
            'min_price.lte' => 'El precio mínimo no puede ser mayor que el precio de venta.',
            'image_url.url' => 'La URL de la imagen debe ser un enlace válido (ej. https://ejemplo.com/foto.jpg).',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('image')) {
            if ($product->image && ! str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        } elseif ($request->filled('image_url')) {
            if ($product->image && ! str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->input('image_url');
        } elseif ($request->boolean('remove_image')) {
            if ($product->image && ! str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image && ! str_starts_with($product->image, 'http') && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:products,id'],
        ]);

        $products = Product::whereIn('id', $validated['ids'])->get();
        $count = $products->count();

        foreach ($products as $prod) {
            if ($prod->image && Storage::disk('public')->exists($prod->image)) {
                Storage::disk('public')->delete($prod->image);
            }
            $prod->delete();
        }

        return redirect()->route('products.index')->with('success', "Se eliminaron {$count} productos seleccionados correctamente.");
    }

    public function deleteAll(): RedirectResponse
    {
        $products = Product::all();
        $count = $products->count();

        foreach ($products as $prod) {
            if ($prod->image && Storage::disk('public')->exists($prod->image)) {
                Storage::disk('public')->delete($prod->image);
            }
            $prod->delete();
        }

        return redirect()->route('products.index')->with('success', "Todos los {$count} productos han sido eliminados correctamente.");
    }

    /**
     * Import products in bulk from Excel (.xlsx) or CSV.
     */
    public function import(Request $request, ExcelImportService $importService): RedirectResponse
    {
        $request->validate([
            'excel_file' => ['required', 'file', 'max:51200'], // max 50MB
        ]);

        $file = $request->file('excel_file');
        $ext = strtolower($file->getClientOriginalExtension());

        if (! in_array($ext, ['xlsx', 'csv', 'txt'])) {
            return redirect()->back()->with('error', 'Formato no soportado. Por favor sube un archivo de Excel (.xlsx) o (.csv).');
        }

        $rows = $importService->parseFile($file->getRealPath(), $ext);

        if (empty($rows)) {
            return redirect()->back()->with('error', 'El archivo no contiene filas o no se pudo procesar.');
        }

        $created = 0;
        $updated = 0;

        DB::transaction(function () use ($rows, &$created, &$updated) {
            foreach ($rows as $row) {
                // Find product name
                $name = $row['nombre'] ?? $row['name'] ?? $row['producto'] ?? $row['titulo'] ?? null;
                if (empty($name)) {
                    continue;
                }

                // Find or create category
                $categoryName = $row['categoria'] ?? $row['category'] ?? $row['subcategoria'] ?? 'General';
                $categoryId = $this->resolveCategory($categoryName);

                // Price parsing
                $priceRaw = $row['precio'] ?? $row['price'] ?? $row['precio_venta'] ?? '0';
                $price = $this->parsePrice($priceRaw);

                $minPriceRaw = $row['precio_minimo'] ?? $row['min_price'] ?? $row['precio_min'] ?? null;
                $minPrice = $minPriceRaw !== null && $minPriceRaw !== '' ? $this->parsePrice($minPriceRaw) : null;

                $stock = isset($row['stock']) && is_numeric($row['stock']) ? (int) $row['stock'] : 10;
                $description = $row['descripcion'] ?? $row['description'] ?? $row['caracteristicas'] ?? $row['detalle'] ?? null;
                $imageUrl = $row['imagen'] ?? $row['image'] ?? $row['url_imagen'] ?? $row['foto'] ?? null;

                $isFeaturedRaw = $row['destacado'] ?? $row['featured'] ?? $row['is_featured'] ?? '0';
                $isFeatured = in_array(strtolower(trim((string) $isFeaturedRaw)), ['1', 'si', 'sí', 'true', 'yes']);

                $isActiveRaw = $row['activo'] ?? $row['active'] ?? $row['is_active'] ?? '1';
                $isActive = ! in_array(strtolower(trim((string) $isActiveRaw)), ['0', 'no', 'false']);

                $slug = Str::slug($name);

                // Check if product exists by name or slug
                $existing = Product::where('name', $name)->orWhere('slug', $slug)->first();

                if ($existing) {
                    $existing->update([
                        'category_id' => $categoryId,
                        'name' => $name,
                        'description' => $description ?? $existing->description,
                        'price' => $price,
                        'min_price' => $minPrice,
                        'stock' => $stock,
                        'is_active' => $isActive,
                        'is_featured' => $isFeatured,
                        'image_url' => (! empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) ? $imageUrl : $existing->image_url,
                    ]);
                    $updated++;
                } else {
                    $originalSlug = $slug;
                    $counter = 1;
                    while (Product::where('slug', $slug)->exists()) {
                        $slug = "{$originalSlug}-{$counter}";
                        $counter++;
                    }

                    Product::create([
                        'category_id' => $categoryId,
                        'name' => $name,
                        'slug' => $slug,
                        'description' => $description,
                        'price' => $price,
                        'min_price' => $minPrice,
                        'stock' => $stock,
                        'is_active' => $isActive,
                        'is_featured' => $isFeatured,
                        'image_url' => (! empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL)) ? $imageUrl : null,
                    ]);
                    $created++;
                }
            }
        });

        $msg = "Importación completada: se registraron {$created} productos nuevos y se actualizaron {$updated} productos existentes.";

        return redirect()->route('products.index')->with('success', $msg);
    }

    /**
     * Download Excel CSV template with sample rows and UTF-8 BOM.
     */
    public function downloadTemplate(): StreamedResponse
    {
        $headers = ['nombre', 'categoria', 'precio', 'precio_minimo', 'stock', 'descripcion', 'url_imagen', 'destacado', 'activo'];
        $sampleRows = [
            ['Laptop Lenovo IdeaPad Slim 3 15.6"', 'Computación > Laptops', '2499.00', '2350.00', '15', 'Procesador Intel Core i5 12va Gen, 16GB RAM, 512GB SSD, Pantalla Full HD 15.6 pulgadas.', 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800', 'SI', 'SI'],
            ['Monitor Gamer Samsung Odyssey G5 27" 165Hz', 'Computación > Monitores', '1199.00', '1100.00', '8', 'Resolución QHD 2560x1440, Panel Curvo 1000R, 1ms, FreeSync Premium.', '', 'SI', 'SI'],
            ['Auriculares Gamer HyperX Cloud II Wireless', 'Accesorios > Audífonos', '489.00', '450.00', '20', 'Sonido envolvente 7.1 virtual, hasta 30 horas de batería, micrófono con cancelación de ruido.', '', 'NO', 'SI'],
            ['Teclado Mecánico RGB Redragon Kumara K552', 'Accesorios > Teclados', '179.00', '160.00', '25', 'Switches Outemu Blue, retroiluminación RGB, estructura de aluminio.', '', 'NO', 'SI'],
        ];

        return response()->streamDownload(function () use ($headers, $sampleRows) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output, $headers, ';');
            foreach ($sampleRows as $row) {
                fputcsv($output, $row, ';');
            }
            fclose($output);
        }, 'Plantilla-Importar-Productos-Excel.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export all products to Excel compatible CSV.
     */
    public function exportCsv(): StreamedResponse
    {
        $products = Product::with('category.parent')->orderBy('name')->get();

        return response()->streamDownload(function () use ($products) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output, ['ID', 'Nombre', 'Categoría', 'Precio', 'Precio Mínimo', 'Stock', 'Descripción', 'URL Imagen', 'Destacado', 'Activo'], ';');

            foreach ($products as $p) {
                $catName = $p->category ? ($p->category->parent ? $p->category->parent->name.' > '.$p->category->name : $p->category->name) : 'General';
                fputcsv($output, [
                    $p->id,
                    $p->name,
                    $catName,
                    number_format($p->price, 2, '.', ''),
                    $p->min_price ? number_format($p->min_price, 2, '.', '') : '',
                    $p->stock,
                    $p->description,
                    $p->image_url ?? ($p->image ? asset('storage/'.$p->image) : ''),
                    $p->is_featured ? 'SI' : 'NO',
                    $p->is_active ? 'SI' : 'NO',
                ], ';');
            }
            fclose($output);
        }, 'Catalogo-Productos-'.now()->format('Y-m-d').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function resolveCategory(string $categoryStr): int
    {
        $categoryStr = trim($categoryStr);
        if (empty($categoryStr)) {
            $categoryStr = 'General';
        }

        $parts = preg_split('/\s*[>\/]\s*/', $categoryStr);
        $parentId = null;
        $lastCategory = null;

        foreach ($parts as $part) {
            $partName = trim($part);
            if (empty($partName)) {
                continue;
            }

            $partSlug = Str::slug($partName);
            $cat = Category::where('slug', $partSlug)->where('parent_id', $parentId)->first();

            if (! $cat) {
                $cat = Category::create([
                    'parent_id' => $parentId,
                    'name' => $partName,
                    'slug' => $partSlug,
                ]);
            }

            $parentId = $cat->id;
            $lastCategory = $cat;
        }

        return $lastCategory ? $lastCategory->id : Category::firstOrCreate(['name' => 'General', 'slug' => 'general'])->id;
    }

    private function parsePrice(mixed $price): float
    {
        if (is_numeric($price)) {
            return (float) $price;
        }

        $str = (string) $price;
        $str = preg_replace('/[^\d.,]/', '', $str);

        if (str_contains($str, ',') && str_contains($str, '.')) {
            $str = str_replace(',', '', $str);
        } elseif (str_contains($str, ',') && ! str_contains($str, '.')) {
            $str = str_replace(',', '.', $str);
        }

        return (float) $str;
    }
}
