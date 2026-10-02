<?php

namespace App\Services\Payment\Gateways;

use App\Models\Order;
use App\Services\Payment\Contracts\PaymentGatewayInterface;

class CashOnDeliveryGateway implements PaymentGatewayInterface
{
    public function process(Order $order, array $payload): array
    {
        return [
            'success' => true,
            'status' => 'pending',
            'transaction_id' => null,
            'message' => 'Payable in cash upon package delivery by courier.',
            'redirect_url' => null,
            'data' => [
                'type' => 'cod',
            ],
        ];
    }

    public function handleCallback(array $data): array
    {
        return [
            'success' => true,
            'status' => 'pending',
            'message' => 'Cash on delivery handled.',
        ];
    }
}
