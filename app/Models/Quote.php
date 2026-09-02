<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_number',
        'customer_name',
        'customer_document',
        'customer_document_type',
        'customer_phone',
        'customer_email',
        'customer_address',
        'city',
        'validity_days',
        'status',
        'subtotal',
        'discount',
        'total',
        'notes',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'validity_days' => 'integer',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    /**
     * Generate the next sequential quote number (e.g. COT-2026-0001).
     */
    public static function generateNextNumber(): string
    {
        $year = date('Y');
        $last = self::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        $sequence = 1;
        if ($last && preg_match('/COT-\d{4}-(\d+)/', $last->quote_number, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return sprintf('COT-%s-%04d', $year, $sequence);
    }
}
