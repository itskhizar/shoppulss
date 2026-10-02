<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image_url',
        'seo_title',
        'seo_description',
        'display_order',
        'is_featured',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('display_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Attributes attached to this category (drives dynamic product form fields).
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'category_attributes');
    }

    /**
     * Recursively retrieve all descendant category IDs (children, grandchildren, etc.).
     */
    public function getAllDescendantIds(): array
    {
        $ids = [];
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllDescendantIds());
        }

        return $ids;
    }

    /**
     * Get all products including descendant subcategories.
     */
    public function productsWithDescendants()
    {
        $categoryIds = array_merge([$this->id], $this->getAllDescendantIds());

        return Product::whereIn('category_id', $categoryIds);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeParents(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }
}
