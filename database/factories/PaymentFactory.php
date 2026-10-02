<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'payment_method' => 'cod',
            'transaction_reference' => null,
            'amount' => $this->faker->randomFloat(2, 500, 50000),
            'currency' => 'PKR',
            'status' => 'pending',
            'bank_name' => null,
            'sender_account_or_phone' => null,
            'receipt_path' => null,
            'gateway_response' => null,
            'paid_at' => null,
            'verified_by' => null,
            'verified_at' => null,
            'verification_notes' => null,
        ];
    }

    /** COD payment awaiting delivery collection. */
    public function cod(): static
    {
        return $this->state([
            'payment_method' => 'cod',
            'status' => 'pending',
        ]);
    }

    /** Bank transfer pending admin verification. */
    public function bankTransferPending(): static
    {
        return $this->state([
            'payment_method' => 'bank_transfer',
            'status' => 'pending_verification',
            'transaction_reference' => $this->faker->numerify('TXN###########'),
            'bank_name' => $this->faker->randomElement(['HBL', 'Meezan Bank', 'MCB', 'Alfalah Bank', 'UBL']),
            'sender_account_or_phone' => $this->faker->numerify('03##########'),
        ]);
    }

    /** Fully paid and verified payment. */
    public function paid(): static
    {
        return $this->state([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /** EasyPaisa mobile wallet payment. */
    public function easypaisa(): static
    {
        return $this->state([
            'payment_method' => 'easypaisa',
            'status' => 'pending',
            'sender_account_or_phone' => $this->faker->numerify('03##########'),
        ]);
    }

    /** JazzCash mobile wallet payment. */
    public function jazzcash(): static
    {
        return $this->state([
            'payment_method' => 'jazzcash',
            'status' => 'pending',
            'sender_account_or_phone' => $this->faker->numerify('03##########'),
        ]);
    }

    /** Failed payment. */
    public function failed(): static
    {
        return $this->state([
            'status' => 'failed',
            'verification_notes' => 'Transaction reference not found in bank records.',
        ]);
    }
}
