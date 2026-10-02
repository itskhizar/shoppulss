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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('email');
            $table->string('phone', 20);
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->string('payment_method', 50)->default('cod');
            $table->unsignedBigInteger('shipping_method_id')->nullable();
            $table->enum('status', [
                'pending', 'confirmed', 'payment_pending', 'paid', 'processing', 'packed',
                'shipped', 'delivered', 'cancelled', 'return_requested',
                'returned', 'refund_pending', 'refunded',
            ])->default('pending');
            $table->enum('payment_status', [
                'pending', 'authorized', 'paid', 'failed', 'partially_refunded', 'refunded',
            ])->default('pending');
            $table->decimal('subtotal', 14, 2);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('shipping_amount', 14, 2);
            $table->decimal('total_amount', 14, 2);
            $table->string('currency', 3)->default('PKR');
            $table->string('coupon_code', 100)->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamps();

            $table->index('order_number', 'idx_order_number');
            $table->index('user_id', 'idx_order_user_id');
            $table->index('status', 'idx_order_status');
            $table->index('payment_status', 'idx_order_payment_status');
            $table->index('created_at', 'idx_order_created_at');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_sku', 100)->nullable();
            $table->string('product_name');
            $table->json('variant_details')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 14, 2);
            $table->decimal('discount_per_item', 12, 2)->default(0);
            $table->decimal('tax_per_item', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('from_status', 100)->nullable();
            $table->string('to_status', 100);
            $table->string('reason')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
