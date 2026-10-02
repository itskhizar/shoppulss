<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        // 1. Create category_attributes pivot table
        if (! Schema::hasTable('category_attributes')) {
            Schema::create('category_attributes', function (Blueprint $table) {
                $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $table->foreignId('attribute_id')->constrained('attributes')->cascadeOnDelete();
                $table->primary(['category_id', 'attribute_id']);
            });
        }

        // 2. Create product_variant_attribute_values pivot table
        if (! Schema::hasTable('product_variant_attribute_values')) {
            Schema::create('product_variant_attribute_values', function (Blueprint $table) {
                $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
                $table->foreignId('attribute_value_id')->constrained('attribute_values')->cascadeOnDelete();
                $table->primary(['product_variant_id', 'attribute_value_id']);
            });
        }

        // 3. Make addresses.user_id nullable (guest checkout support) — MySQL/PostgreSQL only
        if ($driver !== 'sqlite' && Schema::hasColumn('addresses', 'user_id')) {
            Schema::table('addresses', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->unsignedBigInteger('user_id')->nullable()->change();
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
        }

        // 4. Remove brand_id from products — SQLite cannot drop columns with indexes,
        //    so we skip it there (brand_id was never present in fresh SQLite test DBs).
        if ($driver !== 'sqlite' && Schema::hasColumn('products', 'brand_id')) {
            Schema::table('products', function (Blueprint $table) {
                // Drop FK first, then column
                try {
                    $table->dropForeign(['brand_id']);
                } catch (Throwable) {
                    // FK may already be gone
                }
                $table->dropColumn('brand_id');
            });
        }

        // 5. Alter orders status enum — MySQL only
        if ($driver === 'mysql') {
            DB::statement("
                ALTER TABLE orders MODIFY COLUMN status ENUM(
                    'pending','confirmed','processing','packed','shipped','delivered','cancelled'
                ) NOT NULL DEFAULT 'pending'
            ");
        }

        // 6. Add customer_name to orders if missing
        if (! Schema::hasColumn('orders', 'customer_name')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('customer_name')->nullable()->after('user_id');
            });
        }

        // 7. Add payment_method to orders if missing
        if (! Schema::hasColumn('orders', 'payment_method')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('payment_method', 50)->default('cod')->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_attribute_values');
        Schema::dropIfExists('category_attributes');

        $driver = DB::getDriverName();

        if ($driver !== 'sqlite' && Schema::hasColumn('addresses', 'user_id')) {
            Schema::table('addresses', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            });
        }
    }
};
