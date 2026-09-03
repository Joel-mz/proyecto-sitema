<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'slug',
        'description',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('name');
    }

    public function children(): HasMany
    {
        return $this->subcategories();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function isSubcategory(): bool
    {
        return ! is_null($this->parent_id);
    }

    public function isParent(): bool
    {
        return is_null($this->parent_id);
    }

    public function getTotalActiveProductsCountAttribute(): int
    {
        if ($this->relationLoaded('subcategories')) {
            $count = $this->products_count ?? $this->products()->where('is_active', true)->count();
            foreach ($this->subcategories as $sub) {
                $count += ($sub->products_count ?? $sub->products()->where('is_active', true)->count());
            }

            return $count;
        }

        $subcategoryIds = $this->subcategories()->pluck('id')->push($this->id);

        return Product::whereIn('category_id', $subcategoryIds)->where('is_active', true)->count();
    }
}
