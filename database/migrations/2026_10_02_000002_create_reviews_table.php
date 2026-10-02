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
        if (! Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('customer_name', 100);
                $table->string('customer_email', 150)->nullable();
                $table->unsignedTinyInteger('rating')->default(5);
                $table->string('title', 200)->nullable();
                $table->text('comment');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->boolean('is_verified_purchase')->default(false);
                $table->timestamps();

                $table->index(['product_id', 'status'], 'idx_review_product_status');
                $table->index('status', 'idx_review_status');
                $table->index('created_at', 'idx_review_created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
