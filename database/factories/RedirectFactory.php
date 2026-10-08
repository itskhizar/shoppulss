<?php

namespace Database\Factories;

use App\Models\Redirect;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Redirect>
 */
class RedirectFactory extends Factory
{
    protected $model = Redirect::class;

    public function definition(): array
    {
        return [
            'source_path' => fake()->unique()->slug(),
            'target_path' => '/'.fake()->slug(),
            'status_code' => 301,
            'is_active' => true,
        ];
    }
}
