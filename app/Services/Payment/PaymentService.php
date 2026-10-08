<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\Gateways\BankTransferGateway;
use App\Services\Payment\Gateways\CardGateway;
use App\Services\Payment\Gateways\CashOnDeliveryGateway;
use App\Services\Payment\Gateways\EasyPaisaGateway;
use App\Services\Payment\Gateways\JazzCashGateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Resolve appropriate payment gateway.
     */
    public function getGateway(string $method): PaymentGatewayInterface
    {
        return match (strtolower($method)) {
            'bank_transfer' => app(BankTransferGateway::class),
            'easypaisa' => app(EasyPaisaGateway::class),
            'jazzcash' => app(JazzCashGateway::class),
            'card' => app(CardGateway::class),
            default => app(CashOnDeliveryGateway::class),
        };
    }

    /**
     * Process checkout payment and create Payment record.
     */
    public function createAndProcessPayment(Order $order, string $method, array $payload): Payment
    {
        $gateway = $this->getGateway($method);

        return DB::transaction(function () use ($order, $method, $gateway, $payload) {
            $result = $gateway->process($order, $payload);

            $status = $result['status'] ?? 'pending';
            $txId = $result['transaction_id'] ?? null;
            $paidAt = $status === 'paid' ? Carbon::now() : null;

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $method,
                'transaction_reference' => $txId,
                'amount' => $order->total_amount,
                'currency' => $order->currency ?? 'PKR',
                'status' => $status,
                'bank_name' => $payload['bank_name'] ?? null,
                'sender_account_or_phone' => $payload['sender_account_or_phone']
                    ?? $payload['easypaisa_mobile_number']
                    ?? $payload['jazzcash_mobile_number']
                    ?? null,
                'receipt_path' => $payload['receipt_path'] ?? null,
                'gateway_response' => $result['data'] ?? null,
                'paid_at' => $paidAt,
                'verification_notes' => $result['message'] ?? null,
            ]);

            // Update order payment status (map pending_verification → pending for order enum)
            $orderPaymentStatus = $status === 'pending_verification' ? 'pending' : $status;
            $order->update([
                'payment_method' => $method,
                'payment_status' => $orderPaymentStatus,
            ]);

            if ($status === 'paid') {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'from_status' => $order->status,
                    'to_status' => $order->status,
                    'reason' => 'Payment confirmed via '.$payment->method_label.($txId ? " (Ref: {$txId})" : ''),
                    'changed_by' => $order->user_id,
                    'created_at' => now(),
                ]);
            } elseif ($status === 'pending_verification') {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'from_status' => $order->status,
                    'to_status' => $order->status,
                    'reason' => $payment->method_label.' details submitted with Ref: '.($txId ?: 'Awaiting confirmation').'. Pending admin verification.',
                    'changed_by' => $order->user_id,
                    'created_at' => now(),
                ]);
            }

            return $payment;
        });
    }

    /**
     * Admin verifies manual bank transfer.
     */
    public function verifyBankPayment(Payment $payment, int $adminId, ?string $notes = null): void
    {
        DB::transaction(function () use ($payment, $adminId, $notes) {
            $payment->markAsPaid($notes, $adminId);

            $order = $payment->order;
            if ($order) {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'from_status' => $order->status,
                    'to_status' => $order->status === 'pending' ? 'confirmed' : $order->status,
                    'reason' => 'Bank transfer verified by admin. '.($notes ?: 'Payment marked as Paid.'),
                    'changed_by' => $adminId,
                    'created_at' => now(),
                ]);

                if ($order->status === 'pending') {
                    $order->update(['status' => 'confirmed']);
                }
            }
        });
    }

    /**
     * Admin rejects bank transfer.
     */
    public function rejectBankPayment(Payment $payment, int $adminId, ?string $reason = null): void
    {
        DB::transaction(function () use ($payment, $adminId, $reason) {
            $payment->update([
                'status' => 'failed',
                'verified_by' => $adminId,
                'verified_at' => Carbon::now(),
                'verification_notes' => 'Payment rejected: '.($reason ?: 'Invalid bank transaction reference or funds not received.'),
            ]);

            $order = $payment->order;
            if ($order) {
                $order->update(['payment_status' => 'failed']);

                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'from_status' => $order->status,
                    'to_status' => $order->status,
                    'reason' => 'Payment verification rejected: '.($reason ?: 'Funds not credited.'),
                    'changed_by' => $adminId,
                    'created_at' => now(),
                ]);
            }
        });
    }
}
