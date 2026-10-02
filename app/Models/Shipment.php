<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    use HasFactory;

    public const STATUSES = [
        'booked',
        'picked_up',
        'in_transit',
        'out_for_delivery',
        'delivered',
        'failed',
        'returned',
        'cancelled',
    ];

    protected $fillable = [
        'order_id',
        'courier_id',
        'tracking_number',
        'external_shipment_id',
        'booking_reference',
        'shipment_status',
        'weight',
        'pieces',
        'packaging_type',
        'dimensions',
        'shipping_fee',
        'cod_amount',
        'advance_amount',
        'declared_value',
        'destination_city',
        'consignee_name',
        'consignee_phone',
        'consignee_address',
        'notes',
        'label_url',
        'pickup_date',
        'expected_delivery_date',
        'dispatched_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'cod_amount' => 'decimal:2',
            'advance_amount' => 'decimal:2',
            'declared_value' => 'decimal:2',
            'pieces' => 'integer',
            'pickup_date' => 'date',
            'expected_delivery_date' => 'date',
            'dispatched_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(ShipmentEvent::class)->orderBy('event_time', 'desc');
    }

    public function scopeBooked(Builder $query): Builder
    {
        return $query->where('shipment_status', 'booked');
    }

    public function scopeInTransit(Builder $query): Builder
    {
        return $query->whereIn('shipment_status', ['picked_up', 'in_transit', 'out_for_delivery']);
    }

    public function scopeDelivered(Builder $query): Builder
    {
        return $query->where('shipment_status', 'delivered');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->shipment_status) {
            'booked' => 'Shipment Booked',
            'picked_up' => 'Picked Up by Courier',
            'in_transit' => 'In Transit',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'failed' => 'Delivery Attempt Failed',
            'returned' => 'Returned to Origin',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->shipment_status)),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->shipment_status) {
            'booked' => 'blue',
            'picked_up' => 'indigo',
            'in_transit' => 'purple',
            'out_for_delivery' => 'amber',
            'delivered' => 'emerald',
            'failed', 'returned', 'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getTrackingUrlAttribute(): ?string
    {
        return $this->courier?->getTrackingUrl($this->tracking_number);
    }

    /**
     * Add a timeline milestone event to this shipment.
     */
    public function addEvent(string $status, string $description, ?string $location = null, ?\DateTimeInterface $time = null): ShipmentEvent
    {
        return $this->events()->create([
            'status' => $status,
            'location' => $location,
            'description' => $description,
            'event_time' => $time ?? Carbon::now(),
        ]);
    }
}
