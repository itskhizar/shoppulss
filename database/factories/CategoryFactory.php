<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(fake()->numberBetween(1, 2), true);

        return [
            'parent_id' => null,
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 9999),
            'description' => fake()->sentence(),
            'image_url' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=500',
            'display_order' => fake()->numberBetween(1, 10),
            'is_featured' => fake()->boolean(40),
            'status' => 'active',
        ];
    }
}
