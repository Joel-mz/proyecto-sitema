<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'phone',
        'whatsapp',
        'email',
        'address',
        'city_province',
        'region',
        'description',
    ];

    public static function getSettings(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'TechStore Perú',
                'phone' => '+51 987 654 321',
                'whatsapp' => '51987654321',
                'email' => 'ventas@techstore.pe',
                'address' => 'Av. Garcilaso de la Vega 1250, Tienda 204',
                'city_province' => 'Lima',
                'region' => 'Lima',
                'description' => 'Especialistas en cómputo, laptops, componentes y periféricos con los mejores precios del mercado.',
            ]
        );
    }
}
