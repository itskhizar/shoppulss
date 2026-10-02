<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    // Unsplash images per category
    private array $categoryImages = [
        'Electronics' => [
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600',
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600',
            'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=600',
            'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=600',
            'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=600',
            'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=600',
        ],
        'Fashion & Apparel' => [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600',
            'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=600',
            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=600',
            'https://images.unsplash.com/photo-1523381294911-8d3cead13475?w=600',
            'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600',
        ],
        'Home & Living' => [
            'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600',
            'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=600',
            'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600',
            'https://images.unsplash.com/photo-1584622781564-1d987f7333c1?w=600',
        ],
        'Beauty & Personal Care' => [
            'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600',
            'https://images.unsplash.com/photo-1631729371254-42c2892f0e6e?w=600',
            'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=600',
        ],
        'Sports & Outdoors' => [
            'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=600',
            'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=600',
            'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=600',
        ],
        'Accessories' => [
            'https://images.unsplash.com/photo-1523293182086-7651a899d37f?w=600',
            'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600',
        ],
    ];

    private array $products = [
        // Electronics
        ['name' => 'PulseFit Pro AMC Wireless Earbuds', 'cat' => 'Electronics', 'price' => 5890, 'sale' => 4850, 'featured' => true, 'is_new' => false],
        ['name' => 'Precision Smart Watch Series 5 with AMOLED Display', 'cat' => 'Electronics', 'price' => 9999, 'sale' => 7499, 'featured' => true, 'is_new' => false],
        ['name' => 'Precision Watch S5 Smart Fitness Tracker', 'cat' => 'Electronics', 'price' => 5999, 'sale' => 3799, 'featured' => false, 'is_new' => true],
        ['name' => 'Ergonomic Aluminium Laptop Stand & Riser', 'cat' => 'Electronics', 'price' => 3990, 'sale' => 2990, 'featured' => true, 'is_new' => false],
        ['name' => 'Minimalist Matte Ceramic Pour-Over Kettle', 'cat' => 'Home & Living', 'price' => 6290, 'sale' => 4200, 'featured' => true, 'is_new' => false],
        ['name' => 'Samsung Galaxy Buds Wireless Earphones', 'cat' => 'Electronics', 'price' => 12999, 'sale' => 9999, 'featured' => false, 'is_new' => true],
        ['name' => 'Ultra-Slim 45W GaN Fast Charger with 2 Ports', 'cat' => 'Electronics', 'price' => 3990, 'sale' => 2299, 'featured' => false, 'is_new' => true],
        ['name' => 'AeroSound Portable Bluetooth 5.3 Speaker', 'cat' => 'Electronics', 'price' => 4999, 'sale' => 3890, 'featured' => false, 'is_new' => true],
        ['name' => 'ShopPulss 20,000mAh PD 22.5W Power Bank', 'cat' => 'Accessories', 'price' => 5499, 'sale' => 3999, 'featured' => true, 'is_new' => false],
        ['name' => 'Noise-Isolating Studio Over-Ear Headphones', 'cat' => 'Electronics', 'price' => 8999, 'sale' => 6450, 'featured' => true, 'is_new' => false],
        ['name' => 'HydroFlow Thermal Double-Walled Travel Flask 750ml', 'cat' => 'Sports & Outdoors', 'price' => 2999, 'sale' => 1950, 'featured' => true, 'is_new' => false],
        ['name' => 'Magnetic Car Mount & 15W Wireless Charger', 'cat' => 'Accessories', 'price' => 3499, 'sale' => 2499, 'featured' => true, 'is_new' => false],
        // Fashion
        ['name' => 'Premium Textured Everyday Canvas Backpack', 'cat' => 'Fashion & Apparel', 'price' => 3999, 'sale' => 2950, 'featured' => false, 'is_new' => true],
        ['name' => 'Sonic Facial Cleansing & Massaging Brush', 'cat' => 'Beauty & Personal Care', 'price' => 4499, 'sale' => 3160, 'featured' => false, 'is_new' => true],
        ['name' => 'Men\'s Regular Fit Polo T-Shirt', 'cat' => 'Fashion & Apparel', 'price' => 1499, 'sale' => 999, 'featured' => false, 'is_new' => true],
        ['name' => 'Women\'s Floral Summer Dress', 'cat' => 'Fashion & Apparel', 'price' => 2499, 'sale' => 1799, 'featured' => false, 'is_new' => true],
        ['name' => 'Sports Running Shoes - Lightweight', 'cat' => 'Fashion & Apparel', 'price' => 5999, 'sale' => 4499, 'featured' => true, 'is_new' => false],
        ['name' => 'Analog Chronograph Watch Men', 'cat' => 'Fashion & Apparel', 'price' => 8999, 'sale' => 6999, 'featured' => true, 'is_new' => false],
        // Home
        ['name' => 'Non-Stick Cookware Set 5 Pieces', 'cat' => 'Home & Living', 'price' => 7999, 'sale' => 5999, 'featured' => true, 'is_new' => false],
        ['name' => 'King Size Luxury Bed Sheet Set', 'cat' => 'Home & Living', 'price' => 3499, 'sale' => 2499, 'featured' => false, 'is_new' => true],
        ['name' => 'LED Desk Lamp with USB Charging Port', 'cat' => 'Home & Living', 'price' => 2999, 'sale' => 1999, 'featured' => false, 'is_new' => false],
        ['name' => 'Ceramic Coffee Mug Set of 4', 'cat' => 'Home & Living', 'price' => 1999, 'sale' => 1499, 'featured' => false, 'is_new' => true],
        // Beauty
        ['name' => 'Vitamin C Brightening Face Serum 30ml', 'cat' => 'Beauty & Personal Care', 'price' => 2999, 'sale' => 1999, 'featured' => true, 'is_new' => false],
        ['name' => 'Professional Hair Dryer 2000W', 'cat' => 'Beauty & Personal Care', 'price' => 4999, 'sale' => 3499, 'featured' => false, 'is_new' => true],
        ['name' => 'Matte Lipstick Collection 6 Shades', 'cat' => 'Beauty & Personal Care', 'price' => 1999, 'sale' => 1299, 'featured' => false, 'is_new' => false],
        // Sports
        ['name' => 'Professional Yoga Mat with Carry Bag', 'cat' => 'Sports & Outdoors', 'price' => 2499, 'sale' => 1799, 'featured' => false, 'is_new' => true],
        ['name' => 'Adjustable Dumbbell Set 20KG', 'cat' => 'Sports & Outdoors', 'price' => 12999, 'sale' => 9999, 'featured' => true, 'is_new' => false],
        ['name' => 'Cricket Bat Full Size English Willow', 'cat' => 'Sports & Outdoors', 'price' => 8999, 'sale' => 6999, 'featured' => false, 'is_new' => false],
        // Accessories
        ['name' => 'Leather Wallet with RFID Protection', 'cat' => 'Fashion & Apparel', 'price' => 2499, 'sale' => 1799, 'featured' => false, 'is_new' => true],
        ['name' => 'Laptop Sleeve 15.6 inch Waterproof', 'cat' => 'Accessories', 'price' => 1999, 'sale' => 1299, 'featured' => false, 'is_new' => true],
    ];

    public function run(): void
    {
        foreach ($this->products as $productData) {
            $catName = $productData['cat'];
            $category = Category::where('name', 'like', '%'.explode(' &', $catName)[0].'%')
                ->whereNull('parent_id')
                ->first();

            if (! $category) {
                continue;
            }

            $slug = Str::slug($productData['name']);

            $product = Product::firstOrCreate(
                ['name' => $productData['name']],
                [
                    'sku' => 'SKU-'.strtoupper(Str::random(8)),
                    'name' => $productData['name'],
                    'slug' => $slug,
                    'short_description' => 'Premium quality '.$productData['name'].' at the best price in Pakistan.',
                    'description' => '<p>Experience the best quality with our <strong>'.$productData['name'].'</strong>. Sourced directly from top manufacturers, delivered to your doorstep with authenticity guarantee.</p><p>Features premium build quality, long-lasting durability, and comes with a warranty.</p>',
                    'category_id' => $category->id,
                    'type' => 'simple',
                    'regular_price' => $productData['price'],
                    'sale_price' => $productData['sale'],
                    'cost_price' => round($productData['price'] * 0.55, 2),
                    'currency' => 'PKR',
                    'stock_quantity' => rand(15, 120),
                    'low_stock_threshold' => 5,
                    'weight' => round(rand(1, 20) / 10, 2),
                    'is_featured' => $productData['featured'],
                    'is_new' => $productData['is_new'],
                    'status' => 'published',
                    'seo_title' => $productData['name'].' - Buy Online in Pakistan | ShopPulss',
                    'seo_description' => 'Buy '.$productData['name'].' at best price in Pakistan. Free delivery. 100% authentic.',
                ]
            );

            if ($product->images()->count() === 0) {
                $images = $this->categoryImages[$catName] ?? $this->categoryImages['Electronics'];
                $imageUrl = $images[array_rand($images)];

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imageUrl,
                    'alt_text' => $product->name,
                    'display_order' => 1,
                    'is_featured' => true,
                ]);

                if (rand(0, 1)) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $images[array_rand($images)],
                        'alt_text' => $product->name.' - view 2',
                        'display_order' => 2,
                        'is_featured' => false,
                    ]);
                }
            }

            if ($product->variants()->count() === 0) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $product->sku.'-V1',
                    'name' => 'Standard',
                    'price' => $product->regular_price,
                    'sale_price' => $product->sale_price,
                    'stock_quantity' => $product->stock_quantity,
                    'status' => 'active',
                ]);
            }
        }

        // Add extra random products to hit 100+
        $categories = Category::whereNull('parent_id')->get();
        $productsNeeded = max(0, 80 - Product::count());

        for ($i = 0; $i < $productsNeeded; $i++) {
            $category = $categories->random();
            $name = ucwords(fake()->words(fake()->numberBetween(3, 5), true));
            $slug = Str::slug($name).'-'.Str::random(6);
            $price = fake()->randomElement([1499, 1999, 2999, 4999, 7999, 12999, 19999, 29999]);
            $sale = rand(0, 1) ? round($price * fake()->randomFloat(2, 0.72, 0.88), -1) : null;

            $product = Product::create([
                'sku' => 'SKU-'.strtoupper(Str::random(8)),
                'name' => $name,
                'slug' => $slug,
                'short_description' => fake()->sentence(10),
                'description' => '<p>'.fake()->paragraph(3).'</p>',
                'category_id' => $category->id,
                'type' => 'simple',
                'regular_price' => $price,
                'sale_price' => $sale,
                'cost_price' => round($price * 0.55, 2),
                'currency' => 'PKR',
                'stock_quantity' => rand(5, 100),
                'low_stock_threshold' => 5,
                'is_featured' => rand(0, 4) === 0,
                'is_new' => rand(0, 3) === 0,
                'status' => 'published',
            ]);

            $images = array_merge(...array_values($this->categoryImages));
            $imageUrl = $images[array_rand($images)];

            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $imageUrl,
                'alt_text' => $product->name,
                'display_order' => 1,
                'is_featured' => true,
            ]);

            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $product->sku.'-V1',
                'name' => 'Standard',
                'price' => $product->regular_price,
                'sale_price' => $product->sale_price,
                'stock_quantity' => $product->stock_quantity,
                'status' => 'active',
            ]);
        }
    }
}
