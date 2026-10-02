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
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'is_deal')) {
                $table->boolean('is_deal')->default(false)->after('is_new');
            }
            if (! Schema::hasColumn('products', 'deal_start_at')) {
                $table->timestamp('deal_start_at')->nullable()->after('is_deal');
            }
            if (! Schema::hasColumn('products', 'deal_end_at')) {
                $table->timestamp('deal_end_at')->nullable()->after('deal_start_at');
            }
            if (! Schema::hasColumn('products', 'is_trending')) {
                $table->boolean('is_trending')->default(false)->after('deal_end_at');
            }

            $table->index(['is_deal', 'deal_end_at'], 'idx_prod_deals');
            $table->index('is_trending', 'idx_prod_trending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_prod_deals');
            $table->dropIndex('idx_prod_trending');
            $table->dropColumn(['is_deal', 'deal_start_at', 'deal_end_at', 'is_trending']);
        });
    }
};
