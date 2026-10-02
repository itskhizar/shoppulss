<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Latest gadgets, phones, laptops, and electronic accessories',
                'image_url' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=500',
                'display_order' => 1,
                'is_featured' => true,
                'children' => [
                    'Mobile Phones', 'Smartphones & Tablets', 'Laptops & Computers', 'TV & Audio',
                    'Cameras & Photography', 'Gaming', 'Wearable Tech', 'Networking',
                    'Chargers & Cables', 'Accessories',
                ],
            ],
            [
                'name' => 'Fashion & Apparel',
                'slug' => 'fashion-apparel',
                'description' => 'Men, Women and Kids clothing, shoes, and accessories',
                'image_url' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=500',
                'display_order' => 2,
                'is_featured' => true,
                'children' => [
                    "Men's Clothing", "Women's Clothing", 'Shoes', 'Watches',
                    'Bags & Accessories', 'Jewelry',
                ],
            ],
            [
                'name' => 'Home & Living',
                'slug' => 'home-living',
                'description' => 'Everything you need for your home',
                'image_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=500',
                'display_order' => 3,
                'is_featured' => true,
                'children' => [
                    'Kitchen & Dining', 'Bedding & Bath', 'Furniture',
                    'Décor & Lighting', 'Cleaning Supplies', 'Garden & Outdoor',
                ],
            ],
            [
                'name' => 'Beauty & Personal Care',
                'slug' => 'beauty-personal-care',
                'description' => 'Skincare, haircare, makeup, and personal hygiene',
                'image_url' => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=500',
                'display_order' => 4,
                'is_featured' => true,
                'children' => [
                    'Skincare', 'Hair Care', 'Makeup', 'Personal Hygiene', 'Fragrances',
                ],
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'description' => 'Sports gear, fitness equipment, and outdoor essentials',
                'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=500',
                'display_order' => 5,
                'is_featured' => true,
                'children' => [
                    'Fitness Equipment', 'Sports Gear', 'Outdoor Gear', 'Bicycles & Accessories',
                ],
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Phone cases, laptop bags, and everyday carry items',
                'image_url' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?w=500',
                'display_order' => 6,
                'is_featured' => true,
                'children' => ['Phone Cases & Covers', 'Laptop Bags', 'Power Banks', 'Headphones & Earphones'],
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'description' => 'Fun toys and games for all ages',
                'image_url' => 'https://images.unsplash.com/photo-1558060370-d644485927b4?w=500',
                'display_order' => 7,
                'is_featured' => false,
                'children' => [
                    'Action Figures', 'Board Games', 'Puzzles', 'Building Blocks',
                    'Remote Control Toys', 'Educational Toys',
                ],
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'description' => 'Car and bike accessories and maintenance',
                'image_url' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=500',
                'display_order' => 8,
                'is_featured' => false,
                'children' => ['Car Accessories', 'Bike Accessories', 'Maintenance'],
            ],
        ];

        foreach ($categories as $catData) {
            $children = $catData['children'];
            unset($catData['children']);

            $parent = Category::firstOrCreate(
                ['slug' => $catData['slug']],
                array_merge($catData, ['status' => 'active'])
            );

            foreach ($children as $index => $childName) {
                $child = Category::firstOrCreate(
                    ['slug' => $parent->slug.'-'.Str::slug($childName)],
                    [
                        'parent_id' => $parent->id,
                        'name' => $childName,
                        'slug' => $parent->slug.'-'.Str::slug($childName),
                        'description' => "Shop the best {$childName} in Pakistan",
                        'display_order' => $index + 1,
                        'is_featured' => false,
                        'status' => 'active',
                    ]
                );

                // Assign relevant dynamic attributes
                $attrSlugs = match ($childName) {
                    'Mobile Phones', 'Smartphones & Tablets' => ['model', 'color', 'storage', 'ram', 'screen-size', 'battery-capacity', 'warranty'],
                    'Laptops & Computers' => ['model', 'ram', 'storage', 'screen-size', 'warranty'],
                    'Shoes' => ['shoe-size', 'shoe-type', 'color', 'material'],
                    "Men's Clothing", "Women's Clothing" => ['size', 'color', 'material'],
                    'Watches' => ['color', 'material', 'warranty'],
                    default => ['color', 'material'],
                };

                $attrIds = Attribute::whereIn('slug', $attrSlugs)->pluck('id');
                if ($attrIds->isNotEmpty()) {
                    $child->attributes()->syncWithoutDetaching($attrIds);
                }
            }

            // Also attach attributes to parent
            $parentAttrSlugs = match ($parent->slug) {
                'electronics' => ['model', 'storage', 'ram', 'screen-size', 'color', 'warranty'],
                'fashion-apparel' => ['size', 'shoe-size', 'shoe-type', 'color', 'material'],
                default => ['color', 'material'],
            };
            $parentAttrIds = Attribute::whereIn('slug', $parentAttrSlugs)->pluck('id');
            if ($parentAttrIds->isNotEmpty()) {
                $parent->attributes()->syncWithoutDetaching($parentAttrIds);
            }
        }
    }
}
