<?php

namespace Database\Factories;

use App\Models\Courier;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shipment>
 */
class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'courier_id' => Courier::factory(),
            'tracking_number' => strtoupper($this->faker->bothify('??#########')),
            'external_shipment_id' => null,
            'shipment_status' => 'booked',
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'pieces' => $this->faker->numberBetween(1, 5),
            'shipping_fee' => $this->faker->randomElement([0, 199, 250, 300]),
            'cod_amount' => 0,
            'destination_city' => $this->faker->randomElement(['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Peshawar', 'Quetta', 'Multan', 'Faisalabad']),
            'consignee_name' => $this->faker->name(),
            'consignee_phone' => $this->faker->numerify('03##########'),
            'consignee_address' => $this->faker->streetAddress(),
            'notes' => null,
            'label_url' => null,
            'dispatched_at' => null,
            'delivered_at' => null,
        ];
    }

    /** COD shipment where cash must be collected on delivery. */
    public function cod(float $amount): static
    {
        return $this->state(['cod_amount' => $amount]);
    }

    /** Shipment that has been picked up and is in transit. */
    public function inTransit(): static
    {
        return $this->state([
            'shipment_status' => 'in_transit',
            'dispatched_at' => now()->subHours(3),
        ]);
    }

    /** Shipment that was delivered successfully. */
    public function delivered(): static
    {
        return $this->state([
            'shipment_status' => 'delivered',
            'dispatched_at' => now()->subDays(2),
            'delivered_at' => now(),
        ]);
    }

    /** Shipment with a failed delivery attempt. */
    public function failed(): static
    {
        return $this->state([
            'shipment_status' => 'failed',
            'dispatched_at' => now()->subDays(1),
        ]);
    }
}
