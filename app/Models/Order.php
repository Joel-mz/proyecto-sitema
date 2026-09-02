<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_document',
        'customer_document_type',
        'customer_phone',
        'delivery_mode',
        'delivery_address',
        'payment_method',
        'subtotal',
        'total',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateNextNumber(): string
    {
        $year = date('Y');
        $last = self::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $sequence = 1;
        if ($last && preg_match('/PED-\d{4}-(\d+)/', $last->order_number, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return sprintf('PED-%s-%04d', $year, $sequence);
    }
}
