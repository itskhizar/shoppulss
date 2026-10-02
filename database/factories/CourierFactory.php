<?php

namespace Database\Factories;

use App\Models\Courier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Courier>
 */
class CourierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $couriers = [
            ['name' => 'Leopards Courier', 'code' => 'leopards', 'template' => 'https://leopardscourier.com/track?cn={tracking_number}'],
            ['name' => 'TCS Courier', 'code' => 'tcs', 'template' => 'https://www.tcs.com.pk/tracking/?cn={tracking_number}'],
            ['name' => 'Trax Logistics', 'code' => 'trax', 'template' => 'https://app.trax.pk/track?ref={tracking_number}'],
        ];

        $pick = $this->faker->randomElement($couriers);

        return [
            'name' => $pick['name'],
            'code' => $pick['code'].'-'.$this->faker->unique()->numerify('###'),
            'tracking_url_template' => $pick['template'],
            'contact_phone' => $this->faker->numerify('0300#######'),
            'contact_email' => $this->faker->safeEmail(),
            'is_active' => true,
            'api_settings' => null,
        ];
    }

    /** Courier with a known code (e.g. 'leopards'). */
    public function leopards(): static
    {
        return $this->state([
            'name' => 'Leopards Courier',
            'code' => 'leopards',
            'tracking_url_template' => 'https://leopardscourier.com/track?cn={tracking_number}',
        ]);
    }

    /** Manual / walk-in courier (no API). */
    public function manual(): static
    {
        return $this->state([
            'name' => 'Local Rider',
            'code' => 'manual',
            'tracking_url_template' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
