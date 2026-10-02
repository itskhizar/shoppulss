<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomElement([2499, 4999, 8999, 14999, 24999]);
        $shipping = fake()->randomElement([0, 199, 250, 300]);
        $discount = fake()->randomElement([0, 200, 500]);
        $total = $subtotal - $discount + $shipping;

        return [
            'order_number' => 'ORD-'.date('Ymd').'-'.fake()->unique()->numberBetween(1000, 9999),
            'user_id' => User::factory(),
            'email' => fake()->safeEmail(),
            'phone' => '+923'.fake()->numberBetween(10000000, 49999999),
            'shipping_address_id' => Address::factory(),
            'billing_address_id' => Address::factory(),
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered']),
            'payment_status' => fake()->randomElement(['pending', 'paid']),
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'tax_amount' => 0,
            'shipping_amount' => $shipping,
            'total_amount' => $total,
            'currency' => 'PKR',
            'customer_notes' => fake()->optional()->sentence(),
        ];
    }
}
