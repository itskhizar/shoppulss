<?php

namespace App\Services\Shipping\Adapters;

use App\Models\Courier;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\Shipping\Contracts\CourierAdapterInterface;
use Illuminate\Support\Str;

class ManualCourierAdapter implements CourierAdapterInterface
{
    public function bookShipment(Order $order, Courier $courier, array $payload): array
    {
        $prefix = match ($courier->code) {
            'leopards' => 'LEO',
            'tcs' => 'TCS',
            'trax' => 'TRX',
            'mnp' => 'MNP',
            default => 'SP',
        };

        $trackingNumber = ! empty($payload['tracking_number'])
            ? trim($payload['tracking_number'])
            : $prefix.'-'.date('Ymd').'-'.strtoupper(Str::random(6));

        $fee = isset($payload['shipping_fee']) ? (float) $payload['shipping_fee'] : 200.00;

        return [
            'success' => true,
            'tracking_number' => $trackingNumber,
            'external_id' => $payload['external_id'] ?? null,
            'shipping_fee' => $fee,
            'label_url' => null,
            'message' => 'Shipment booked manually via '.$courier->name.' portal.',
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
