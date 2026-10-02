<?php

namespace App\Services\Payment\Contracts;

use App\Models\Order;

interface PaymentGatewayInterface
{
    /**
     * Process checkout payment.
     *
     * @return array [success => bool, status => string, transaction_id => ?string, message => ?string, redirect_url => ?string, data => array]
     */
    public function process(Order $order, array $payload): array;

    /**
     * Handle incoming gateway callback or webhook.
     *
     * @return array [success => bool, order_number => ?string, transaction_id => ?string, status => string, message => ?string]
     */
    public function handleCallback(array $data): array;
}
