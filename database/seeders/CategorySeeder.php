<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Computación',
            'Accesorios',
            'Componentes',
            'Impresoras',
            'Redes',
            'Gaming',
            'Almacenamiento',
            'Cables y adaptadores',
            'Otros',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => Str::slug($category)],
                [
                    'name' => $category,
                    'description' => 'Descripción de '.$category,
                ]
            );
        }
    }
}
