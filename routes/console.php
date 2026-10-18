<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('shop:clean-catalog {--force : Force the operation without confirmation}', function () {
    if (! $this->option('force') && ! $this->confirm('Are you sure you want to PERMANENTLY remove all products, variants, images, cart items, reviews, and categories from the database?')) {
        $this->info('Operation cancelled.');

        return 0;
    }

    $this->info('Resetting ShopPulss catalog (products, variants, images, reviews, categories)...');

    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    $tables = [
        'reviews',
        'cart_items',
        'product_images',
        'product_variant_attribute_values',
        'product_variants',
        'products',
        'category_attributes',
        'categories',
    ];

    foreach ($tables as $table) {
        if (Schema::hasTable($table)) {
            DB::table($table)->truncate();
            $this->line(" ✓ Cleared table: {$table}");
        }
    }

    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $this->info('Catalog successfully wiped! All products and categories have been completely removed.');

    return 0;
})->purpose('Permanently wipe all products and categories from the database');
