<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'category',
        'body',
        'summary',
        'seo_title',
        'seo_description',
        'canonical_url',
        'robots_directive',
        'og_image',
        'is_published',
        'show_in_footer',
        'show_in_sitemap',
        'effective_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'show_in_footer' => 'boolean',
            'show_in_sitemap' => 'boolean',
            'effective_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeInFooter(Builder $query): Builder
    {
        return $query->where('show_in_footer', true);
    }

    public function scopeInSitemap(Builder $query): Builder
    {
        return $query->where('show_in_sitemap', true);
    }

    public function getEffectiveDateFormattedAttribute(): string
    {
        return ($this->effective_at ?? $this->updated_at ?? now())->format('F j, Y');
    }
}
