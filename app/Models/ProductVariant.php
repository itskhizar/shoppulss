<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as CastAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'option_1',
        'option_1_value',
        'option_2',
        'option_2_value',
        'option_3',
        'option_3_value',
        'price',
        'sale_price',
        'stock_quantity',
        'image_url',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock_quantity' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Attribute values linked to this variant (via product_variant_attribute_values).
     */
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_values');
    }

    protected function effectivePrice(): CastAttribute
    {
        return CastAttribute::make(
            get: function () {
                if ($this->sale_price !== null && $this->sale_price > 0) {
                    return $this->sale_price;
                }

                if ($this->price !== null && $this->price > 0) {
                    return $this->price;
                }

                return $this->product?->effective_price ?? 0;
            }
        );
    }

    /**
     * Human-readable label combining option values.
     */
    public function getLabelAttribute(): string
    {
        $parts = array_filter([
            $this->option_1_value,
            $this->option_2_value,
            $this->option_3_value,
        ]);

        return $parts ? implode(' / ', $parts) : ($this->name ?? 'Standard');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
