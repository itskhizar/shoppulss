<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        $cityData = fake()->randomElement([
            ['city' => 'Karachi', 'province' => 'Sindh', 'postal' => '74400', 'areas' => ['Clifton', 'DHA Phase 5', 'Gulshan-e-Iqbal', 'North Nazimabad']],
            ['city' => 'Lahore', 'province' => 'Punjab', 'postal' => '54000', 'areas' => ['Gulberg III', 'DHA Phase 6', 'Model Town', 'Johar Town']],
            ['city' => 'Islamabad', 'province' => 'Federal Capital', 'postal' => '44000', 'areas' => ['Sector F-7/2', 'Sector F-10', 'Sector G-11', 'Bahria Town']],
            ['city' => 'Rawalpindi', 'province' => 'Punjab', 'postal' => '46000', 'areas' => ['Saddar', 'Satellite Town', 'Westridge', 'Chaklala']],
            ['city' => 'Abbottabad', 'province' => 'Khyber Pakhtunkhwa', 'postal' => '22010', 'areas' => ['Mandian', 'Supply', 'Jinnahabad', 'Kakul Road']],
            ['city' => 'Peshawar', 'province' => 'Khyber Pakhtunkhwa', 'postal' => '25000', 'areas' => ['Hayatabad Phase 3', 'University Town', 'Cantt', 'Warsak Road']],
            ['city' => 'Faisalabad', 'province' => 'Punjab', 'postal' => '38000', 'areas' => ['Madina Town', 'D Ground', 'Peoples Colony', 'Civil Lines']],
        ]);

        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['shipping', 'billing', 'both']),
            'full_name' => fake()->name(),
            'phone' => '+923'.fake()->numberBetween(10000000, 49999999),
            'province' => $cityData['province'],
            'city' => $cityData['city'],
            'area' => fake()->randomElement($cityData['areas']),
            'street_address' => 'House #'.fake()->numberBetween(1, 450).', Street #'.fake()->numberBetween(1, 25),
            'landmark' => 'Near '.fake()->company().' Plaza',
            'postal_code' => $cityData['postal'],
            'is_default' => fake()->boolean(40),
        ];
    }
}
