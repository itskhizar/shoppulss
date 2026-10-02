<?php

namespace App\Services\Payment\Gateways;

use App\Models\Order;
use App\Services\Payment\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class CardGateway implements PaymentGatewayInterface
{
    public function process(Order $order, array $payload): array
    {
        return [
            'success' => true,
            'status' => 'paid',
            'transaction_id' => 'CARD-'.strtoupper(Str::random(10)),
            'message' => 'Online card payment authorized successfully.',
            'redirect_url' => null,
            'data' => [
                'type' => 'card',
                'mode' => 'instant',
            ],
        ];
    }

    public function handleCallback(array $data): array
    {
        return [
            'success' => true,
            'status' => 'paid',
            'message' => 'Card callback handled.',
        ];
    }
}
