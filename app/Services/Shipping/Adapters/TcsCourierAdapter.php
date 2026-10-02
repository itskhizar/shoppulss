<?php

namespace App\Services\Shipping\Adapters;

use App\Models\Courier;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\Shipping\Contracts\CourierAdapterInterface;
use Illuminate\Support\Str;

class TcsCourierAdapter implements CourierAdapterInterface
{
    public function bookShipment(Order $order, Courier $courier, array $payload): array
    {
        if (! empty($payload['tracking_number'])) {
            return [
                'success' => true,
                'tracking_number' => trim($payload['tracking_number']),
                'external_id' => $payload['external_id'] ?? null,
                'shipping_fee' => (float) ($payload['shipping_fee'] ?? 250.00),
                'label_url' => null,
                'message' => 'Booked with TCS tracking number.',
            ];
        }

        // Generate standard TCS consignment format (e.g. 770000000000)
        $tcsCn = '77'.date('md').rand(100000, 999999);

        return [
            'success' => true,
            'tracking_number' => $tcsCn,
            'external_id' => 'TCS-'.strtoupper(Str::random(8)),
            'shipping_fee' => 250.00,
            'label_url' => null,
            'message' => 'Booked with TCS Express.',
        ];
    }

    public function trackShipment(Shipment $shipment): array
    {
        return [
            'current_status' => $shipment->shipment_status,
            'events' => $shipment->events()->get()->toArray(),
        ];
    }

    public function cancelShipment(Shipment $shipment): bool
    {
        return true;
    }
}
