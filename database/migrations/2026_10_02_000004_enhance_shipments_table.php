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
        Schema::table('shipments', function (Blueprint $table) {
            if (! Schema::hasColumn('shipments', 'booking_reference')) {
                $table->string('booking_reference', 100)->nullable()->after('external_shipment_id');
            }
            if (! Schema::hasColumn('shipments', 'packaging_type')) {
                $table->string('packaging_type', 50)->default('flyer')->after('pieces');
            }
            if (! Schema::hasColumn('shipments', 'dimensions')) {
                $table->string('dimensions', 100)->nullable()->after('packaging_type');
            }
            if (! Schema::hasColumn('shipments', 'advance_amount')) {
                $table->decimal('advance_amount', 14, 2)->default(0.00)->after('cod_amount');
            }
            if (! Schema::hasColumn('shipments', 'declared_value')) {
                $table->decimal('declared_value', 14, 2)->nullable()->after('advance_amount');
            }
            if (! Schema::hasColumn('shipments', 'pickup_date')) {
                $table->date('pickup_date')->nullable()->after('label_url');
            }
            if (! Schema::hasColumn('shipments', 'expected_delivery_date')) {
                $table->date('expected_delivery_date')->nullable()->after('pickup_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn([
                'booking_reference',
                'packaging_type',
                'dimensions',
                'advance_amount',
                'declared_value',
                'pickup_date',
                'expected_delivery_date',
            ]);
        });
    }
};
