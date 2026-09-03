<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations to properly organize categories, subcategories and assign products.
     */
    public function up(): void
    {
        $structure = [
            'Computación' => [
                'description' => 'Laptops, computadoras de escritorio, monitores y equipos informáticos.',
                'subcategories' => [
                    'Laptops' => 'Laptops para oficina, hogar, estudiantes y alto rendimiento.',
                    'Computadoras de Escritorio' => 'PCs de escritorio, All in One y estaciones de trabajo.',
                    'Monitores' => 'Monitores para oficina, diseño y gaming.',
                ],
            ],
            'Impresoras y Suministros' => [
                'description' => 'Impresoras multifuncionales, sistemas continuos, tintas y consumibles.',
                'subcategories' => [
                    'Impresoras Multifuncionales' => 'Impresoras a inyección de tinta, sistema continuo y láser.',
                    'Tintas y Consumibles' => 'Botellas de tinta original Epson, Brother, HP y Canon.',
                    'Tintas Genéricas' => 'Tintas compatibles y genéricas de alta calidad para impresoras.',
                ],
            ],
            'Audio y Video' => [
                'description' => 'Parlantes, micrófonos, audífonos y sistemas de sonido.',
                'subcategories' => [
                    'Parlantes' => 'Parlantes Bluetooth portátiles, recargables e impermeables.',
                    'Micrófonos' => 'Micrófonos inalámbricos y alámbricos profesionales.',
                    'Audífonos' => 'Audífonos inalámbricos, diademas y auriculares.',
                ],
            ],
            'Seguridad y Vigilancia' => [
                'description' => 'Cámaras de seguridad Wi-Fi, domos exteriores, cámaras 360 e interiores.',
                'subcategories' => [
                    'Cámaras de Seguridad' => 'Cámaras de seguridad para interiores y exteriores EZVIZ, Dahua, Hikvision.',
                ],
            ],
            'Fotografía y Video' => [
                'description' => 'Estabilizadores, gimbals, trípodes y accesorios para creadores de contenido.',
                'subcategories' => [
                    'Estabilizadores' => 'Gimbals estabilizadores de 3 ejes con seguimiento inteligente.',
                ],
            ],
            'Accesorios' => [
                'description' => 'Teclados, mouse, pads, mochilas y accesorios para laptop y PC.',
                'subcategories' => [
                    'Teclados' => 'Teclados mecánicos y de membrana.',
                    'Mouse' => 'Mouse ópticos, ergonómicos e inalámbricos.',
                ],
            ],
            'Componentes' => [
                'description' => 'Memorias RAM, discos sólidos SSD, tarjetas de video y fuentes.',
                'subcategories' => [
                    'Memorias RAM' => 'Memorias DDR4 y DDR5 para laptops y PC.',
                    'Almacenamiento SSD' => 'Discos de estado sólido M.2 NVMe y SATA.',
                ],
            ],
            'Redes y Conectividad' => [
                'description' => 'Routers, repetidores Wi-Fi, switches y cables de red.',
                'subcategories' => [
                    'Routers y Repetidores' => 'Dispositivos de red Wi-Fi y extensores de rango.',
                    'Cables y Adaptadores' => 'Cables HDMI, adaptadores y cables de red.',
                ],
            ],
        ];

        $createdParents = [];
        $createdSubs = [];

        foreach ($structure as $parentName => $data) {
            $parentSlug = Str::slug($parentName);
            $parent = Category::where('slug', $parentSlug)->orWhere('name', $parentName)->first();

            if (! $parent) {
                $parent = Category::create([
                    'parent_id' => null,
                    'name' => $parentName,
                    'slug' => $parentSlug,
                    'description' => $data['description'],
                ]);
            } else {
                $parent->update([
                    'parent_id' => null,
                    'name' => $parentName,
                    'description' => $data['description'],
                ]);
            }

            $createdParents[$parentName] = $parent;

            foreach ($data['subcategories'] as $subName => $subDesc) {
                $subSlug = Str::slug($subName);
                $sub = Category::where('slug', $subSlug)->orWhere('name', $subName)->first();

                if (! $sub) {
                    $sub = Category::create([
                        'parent_id' => $parent->id,
                        'name' => $subName,
                        'slug' => $subSlug,
                        'description' => $subDesc,
                    ]);
                } else {
                    $sub->update([
                        'parent_id' => $parent->id,
                        'name' => $subName,
                        'description' => $subDesc,
                    ]);
                }

                $createdSubs[$subName] = $sub;
            }
        }

        // Clean up redundant / duplicate legacy categories
        $oldCats = Category::whereIn('name', ['Impresión', 'Impresoras'])->get();
        foreach ($oldCats as $old) {
            if (isset($createdParents['Impresoras y Suministros']) && $old->id !== $createdParents['Impresoras y Suministros']->id) {
                Product::where('category_id', $old->id)->update(['category_id' => $createdSubs['Impresoras Multifuncionales']->id]);
                Category::where('parent_id', $old->id)->update(['parent_id' => $createdParents['Impresoras y Suministros']->id]);
                $old->delete();
            }
        }

        // Smart mapping of products into their respective subcategories
        $allProducts = Product::all();
        foreach ($allProducts as $prod) {
            $name = strtolower($prod->name);

            if (str_contains($name, 'laptop') || str_contains($name, 'ideapad') || str_contains($name, 'notebook') || str_contains($name, 'hp 250') || str_contains($name, 'hp 15-')) {
                if (isset($createdSubs['Laptops'])) {
                    $prod->update(['category_id' => $createdSubs['Laptops']->id]);
                }
            } elseif (str_contains($name, 'cámara') || str_contains($name, 'camara') || str_contains($name, 'ezviz')) {
                if (isset($createdSubs['Cámaras de Seguridad'])) {
                    $prod->update(['category_id' => $createdSubs['Cámaras de Seguridad']->id]);
                }
            } elseif (str_contains($name, 'gimbal') || str_contains($name, 'estabilizador')) {
                if (isset($createdSubs['Estabilizadores'])) {
                    $prod->update(['category_id' => $createdSubs['Estabilizadores']->id]);
                }
            } elseif (str_contains($name, 'micrófono') || str_contains($name, 'microfono') || str_contains($name, 'mic')) {
                if (isset($createdSubs['Micrófonos'])) {
                    $prod->update(['category_id' => $createdSubs['Micrófonos']->id]);
                }
            } elseif (str_contains($name, 'parlante') || str_contains($name, 'flip') || str_contains($name, 'xbass') || str_contains($name, 'eon one') || str_contains($name, 'jbl grip')) {
                if (isset($createdSubs['Parlantes'])) {
                    $prod->update(['category_id' => $createdSubs['Parlantes']->id]);
                }
            } elseif (str_contains($name, 'impresora') || str_contains($name, 'multifuncional') || str_contains($name, 'dcp-')) {
                if (isset($createdSubs['Impresoras Multifuncionales'])) {
                    $prod->update(['category_id' => $createdSubs['Impresoras Multifuncionales']->id]);
                }
            } elseif (str_contains($name, 'tinta genérica') || str_contains($name, 'tinta generica') || str_contains($name, 'full colors') || str_contains($name, 'fc-5')) {
                if (isset($createdSubs['Tintas Genéricas'])) {
                    $prod->update(['category_id' => $createdSubs['Tintas Genéricas']->id]);
                }
            } elseif (str_contains($name, 'tinta') || str_contains($name, 'botella') || str_contains($name, 'pack de tintas') || str_contains($name, 'epson 544') || str_contains($name, 'epson 664') || str_contains($name, 'epson 504') || str_contains($name, 'brother btd') || str_contains($name, 'hp gt5')) {
                if (isset($createdSubs['Tintas y Consumibles'])) {
                    $prod->update(['category_id' => $createdSubs['Tintas y Consumibles']->id]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration needed
    }
};
