<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'tracking_url_template',
        'contact_phone',
        'contact_email',
        'is_active',
        'api_settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'api_settings' => 'array',
        ];
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Build public tracking URL for a given tracking number.
     */
    public function getTrackingUrl(string $trackingNumber): ?string
    {
        if (empty($this->tracking_url_template)) {
            return null;
        }

        return str_replace('{tracking_number}', urlencode($trackingNumber), $this->tracking_url_template);
    }
}
