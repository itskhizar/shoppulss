<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->words(fake()->numberBetween(2, 4), true);
        $regularPrice = fake()->randomElement([1499, 1999, 2499, 3999, 4999, 8999, 12999, 18999, 29999]);
        $hasSale = fake()->boolean(60);
        $salePrice = $hasSale ? round($regularPrice * fake()->randomFloat(2, 0.70, 0.90), -1) : null;

        return [
            'sku' => 'SKU-'.strtoupper(Str::random(8)),
            'barcode' => fake()->ean13(),
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1000, 99999),
            'short_description' => fake()->sentence(12),
            'description' => fake()->paragraphs(3, true),
            'category_id' => Category::factory(),
            'regular_price' => $regularPrice,
            'sale_price' => $salePrice,
            'cost_price' => round($regularPrice * 0.55, 2),
            'currency' => 'PKR',
            'stock_quantity' => fake()->numberBetween(10, 150),
            'low_stock_threshold' => 5,
            'weight' => fake()->randomFloat(3, 0.2, 5.0),
            'is_featured' => fake()->boolean(25),
            'is_new' => fake()->boolean(30),
            'status' => 'published',
            'seo_title' => ucwords($name).' - Best Price in Pakistan | ShopPulss',
            'seo_description' => fake()->sentence(15),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600',
                'alt_text' => $product->name,
                'display_order' => 1,
                'is_featured' => true,
            ]);

            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $product->sku.'-VAR1',
                'name' => 'Standard',
                'price' => $product->regular_price,
                'sale_price' => $product->sale_price,
                'stock_quantity' => $product->stock_quantity,
                'status' => 'active',
            ]);
        });
    }
}
