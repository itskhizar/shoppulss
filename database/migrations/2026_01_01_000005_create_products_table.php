<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 100)->unique();
            $table->string('barcode')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->decimal('regular_price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->string('currency', 3)->default('PKR');
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->decimal('weight', 8, 3)->nullable();
            $table->decimal('dimensions_length', 8, 2)->nullable();
            $table->decimal('dimensions_width', 8, 2)->nullable();
            $table->decimal('dimensions_height', 8, 2)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('canonical_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('sku', 'idx_prod_sku');
            $table->index('slug', 'idx_prod_slug');
            $table->index('status', 'idx_prod_status');
            $table->index('category_id', 'idx_prod_category_id');
            $table->index('brand_id', 'idx_prod_brand_id');
            if (DB::getDriverName() !== 'sqlite') {
                $table->fullText(['name', 'short_description', 'description'], 'ft_search');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
