<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Color',
                'slug' => 'color',
                'type' => 'color',
                'values' => [
                    ['value' => 'Black', 'hex_color' => '#000000'],
                    ['value' => 'White', 'hex_color' => '#FFFFFF'],
                    ['value' => 'Red', 'hex_color' => '#FF0000'],
                    ['value' => 'Blue', 'hex_color' => '#0000FF'],
                    ['value' => 'Navy', 'hex_color' => '#0F1B4D'],
                    ['value' => 'Green', 'hex_color' => '#008000'],
                    ['value' => 'Grey', 'hex_color' => '#808080'],
                    ['value' => 'Beige', 'hex_color' => '#F5F5DC'],
                    ['value' => 'Pink', 'hex_color' => '#FFC0CB'],
                    ['value' => 'Yellow', 'hex_color' => '#FFFF00'],
                    ['value' => 'Purple', 'hex_color' => '#800080'],
                    ['value' => 'Orange', 'hex_color' => '#FF6B35'],
                ],
            ],
            [
                'name' => 'Size',
                'slug' => 'size',
                'type' => 'select',
                'values' => [
                    ['value' => 'XS'], ['value' => 'S'], ['value' => 'M'],
                    ['value' => 'L'], ['value' => 'XL'], ['value' => 'XXL'], ['value' => 'XXXL'],
                    ['value' => '38'], ['value' => '39'], ['value' => '40'],
                    ['value' => '41'], ['value' => '42'], ['value' => '43'], ['value' => '44'],
                ],
            ],
            [
                'name' => 'Material',
                'slug' => 'material',
                'type' => 'select',
                'values' => [
                    ['value' => 'Cotton'], ['value' => 'Polyester'], ['value' => 'Linen'],
                    ['value' => 'Silk'], ['value' => 'Wool'], ['value' => 'Leather'],
                    ['value' => 'Stainless Steel'], ['value' => 'Aluminium'], ['value' => 'Plastic'],
                ],
            ],
            [
                'name' => 'Warranty',
                'slug' => 'warranty',
                'type' => 'select',
                'values' => [
                    ['value' => 'No Warranty'], ['value' => '3 Months'], ['value' => '6 Months'],
                    ['value' => '1 Year'], ['value' => '2 Years'], ['value' => '3 Years'],
                ],
            ],
            [
                'name' => 'Storage',
                'slug' => 'storage',
                'type' => 'select',
                'values' => [
                    ['value' => '16GB'], ['value' => '32GB'], ['value' => '64GB'],
                    ['value' => '128GB'], ['value' => '256GB'], ['value' => '512GB'], ['value' => '1TB'],
                ],
            ],
            [
                'name' => 'RAM',
                'slug' => 'ram',
                'type' => 'select',
                'values' => [
                    ['value' => '2GB'], ['value' => '4GB'], ['value' => '6GB'],
                    ['value' => '8GB'], ['value' => '12GB'], ['value' => '16GB'], ['value' => '32GB'],
                ],
            ],
            [
                'name' => 'Model',
                'slug' => 'model',
                'type' => 'text',
                'values' => [
                    ['value' => 'iPhone 15 Pro'], ['value' => 'iPhone 15'], ['value' => 'Galaxy S24 Ultra'],
                    ['value' => 'Galaxy S24'], ['value' => 'Redmi Note 13'], ['value' => 'Air Jordan 1'],
                    ['value' => 'Ultraboost 1.0'], ['value' => 'Classic Leather'],
                ],
            ],
            [
                'name' => 'Screen Size',
                'slug' => 'screen-size',
                'type' => 'select',
                'values' => [
                    ['value' => '5.5 inch'], ['value' => '6.1 inch'], ['value' => '6.5 inch'],
                    ['value' => '6.7 inch'], ['value' => '6.8 inch'], ['value' => '13 inch'], ['value' => '15.6 inch'],
                ],
            ],
            [
                'name' => 'Shoe Size',
                'slug' => 'shoe-size',
                'type' => 'select',
                'values' => [
                    ['value' => 'UK 6 (EU 40)'], ['value' => 'UK 7 (EU 41)'], ['value' => 'UK 8 (EU 42)'],
                    ['value' => 'UK 9 (EU 43)'], ['value' => 'UK 10 (EU 44)'], ['value' => 'UK 11 (EU 45)'],
                ],
            ],
            [
                'name' => 'Shoe Type',
                'slug' => 'shoe-type',
                'type' => 'select',
                'values' => [
                    ['value' => 'Sneakers'], ['value' => 'Running Shoes'], ['value' => 'Formal Oxford'],
                    ['value' => 'Loafers'], ['value' => 'Boots'], ['value' => 'Sandals'],
                ],
            ],
            [
                'name' => 'Battery Capacity',
                'slug' => 'battery-capacity',
                'type' => 'select',
                'values' => [
                    ['value' => '3000 mAh'], ['value' => '4000 mAh'], ['value' => '5000 mAh'], ['value' => '6000 mAh'],
                ],
            ],
        ];

        foreach ($attributes as $attrData) {
            $values = $attrData['values'];
            unset($attrData['values']);

            $attribute = Attribute::firstOrCreate(
                ['slug' => $attrData['slug']],
                array_merge($attrData, ['status' => 'active'])
            );

            foreach ($values as $order => $valueData) {
                AttributeValue::firstOrCreate(
                    ['attribute_id' => $attribute->id, 'value' => $valueData['value']],
                    array_merge(['attribute_id' => $attribute->id, 'display_order' => $order], $valueData)
                );
            }
        }
    }
}
