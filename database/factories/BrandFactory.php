<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(10, 999),
            'logo_url' => 'https://images.unsplash.com/photo-1599305445671-ac291c95aaa9?w=200',
            'description' => fake()->sentence(),
            'status' => 'active',
        ];
    }
}
