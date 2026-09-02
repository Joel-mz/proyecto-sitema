<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comp = Category::where('slug', 'computacion')->first();
        $acc = Category::where('slug', 'accesorios')->first();
        $part = Category::where('slug', 'componentes')->first();
        $imp = Category::where('slug', 'impresoras')->first();
        $gam = Category::where('slug', 'gaming')->first();
        $alm = Category::where('slug', 'almacenamiento')->first();
        $cab = Category::where('slug', 'cables-y-adaptadores')->first();

        $products = [
            [
                'category_id' => $comp?->id,
                'name' => 'Laptop Lenovo IdeaPad Slim 3 15.6"',
                'description' => 'Procesador Intel Core i5 12va Gen, 16GB RAM DDR4, 512GB SSD NVMe, Pantalla FHD IPS, Windows 11 Home.',
                'price' => 2499.00,
                'min_price' => 2299.00,
                'is_active' => true,
            ],
            [
                'category_id' => $comp?->id,
                'name' => 'Laptop ASUS Vivobook 15 OLED',
                'description' => 'Pantalla 15.6" OLED FHD, AMD Ryzen 7 7730U, 16GB RAM, 1TB SSD, Teclado retroiluminado, Batería de larga duración.',
                'price' => 3199.00,
                'min_price' => 2950.00,
                'is_active' => true,
            ],
            [
                'category_id' => $acc?->id,
                'name' => 'Mouse Inalámbrico Logitech MX Master 3S',
                'description' => 'Sensor Darkfield de 8000 DPI, clics silenciosos, desplazamiento electromagnético MagSpeed, conexión Bluetooth y receptor Logi Bolt.',
                'price' => 429.00,
                'min_price' => 380.00,
                'is_active' => true,
            ],
            [
                'category_id' => $acc?->id,
                'name' => 'Teclado Mecánico Redragon Kumara K552 RGB',
                'description' => 'Switches Outemu Red lineales, retroiluminación RGB configurable, diseño compacto Tenkeyless (TKL) de alta durabilidad.',
                'price' => 179.00,
                'min_price' => 150.00,
                'is_active' => true,
            ],
            [
                'category_id' => $gam?->id,
                'name' => 'Monitor Gamer Samsung Odyssey G5 27" 165Hz',
                'description' => 'Resolución QHD (2560x1440), panel curvo 1000R, 1ms tiempo de respuesta, compatible con AMD FreeSync Premium y HDR10.',
                'price' => 1199.00,
                'min_price' => 1050.00,
                'is_active' => true,
            ],
            [
                'category_id' => $gam?->id,
                'name' => 'Auriculares Gamer HyperX Cloud II Wireless',
                'description' => 'Sonido envolvente 7.1 virtual, transductores de 53mm, batería de hasta 30 horas, micrófono desmontable con cancelación de ruido.',
                'price' => 489.00,
                'min_price' => 430.00,
                'is_active' => true,
            ],
            [
                'category_id' => $part?->id,
                'name' => 'Procesador AMD Ryzen 7 5700X',
                'description' => '8 núcleos y 16 hilos, reloj base 3.4GHz / Boost 4.6GHz, 32MB L3 Caché, socket AM4 (sin disipador incluido).',
                'price' => 749.00,
                'min_price' => 680.00,
                'is_active' => true,
            ],
            [
                'category_id' => $part?->id,
                'name' => 'Tarjeta de Video GeForce RTX 4060 8GB GDDR6',
                'description' => 'Arquitectura NVIDIA Ada Lovelace, DLSS 3, Ray Tracing, 3 ventiladores de enfriamiento, salidas HDMI 2.1a y DisplayPort 1.4a.',
                'price' => 1599.00,
                'min_price' => 1450.00,
                'is_active' => true,
            ],
            [
                'category_id' => $alm?->id,
                'name' => 'Disco Sólido Kingston NV2 1TB M.2 NVMe PCIe 4.0',
                'description' => 'Velocidades de lectura de hasta 3500MB/s y escritura de 2100MB/s. Factor de forma compacto M.2 2280.',
                'price' => 269.00,
                'min_price' => 235.00,
                'is_active' => true,
            ],
            [
                'category_id' => $imp?->id,
                'name' => 'Impresora Multifuncional Epson EcoTank L3250 Wi-Fi',
                'description' => 'Sistema continuo de tanques de tinta original, impresión inalámbrica desde smartphone, costo de impresión ultrabajo por página.',
                'price' => 789.00,
                'min_price' => 720.00,
                'is_active' => true,
            ],
            [
                'category_id' => $cab?->id,
                'name' => 'Hub Adaptador USB-C 7 en 1 con HDMI 4K',
                'description' => 'Incluye puerto HDMI 4K@30Hz, 3 puertos USB 3.0, lector de tarjetas SD/TF y puerto de carga rápida USB-C Power Delivery 100W.',
                'price' => 129.00,
                'min_price' => 100.00,
                'is_active' => true,
            ],
        ];

        foreach ($products as $p) {
            if ($p['category_id']) {
                Product::updateOrCreate(
                    ['slug' => Str::slug($p['name'])],
                    [
                        'category_id' => $p['category_id'],
                        'name' => $p['name'],
                        'description' => $p['description'],
                        'price' => $p['price'],
                        'min_price' => $p['min_price'],
                        'is_active' => $p['is_active'],
                    ]
                );
            }
        }
    }
}
