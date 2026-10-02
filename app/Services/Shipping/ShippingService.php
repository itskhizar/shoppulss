<?php

namespace App\Services\Shipping;

use App\Models\Courier;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Services\Shipping\Adapters\LeopardsCourierAdapter;
use App\Services\Shipping\Adapters\ManualCourierAdapter;
use App\Services\Shipping\Adapters\TcsCourierAdapter;
use App\Services\Shipping\Contracts\CourierAdapterInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShippingService
{
    /**
     * Resolve courier adapter.
     */
    public function getAdapter(string $code): CourierAdapterInterface
    {
        return match (strtolower($code)) {
            'leopards' => app(LeopardsCourierAdapter::class),
            'tcs' => app(TcsCourierAdapter::class),
            default => app(ManualCourierAdapter::class),
        };
    }

    /**
     * Create and book a shipment for an order.
     */
    public function createShipment(Order $order, Courier|int $courier, array $data): Shipment
    {
        $courierModel = is_int($courier) ? Courier::findOrFail($courier) : $courier;
        $adapter = $this->getAdapter($courierModel->code);

        return DB::transaction(function () use ($order, $courierModel, $adapter, $data) {
            $shippingAddress = $order->shippingAddress;
            $weight = (float) ($data['weight'] ?? 0.50);
            $pieces = (int) ($data['pieces'] ?? 1);
            $codAmount = (float) ($data['cod_amount'] ?? ($order->payment_method === 'cod' ? $order->total_amount : 0));

            // Call courier adapter
            $result = $adapter->bookShipment($order, $courierModel, [
                'weight' => $weight,
                'pieces' => $pieces,
                'cod_amount' => $codAmount,
                'tracking_number' => $data['tracking_number'] ?? null,
                'shipping_fee' => $data['shipping_fee'] ?? null,
                'external_id' => $data['external_id'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // Save shipment in database
            $shipment = Shipment::create([
                'order_id' => $order->id,
                'courier_id' => $courierModel->id,
                'tracking_number' => $result['tracking_number'],
                'external_shipment_id' => $result['external_id'] ?? null,
                'shipment_status' => 'booked',
                'weight' => $weight,
                'pieces' => $pieces,
                'shipping_fee' => $result['shipping_fee'] ?? 0.00,
                'cod_amount' => $codAmount,
                'destination_city' => $shippingAddress?->city ?? 'Unknown',
                'consignee_name' => $shippingAddress?->full_name ?? $order->customer_name,
                'consignee_phone' => $shippingAddress?->phone ?? $order->phone,
                'consignee_address' => $shippingAddress?->street_address ?? 'Customer address',
                'notes' => $data['notes'] ?? null,
                'label_url' => $result['label_url'] ?? null,
                'dispatched_at' => null,
            ]);

            // Create initial milestone event
            $shipment->events()->create([
                'status' => 'booked',
                'location' => 'ShopPulss Fulfillment Center',
                'description' => 'Shipment booked via '.$courierModel->name.'. Awaiting courier rider pickup.',
                'event_time' => Carbon::now(),
            ]);

            // Advance order status to 'processing' or 'packed' or 'shipped'
            $oldStatus = $order->status;
            $newStatus = ! empty($data['advance_order_status']) ? $data['advance_order_status'] : 'shipped';

            if ($order->status !== $newStatus) {
                $order->update(['status' => $newStatus]);

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'from_status' => $oldStatus,
                    'to_status' => $newStatus,
                    'reason' => 'Courier shipment created via '.$courierModel->name." (Tracking: {$shipment->tracking_number})",
                    'changed_by' => Auth::id(),
                    'created_at' => now(),
                ]);
            }

            return $shipment;
        });
    }

    /**
     * Update shipment status and log audit event.
     */
    public function updateShipmentStatus(Shipment $shipment, string $status, ?string $location = null, ?string $description = null): void
    {
        DB::transaction(function () use ($shipment, $status, $location, $description) {
            $defaultDescription = match ($status) {
                'booked' => 'Shipment booked and awaiting courier pickup.',
                'picked_up' => 'Parcel collected by courier rider from ShopPulss facility.',
                'in_transit' => 'Parcel arrived at hub and is in transit to destination.',
                'out_for_delivery' => 'Parcel is out for delivery with the local courier rider.',
                'delivered' => 'Package successfully handed over to customer.',
                'failed' => 'Delivery attempt failed (Customer unavailable or reschedule requested).',
                'returned' => 'Parcel returned to origin facility.',
                'cancelled' => 'Shipment booking cancelled.',
                default => 'Shipment updated to '.ucfirst(str_replace('_', ' ', $status)),
            };

            $eventDesc = $description ?: $defaultDescription;
            $eventLocation = $location ?: ($status === 'delivered' ? $shipment->destination_city : 'Courier Network Hub');

            $updates = ['shipment_status' => $status];

            if ($status === 'picked_up' && ! $shipment->dispatched_at) {
                $updates['dispatched_at'] = Carbon::now();
            }

            if ($status === 'delivered') {
                $updates['delivered_at'] = Carbon::now();
            }

            $shipment->update($updates);

            // Create tracking event
            $shipment->events()->create([
                'status' => $status,
                'location' => $eventLocation,
                'description' => $eventDesc,
                'event_time' => Carbon::now(),
            ]);

            // Sync with parent order
            $order = $shipment->order;
            if ($order) {
                if ($status === 'delivered') {
                    $oldStatus = $order->status;
                    $order->status = 'delivered';

                    if ($order->payment_method === 'cod') {
                        $order->payment_status = 'paid';

                        // Mark any pending COD payment as paid
                        $latestPayment = $order->payment;
                        if ($latestPayment && $latestPayment->status !== 'paid') {
                            $latestPayment->update([
                                'status' => 'paid',
                                'paid_at' => Carbon::now(),
                                'verification_notes' => 'Cash on Delivery collected by '.$shipment->courier?->name,
                            ]);
                        }
                    }

                    $order->save();

                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'from_status' => $oldStatus,
                        'to_status' => 'delivered',
                        'reason' => 'Confirmed delivered by courier '.$shipment->courier?->name." (Tracking #{$shipment->tracking_number})",
                        'changed_by' => Auth::id(),
                        'created_at' => now(),
                    ]);
                } elseif ($status === 'picked_up' && $order->status !== 'shipped') {
                    $oldStatus = $order->status;
                    $order->update(['status' => 'shipped']);

                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'from_status' => $oldStatus,
                        'to_status' => 'shipped',
                        'reason' => 'Package handed to courier rider (Dispatched)',
                        'changed_by' => Auth::id(),
                        'created_at' => now(),
                    ]);
                }
            }
        });
    }

    /**
     * Add custom tracking milestone event.
     */
    public function addTrackingEvent(Shipment $shipment, string $status, string $description, ?string $location = null): ShipmentEvent
    {
        return $shipment->events()->create([
            'status' => $status,
            'location' => $location,
            'description' => $description,
            'event_time' => Carbon::now(),
        ]);
    }
}
