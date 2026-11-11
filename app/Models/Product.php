<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'price_per_unit' => 'decimal:3',
        'on_sale' => 'boolean',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'average_rating' => 'float',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getPrimaryImageAttribute(): ?ProductImage
    {
        return $this->images->firstWhere('is_primary', true) ?? $this->images->first();
    }

    public function getFormattedPriceAttribute(): string
    {
        return shop_money($this->price);
    }

    public function getFormattedGrundpreisAttribute(): ?string
    {
        if ($this->price_per_unit === null || ! $this->price_per_unit_label) {
            return null;
        }

        $label = str_replace('CHF/', shop_currency().'/', (string) $this->price_per_unit_label);

        return 'Grundpreis: '.number_format((float) $this->price_per_unit, 2, '.', "'").' '.$label;
    }
}
