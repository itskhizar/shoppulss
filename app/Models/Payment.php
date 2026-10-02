<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    public const METHODS = [
        'cod',
        'bank_transfer',
        'easypaisa',
        'jazzcash',
        'card',
    ];

    public const STATUSES = [
        'pending',
        'pending_verification',
        'paid',
        'failed',
        'refunded',
    ];

    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_reference',
        'amount',
        'currency',
        'status',
        'bank_name',
        'sender_account_or_phone',
        'receipt_path',
        'gateway_response',
        'paid_at',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'gateway_response' => 'array',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePendingVerification(Builder $query): Builder
    {
        return $query->where('status', 'pending_verification');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }

    public function getMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cod' => 'Cash on Delivery',
            'bank_transfer' => 'Bank Transfer (Direct)',
            'easypaisa' => 'EasyPaisa Wallet / Direct',
            'jazzcash' => 'JazzCash Mobile Account',
            'card' => 'Debit / Credit Card',
            default => strtoupper($this->payment_method),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Payment',
            'pending_verification' => 'Pending Verification',
            'paid' => 'Payment Verified / Paid',
            'failed' => 'Payment Failed',
            'refunded' => 'Refunded',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'emerald',
            'pending_verification' => 'amber',
            'pending' => 'blue',
            'failed', 'refunded' => 'red',
            default => 'gray',
        };
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Mark payment as verified and paid.
     */
    public function markAsPaid(?string $notes = null, ?int $adminId = null): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(),
            'verified_by' => $adminId,
            'verified_at' => Carbon::now(),
            'verification_notes' => $notes ?? 'Verified manually by administrator.',
        ]);

        // Sync order payment status
        if ($this->order) {
            $this->order->update([
                'payment_status' => 'paid',
            ]);
        }
    }
}
