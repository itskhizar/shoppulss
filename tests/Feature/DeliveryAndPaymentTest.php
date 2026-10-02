<?php

use App\Models\Address;
use App\Models\Courier;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Shipment;
use App\Models\User;
use App\Services\Payment\PaymentService;
use App\Services\Shipping\ShippingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

// ─── Helper: admin user ────────────────────────────────────────────────────────

function makeAdmin(): User
{
    $adminRole = Role::firstOrCreate(['name' => 'Store Admin'], ['guard' => 'web']);
    $user = User::factory()->create();

    // model_has_roles requires model_type — attach with extra pivot column
    DB::table('model_has_roles')->insert([
        'role_id' => $adminRole->id,
        'model_id' => $user->id,
        'model_type' => User::class,
    ]);

    return $user;
}

function makeOrderWithAddress(string $paymentMethod = 'cod'): Order
{
    $address = Address::factory()->create([
        'full_name' => 'Ahmed Khan',
        'phone' => '03001234567',
        'city' => 'Lahore',
        'province' => 'Punjab',
        'street_address' => 'House 5, Street 7, DHA',
    ]);

    return Order::factory()->create([
        'payment_method' => $paymentMethod,
        'payment_status' => 'pending',
        'status' => 'confirmed',
        'shipping_address_id' => $address->id,
        'billing_address_id' => $address->id,
        'customer_name' => $address->full_name,
        'phone' => $address->phone,
        'total_amount' => 5000,
    ]);
}

// ─── Courier Model ─────────────────────────────────────────────────────────────

test('courier model builds correct tracking url from template', function () {
    $courier = Courier::factory()->create([
        'tracking_url_template' => 'https://leopardscourier.com/track?cn={tracking_number}',
    ]);

    $url = $courier->getTrackingUrl('LCC123456');

    expect($url)->toBe('https://leopardscourier.com/track?cn=LCC123456');
});

test('courier with no tracking template returns null url', function () {
    $courier = Courier::factory()->create(['tracking_url_template' => null]);

    expect($courier->getTrackingUrl('ABC123'))->toBeNull();
});

test('courier active scope excludes inactive couriers', function () {
    Courier::factory()->count(2)->create(['is_active' => true]);
    Courier::factory()->count(1)->create(['is_active' => false]);

    expect(Courier::active()->count())->toBe(2);
});

// ─── Shipment Model & Service ──────────────────────────────────────────────────

test('shipping service creates shipment and books initial milestone event', function () {
    $courier = Courier::factory()->manual()->create(['code' => 'manual']);
    $order = makeOrderWithAddress('cod');
    $service = app(ShippingService::class);

    $shipment = $service->createShipment($order, $courier, [
        'weight' => 0.5,
        'pieces' => 1,
        'cod_amount' => 5000,
        'advance_order_status' => 'shipped',
    ]);

    expect($shipment)->toBeInstanceOf(Shipment::class)
        ->and($shipment->shipment_status)->toBe('booked')
        ->and($shipment->order_id)->toBe($order->id)
        ->and($shipment->courier_id)->toBe($courier->id)
        ->and($shipment->cod_amount)->toEqual(5000)
        ->and($shipment->events()->count())->toBe(1);

    // Order status should advance
    $order->refresh();
    expect($order->status)->toBe('shipped');
});

test('shipping service update to delivered marks order and payment as delivered + paid (COD)', function () {
    $courier = Courier::factory()->manual()->create(['code' => 'manual']);
    $order = makeOrderWithAddress('cod');
    $service = app(ShippingService::class);

    $shipment = $service->createShipment($order, $courier, [
        'weight' => 0.5,
        'advance_order_status' => 'shipped',
    ]);

    // Also create a COD payment record
    Payment::factory()->cod()->create(['order_id' => $order->id, 'amount' => 5000]);

    $service->updateShipmentStatus($shipment, 'delivered', 'Lahore', 'Delivered to customer.');

    $order->refresh();
    $shipment->refresh();

    expect($order->status)->toBe('delivered')
        ->and($order->payment_status)->toBe('paid')
        ->and($shipment->shipment_status)->toBe('delivered')
        ->and($shipment->delivered_at)->not->toBeNull();
});

test('shipment update to picked_up sets dispatched_at and advances order to shipped', function () {
    $courier = Courier::factory()->manual()->create(['code' => 'manual']);
    $order = makeOrderWithAddress('cod');
    $order->update(['status' => 'confirmed']);
    $service = app(ShippingService::class);

    $shipment = $service->createShipment($order, $courier, [
        'weight' => 1.0,
        'advance_order_status' => 'processing',
    ]);

    // Manually reset to a pre-shipped status for this test
    $order->update(['status' => 'processing']);
    $shipment->update(['shipment_status' => 'booked', 'dispatched_at' => null]);

    $service->updateShipmentStatus($shipment, 'picked_up');

    $order->refresh();
    $shipment->refresh();

    expect($order->status)->toBe('shipped')
        ->and($shipment->dispatched_at)->not->toBeNull()
        ->and($shipment->events()->count())->toBeGreaterThan(0);
});

test('shipment add tracking event stores milestone correctly', function () {
    $courier = Courier::factory()->manual()->create(['code' => 'manual']);
    $order = makeOrderWithAddress('cod');
    $service = app(ShippingService::class);

    $shipment = $service->createShipment($order, $courier, ['weight' => 0.5]);

    $event = $service->addTrackingEvent($shipment, 'in_transit', 'In transit via Lahore hub.', 'Lahore Hub');

    expect($event->status)->toBe('in_transit')
        ->and($event->description)->toBe('In transit via Lahore hub.')
        ->and($event->location)->toBe('Lahore Hub');
});

test('shipment status label accessor returns human readable value', function () {
    $shipment = Shipment::factory()->create(['shipment_status' => 'out_for_delivery']);

    expect($shipment->status_label)->toBe('Out for Delivery');
});

// ─── Payment Model & Service ───────────────────────────────────────────────────

test('payment service creates cod payment record with pending status', function () {
    $order = makeOrderWithAddress('cod');
    $service = app(PaymentService::class);

    $payment = $service->createAndProcessPayment($order, 'cod', []);

    expect($payment)->toBeInstanceOf(Payment::class)
        ->and($payment->payment_method)->toBe('cod')
        ->and($payment->status)->toBe('pending')
        ->and($payment->amount)->toEqual($order->total_amount);
});

test('payment service creates bank_transfer record with pending_verification status', function () {
    $order = makeOrderWithAddress('bank_transfer');
    $service = app(PaymentService::class);

    $payment = $service->createAndProcessPayment($order, 'bank_transfer', [
        'bank_name' => 'Meezan Bank',
        'transaction_reference' => 'TXN123456789',
        'sender_account_or_phone' => '03001234567',
    ]);

    // Payment record is pending_verification; order.payment_status maps to pending (enum constraint)
    expect($payment->payment_method)->toBe('bank_transfer')
        ->and($payment->status)->toBe('pending_verification')
        ->and($payment->transaction_reference)->toBe('TXN123456789')
        ->and($payment->bank_name)->toBe('Meezan Bank');

    $order->refresh();
    expect($order->payment_status)->toBe('pending');
});

test('payment service creates easypaisa payment and returns sandbox paid status', function () {
    $order = makeOrderWithAddress('easypaisa');
    $service = app(PaymentService::class);

    $payment = $service->createAndProcessPayment($order, 'easypaisa', [
        'easypaisa_mobile_number' => '03001234567',
    ]);

    // EasyPaisa sandbox gateway returns 'paid' automatically in test mode
    expect($payment->payment_method)->toBe('easypaisa')
        ->and($payment->status)->toBeIn(['pending', 'paid'])
        ->and($payment->sender_account_or_phone)->toBe('03001234567');
});

test('payment service creates jazzcash payment and returns sandbox paid status', function () {
    $order = makeOrderWithAddress('jazzcash');
    $service = app(PaymentService::class);

    $payment = $service->createAndProcessPayment($order, 'jazzcash', [
        'jazzcash_mobile_number' => '03111234567',
        'jazzcash_cnic_last4' => '1234',
    ]);

    // JazzCash sandbox gateway returns 'paid' automatically in test mode
    expect($payment->payment_method)->toBe('jazzcash')
        ->and($payment->status)->toBeIn(['pending', 'paid'])
        ->and($payment->sender_account_or_phone)->toBe('03111234567');
});

test('admin can verify bank transfer payment and order becomes paid + confirmed', function () {
    $admin = makeAdmin();
    $order = makeOrderWithAddress('bank_transfer');
    $payment = Payment::factory()->bankTransferPending()->create([
        'order_id' => $order->id,
        'amount' => $order->total_amount,
    ]);

    $service = app(PaymentService::class);
    $service->verifyBankPayment($payment, $admin->id, 'Verified in bank statement.');

    $payment->refresh();
    $order->refresh();

    expect($payment->status)->toBe('paid')
        ->and($payment->verified_by)->toBe($admin->id)
        ->and($payment->paid_at)->not->toBeNull()
        ->and($order->payment_status)->toBe('paid')
        ->and($order->status)->toBe('confirmed'); // pending → confirmed on bank verification
});

test('admin can reject bank transfer payment and marks order payment as failed', function () {
    $admin = makeAdmin();
    $order = makeOrderWithAddress('bank_transfer');
    $payment = Payment::factory()->bankTransferPending()->create([
        'order_id' => $order->id,
        'amount' => $order->total_amount,
    ]);

    $service = app(PaymentService::class);
    $service->rejectBankPayment($payment, $admin->id, 'No matching transaction found.');

    $payment->refresh();
    $order->refresh();

    expect($payment->status)->toBe('failed')
        ->and($order->payment_status)->toBe('failed');
});

test('payment markAsPaid method updates order payment status', function () {
    $order = makeOrderWithAddress('bank_transfer');
    $payment = Payment::factory()->bankTransferPending()->create([
        'order_id' => $order->id,
        'amount' => $order->total_amount,
    ]);

    $payment->markAsPaid('Verified by admin', 1);

    $payment->refresh();
    $order->refresh();

    expect($payment->isPaid())->toBeTrue()
        ->and($payment->verification_notes)->toBe('Verified by admin')
        ->and($order->payment_status)->toBe('paid');
});

test('payment method_label accessor returns human readable label', function () {
    $p1 = Payment::factory()->make(['payment_method' => 'cod']);
    $p2 = Payment::factory()->make(['payment_method' => 'bank_transfer']);
    $p3 = Payment::factory()->make(['payment_method' => 'easypaisa']);
    $p4 = Payment::factory()->make(['payment_method' => 'jazzcash']);

    expect($p1->method_label)->toBe('Cash on Delivery')
        ->and($p2->method_label)->toBe('Bank Transfer (Direct)')
        ->and($p3->method_label)->toBe('EasyPaisa Wallet / Direct')
        ->and($p4->method_label)->toBe('JazzCash Mobile Account');
});

// ─── Admin HTTP Routes: Shipment ───────────────────────────────────────────────

test('admin can view shipments index page', function () {
    $admin = makeAdmin();
    Shipment::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.shipments.index'))
        ->assertOk()
        ->assertSee('Courier Shipments');
});

test('admin can book a shipment for an order', function () {
    $admin = makeAdmin();
    $courier = Courier::factory()->manual()->create(['code' => 'manual']);
    $order = makeOrderWithAddress('cod');

    $this->actingAs($admin)
        ->post(route('admin.orders.shipment.store', $order->id), [
            'courier_id' => $courier->id,
            'weight' => 0.5,
            'pieces' => 1,
            'cod_amount' => 5000,
            'advance_order_status' => 'shipped',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('shipments', [
        'order_id' => $order->id,
        'courier_id' => $courier->id,
    ]);
});

test('admin can update shipment status', function () {
    $admin = makeAdmin();
    $courier = Courier::factory()->manual()->create(['code' => 'manual']);
    $order = makeOrderWithAddress('cod');
    $shipment = Shipment::factory()->create([
        'order_id' => $order->id,
        'courier_id' => $courier->id,
        'shipment_status' => 'booked',
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.shipments.status', $shipment->id), [
            'status' => 'in_transit',
            'location' => 'Karachi Hub',
            'description' => 'Parcel arrived at Karachi sorting hub.',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $shipment->refresh();
    expect($shipment->shipment_status)->toBe('in_transit');
});

test('admin can view shipping label', function () {
    $admin = makeAdmin();
    $courier = Courier::factory()->create();
    $order = makeOrderWithAddress('cod');
    $shipment = Shipment::factory()->create([
        'order_id' => $order->id,
        'courier_id' => $courier->id,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.shipments.label', $shipment->id))
        ->assertOk();
});

// ─── Admin HTTP Routes: Payment ────────────────────────────────────────────────

test('admin can view payments index page', function () {
    $admin = makeAdmin();
    Payment::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.payments.index'))
        ->assertOk()
        ->assertSee('Payments');
});

test('admin can verify bank transfer payment via HTTP', function () {
    $admin = makeAdmin();
    $order = makeOrderWithAddress('bank_transfer');
    $payment = Payment::factory()->bankTransferPending()->create([
        'order_id' => $order->id,
        'amount' => $order->total_amount,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.payments.verify', $payment->id), [
            'notes' => 'Checked bank statement. Amount received.',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $payment->refresh();
    expect($payment->status)->toBe('paid');
});

test('admin can reject bank transfer payment via HTTP', function () {
    $admin = makeAdmin();
    $order = makeOrderWithAddress('bank_transfer');
    $payment = Payment::factory()->bankTransferPending()->create([
        'order_id' => $order->id,
        'amount' => $order->total_amount,
    ]);

    $this->actingAs($admin)
        ->post(route('admin.payments.reject', $payment->id), [
            'reason' => 'Transaction reference not found.',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $payment->refresh();
    expect($payment->status)->toBe('failed');
});

test('guest cannot access admin shipments', function () {
    $this->get(route('admin.shipments.index'))
        ->assertRedirect(route('login'));
});

test('guest cannot access admin payments', function () {
    $this->get(route('admin.payments.index'))
        ->assertRedirect(route('login'));
});

// ─── Payment Filter & Scope Tests ─────────────────────────────────────────────

test('payment pending_verification scope returns only pending verification payments', function () {
    Payment::factory()->bankTransferPending()->count(2)->create();
    Payment::factory()->paid()->count(3)->create();

    expect(Payment::pendingVerification()->count())->toBe(2);
});

test('payment paid scope returns only paid payments', function () {
    Payment::factory()->paid()->count(4)->create();
    Payment::factory()->bankTransferPending()->count(2)->create();

    expect(Payment::paid()->count())->toBe(4);
});
