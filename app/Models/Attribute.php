<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'status',
    ];

    /**
     * Attribute values (e.g., Color → Black, White, Blue).
     */
    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class)->orderBy('display_order');
    }

    /**
     * Categories this attribute is linked to.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_attributes');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
