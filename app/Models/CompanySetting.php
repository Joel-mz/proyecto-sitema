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

    /**
     * Get clean international WhatsApp number for WhatsApp links (e.g. 51987654321).
     */
    public function getWhatsappNumberAttribute(): string
    {
        $raw = $this->whatsapp ?: $this->phone ?: '51987654321';
        $clean = preg_replace('/[^0-9]/', '', $raw);

        // If it's a 9-digit Peruvian cell phone number starting with 9, prepend Peru country code (51)
        if (strlen($clean) === 9 && str_starts_with($clean, '9')) {
            $clean = '51'.$clean;
        }

        return $clean ?: '51987654321';
    }

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
