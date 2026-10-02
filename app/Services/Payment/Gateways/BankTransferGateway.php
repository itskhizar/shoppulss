<?php

namespace App\Services\Payment\Gateways;

use App\Models\Order;
use App\Services\Payment\Contracts\PaymentGatewayInterface;

class BankTransferGateway implements PaymentGatewayInterface
{
    public function process(Order $order, array $payload): array
    {
        $bankName = trim($payload['bank_name'] ?? 'Direct Bank Transfer');
        $txRef = trim($payload['transaction_reference'] ?? '');
        $sender = trim($payload['sender_account_or_phone'] ?? '');

        return [
            'success' => true,
            'status' => 'pending_verification',
            'transaction_id' => $txRef ?: null,
            'message' => 'Bank transfer details received. Order will be verified by the admin team.',
            'redirect_url' => null,
            'data' => [
                'bank_name' => $bankName,
                'sender_account_or_phone' => $sender,
                'transaction_reference' => $txRef,
            ],
        ];
    }

    public function handleCallback(array $data): array
    {
        return [
            'success' => true,
            'status' => 'pending_verification',
            'message' => 'Bank payment manual verification requested.',
        ];
    }
}
