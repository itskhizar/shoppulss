<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku', 100)->unique();
            $table->string('name')->nullable();
            $table->string('option_1')->nullable();
            $table->string('option_1_value')->nullable();
            $table->string('option_2')->nullable();
            $table->string('option_2_value')->nullable();
            $table->string('option_3')->nullable();
            $table->string('option_3_value')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->decimal('weight', 8, 3)->nullable();
            $table->string('image_url')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('product_id', 'idx_product_id');
            $table->index('sku', 'idx_variant_sku');
            $table->unique(['product_id', 'option_1_value', 'option_2_value', 'option_3_value'], 'uq_variant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
