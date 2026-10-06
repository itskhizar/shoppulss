<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'email',
        'phone',
        'shipping_address_id',
        'billing_address_id',
        'status',
        'payment_method',
        'payment_status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'shipping_amount',
        'total_amount',
        'currency',
        'coupon_code',
        'customer_notes',
        'internal_notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    /** V3 status lifecycle constants. */
    public const STATUSES = [
        'pending', 'payment_pending', 'paid', 'confirmed', 'processing', 'packed', 'shipped', 'delivered',
        'cancelled', 'return_requested', 'returned', 'refund_pending', 'refunded',
    ];

    /** Valid transitions from a given status. */
    public const TRANSITIONS = [
        'pending' => ['payment_pending', 'paid', 'confirmed', 'processing', 'cancelled'],
        'payment_pending' => ['paid', 'confirmed', 'cancelled'],
        'paid' => ['processing', 'packed', 'shipped', 'cancelled', 'refund_pending'],
        'confirmed' => ['processing', 'packed', 'shipped', 'cancelled'],
        'processing' => ['packed', 'shipped', 'cancelled'],
        'packed' => ['shipped', 'cancelled'],
        'shipped' => ['delivered', 'cancelled'],
        'delivered' => ['return_requested'],
        'cancelled' => [],
        'return_requested' => ['returned', 'cancelled'],
        'returned' => ['refund_pending'],
        'refund_pending' => ['refunded', 'cancelled'],
        'refunded' => [],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class)->latestOfMany();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeProcessing(Builder $query): Builder
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped(Builder $query): Builder
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered(Builder $query): Builder
    {
        return $query->where('status', 'delivered');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', ['delivered', 'cancelled']);
    }

    /**
     * Check if a transition to $newStatus is valid.
     */
    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::TRANSITIONS[$this->status] ?? [], true);
    }

    /**
     * Human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    /**
     * Status badge colour for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'payment_pending' => 'amber',
            'paid' => 'emerald',
            'confirmed' => 'blue',
            'processing' => 'indigo',
            'packed' => 'purple',
            'shipped' => 'orange',
            'delivered' => 'green',
            'cancelled' => 'red',
            'return_requested' => 'amber',
            'returned' => 'gray',
            'refund_pending' => 'orange',
            'refunded' => 'purple',
            default => 'gray',
        };
    }
}
