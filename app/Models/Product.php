<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute as CastAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku',
        'barcode',
        'name',
        'slug',
        'short_description',
        'description',
        'category_id',
        'type',
        'regular_price',
        'sale_price',
        'cost_price',
        'currency',
        'stock_quantity',
        'low_stock_threshold',
        'is_featured',
        'is_new',
        'is_deal',
        'deal_start_at',
        'deal_end_at',
        'is_trending',
        'status',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'regular_price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_deal' => 'boolean',
            'deal_start_at' => 'datetime',
            'deal_end_at' => 'datetime',
            'is_trending' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    public function featuredImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_featured', true);
    }

    /**
     * Customer reviews.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * Approved customer reviews.
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved')->latest();
    }

    /**
     * Category-driven dynamic attributes for this product.
     */
    public function productAttributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Attributes via the product_attribute_values pivot (what values this specific product uses).
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute_values');
    }

    /**
     * Determine if this product has an active deal running right now.
     */
    public function hasActiveDeal(): bool
    {
        if (! $this->is_deal || ! $this->deal_end_at) {
            return false;
        }

        $now = now();

        if ($this->deal_start_at && $this->deal_start_at->isFuture()) {
            return false;
        }

        return $this->deal_end_at->isFuture();
    }

    /**
     * Determine if this product has an expired deal.
     */
    public function isDealExpired(): bool
    {
        return (bool) ($this->is_deal && $this->deal_end_at && $this->deal_end_at->isPast());
    }

    /**
     * Number of seconds remaining on active deal.
     */
    public function remainingDealSeconds(): int
    {
        if (! $this->hasActiveDeal() || ! $this->deal_end_at) {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds($this->deal_end_at, false));
    }

    protected function effectivePrice(): CastAttribute
    {
        return CastAttribute::make(
            get: function () {
                // If it was a deal and the deal has expired, return regular price
                if ($this->is_deal && $this->deal_end_at && $this->deal_end_at->isPast()) {
                    return $this->regular_price;
                }

                // If deal is active, return sale price
                if ($this->hasActiveDeal() && $this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->regular_price) {
                    return $this->sale_price;
                }

                // Standard non-deal sale price
                if (! $this->is_deal && $this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->regular_price) {
                    return $this->sale_price;
                }

                return $this->regular_price;
            }
        );
    }

    protected function discountPercentage(): CastAttribute
    {
        return CastAttribute::make(
            get: function () {
                $effective = (float) $this->effective_price;
                $regular = (float) $this->regular_price;

                if ($regular > 0 && $effective < $regular) {
                    return (int) round((($regular - $effective) / $regular) * 100);
                }

                return 0;
            }
        );
    }

    protected function primaryImageUrl(): CastAttribute
    {
        return CastAttribute::make(
            get: function () {
                if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
                    $featured = $this->images->firstWhere('is_featured', true);

                    return $featured ? $featured->image_url : $this->images->first()->image_url;
                }

                return '/images/placeholder-product.png';
            }
        );
    }

    /**
     * Check if this is a simple product (no variants).
     */
    public function isSimple(): bool
    {
        return $this->type === 'simple';
    }

    /**
     * Check if this product is in stock.
     */
    public function hasStock(): bool
    {
        if ($this->isSimple()) {
            return $this->stock_quantity > 0;
        }

        return $this->variants()->where('stock_quantity', '>', 0)->where('status', 'active')->exists();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeTrending(Builder $query): Builder
    {
        return $query->where('is_trending', true);
    }

    public function scopeActiveDeals(Builder $query): Builder
    {
        return $query->where('is_deal', true)
            ->where('deal_end_at', '>', now())
            ->where(function ($q) {
                $q->whereNull('deal_start_at')->orWhere('deal_start_at', '<=', now());
            });
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeOnSale(Builder $query): Builder
    {
        return $query->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'regular_price');
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('is_new', true);
    }
}
