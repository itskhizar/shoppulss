<?php

namespace App\Services\Payment\Gateways;

use App\Models\Order;
use App\Services\Payment\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EasyPaisaGateway implements PaymentGatewayInterface
{
    /**
     * Process EasyPaisa payment.
     */
    public function process(Order $order, array $payload): array
    {
        $mobileNumber = trim($payload['easypaisa_mobile_number'] ?? $order->phone);
        $storeId = env('EASYPAISA_STORE_ID');
        $hashKey = env('EASYPAISA_HASH_KEY');
        $isLive = ! empty($storeId) && ! empty($hashKey) && env('EASYPAISA_ENV') === 'production';

        if ($isLive) {
            try {
                // EasyPaisa Direct Merchant API endpoint
                $endpoint = 'https://easypay.easypaisa.com.pk/easypay-service/rest/v4/initiate-ma-transaction';
                $response = Http::timeout(10)->post($endpoint, [
                    'orderId' => $order->order_number,
                    'storeId' => $storeId,
                    'transactionAmount' => number_format($order->total_amount, 2, '.', ''),
                    'mobileAccountNo' => $mobileNumber,
                    'emailAddress' => $order->email,
                ]);

                if ($response->successful()) {
                    $resData = $response->json();
                    if (($resData['responseCode'] ?? null) === '0000') {
                        return [
                            'success' => true,
                            'status' => 'paid',
                            'transaction_id' => $resData['transactionId'] ?? ('EP-'.strtoupper(Str::random(10))),
                            'message' => 'EasyPaisa payment approved successfully.',
                            'redirect_url' => null,
                            'data' => $resData,
                        ];
                    }
                }
                Log::warning('EasyPaisa API response error', ['response' => $response->body()]);
            } catch (\Throwable $e) {
                Log::error('EasyPaisa gateway connection error: '.$e->getMessage());
            }
        }

        // Manual verification mode for EasyPaisa
        $userTxId = trim($payload['easypaisa_transaction_id'] ?? $payload['transaction_reference'] ?? '');
        $txId = $userTxId ?: ('EP'.date('md').rand(100000, 999999));

        return [
            'success' => true,
            'status' => 'pending_verification',
            'transaction_id' => $txId,
            'message' => 'EasyPaisa details received. Order awaiting admin payment verification.',
            'redirect_url' => null,
            'data' => [
                'provider' => 'easypaisa',
                'account' => $mobileNumber,
                'transaction_reference' => $userTxId,
                'mode' => 'manual_verification',
            ],
        ];
    }

    /**
     * Handle incoming EasyPaisa IPN callback.
     */
    public function handleCallback(array $data): array
    {
        $status = ($data['status'] ?? '') === '0000' || ($data['desc'] ?? '') === 'SUCCESS' ? 'paid' : 'failed';

        return [
            'success' => $status === 'paid',
            'order_number' => $data['orderRefNum'] ?? $data['orderId'] ?? null,
            'transaction_id' => $data['transactionId'] ?? null,
            'status' => $status,
            'message' => $data['message'] ?? 'EasyPaisa callback received.',
        ];
    }
}
