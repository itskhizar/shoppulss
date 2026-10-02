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
        // 1. Couriers table
        if (! Schema::hasTable('couriers')) {
            Schema::create('couriers', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('code', 50)->unique();
                $table->string('tracking_url_template')->nullable();
                $table->string('contact_phone', 50)->nullable();
                $table->string('contact_email', 100)->nullable();
                $table->boolean('is_active')->default(true);
                $table->json('api_settings')->nullable();
                $table->timestamps();
            });
        }

        // 2. Shipments table
        if (! Schema::hasTable('shipments')) {
            Schema::create('shipments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('courier_id')->nullable()->constrained('couriers')->nullOnDelete();
                $table->string('tracking_number', 100)->unique();
                $table->string('external_shipment_id', 100)->nullable();
                $table->string('shipment_status', 50)->default('booked');
                $table->decimal('weight', 8, 2)->default(0.50);
                $table->unsignedInteger('pieces')->default(1);
                $table->decimal('shipping_fee', 12, 2)->default(0.00);
                $table->decimal('cod_amount', 14, 2)->default(0.00);
                $table->string('destination_city', 100)->nullable();
                $table->string('consignee_name', 150)->nullable();
                $table->string('consignee_phone', 50)->nullable();
                $table->text('consignee_address')->nullable();
                $table->text('notes')->nullable();
                $table->string('label_url')->nullable();
                $table->timestamp('dispatched_at')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamps();

                $table->index('order_id', 'idx_shipment_order');
                $table->index('courier_id', 'idx_shipment_courier');
                $table->index('shipment_status', 'idx_shipment_status');
            });
        }

        // 3. Shipment events table (audit trail & milestone logs)
        if (! Schema::hasTable('shipment_events')) {
            Schema::create('shipment_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('shipment_id')->constrained('shipments')->cascadeOnDelete();
                $table->string('status', 50);
                $table->string('location', 150)->nullable();
                $table->text('description');
                $table->timestamp('event_time')->useCurrent();
                $table->timestamp('created_at')->useCurrent();

                $table->index(['shipment_id', 'status'], 'idx_shipment_event_status');
            });
        }

        // 4. Payments table
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->string('payment_method', 50);
                $table->string('transaction_reference', 150)->nullable();
                $table->decimal('amount', 14, 2);
                $table->string('currency', 3)->default('PKR');
                $table->string('status', 50)->default('pending');
                $table->string('bank_name', 100)->nullable();
                $table->string('sender_account_or_phone', 100)->nullable();
                $table->string('receipt_path')->nullable();
                $table->json('gateway_response')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('verified_at')->nullable();
                $table->text('verification_notes')->nullable();
                $table->timestamps();

                $table->index('order_id', 'idx_payment_order');
                $table->index('payment_method', 'idx_payment_method');
                $table->index('status', 'idx_payment_status');
                $table->index('transaction_reference', 'idx_payment_tx_ref');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('shipment_events');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('couriers');
    }
};
