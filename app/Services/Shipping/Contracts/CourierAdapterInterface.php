<?php

namespace App\Services\Shipping\Contracts;

use App\Models\Courier;
use App\Models\Order;
use App\Models\Shipment;

interface CourierAdapterInterface
{
    /**
     * Book/create a shipment through courier (API or Manual booking).
     *
     * @param  array  $payload  [weight, pieces, cod_amount, notes, tracking_number]
     * @return array [success => bool, tracking_number => string, external_id => ?string, shipping_fee => float, label_url => ?string, message => ?string]
     */
    public function bookShipment(Order $order, Courier $courier, array $payload): array;

    /**
     * Query live tracking events from courier.
     *
     * @return array [current_status => string, events => array]
     */
    public function trackShipment(Shipment $shipment): array;

    /**
     * Cancel shipment with courier.
     */
    public function cancelShipment(Shipment $shipment): bool;
}
