<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    /**
     * List all payments.
     */
    public function index(Request $request): View
    {
        $query = Payment::with(['order.user', 'verifiedBy']);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_reference', 'like', "%{$search}%")
                    ->orWhere('sender_account_or_phone', 'like', "%{$search}%")
                    ->orWhere('bank_name', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($oq) use ($search) {
                        $oq->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($method = $request->get('method')) {
            $query->where('payment_method', $method);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => Payment::count(),
            'pending_verification' => Payment::where('status', 'pending_verification')->count(),
            'paid' => Payment::where('status', 'paid')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
        ];

        // Recent orders for manual payment creation modal
        $recentOrders = Order::select('id', 'order_number', 'customer_name', 'total_amount', 'payment_status', 'payment_method')
            ->latest()
            ->limit(100)
            ->get();

        return view('admin.payments.index', compact('payments', 'statusCounts', 'recentOrders'));
    }

    /**
     * Manually add a payment record.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'string', 'in:cod,bank_transfer,easypaisa,jazzcash,cash,card'],
            'status' => ['required', 'string', 'in:paid,pending,pending_verification,failed'],
            'transaction_reference' => ['nullable', 'string', 'max:150'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'sender_account_or_phone' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $order = Order::findOrFail($validated['order_id']);
        $isPaid = $validated['status'] === 'paid';
        $adminId = Auth::id();

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'] ?: ('MANUAL-'.strtoupper(Str::random(6))),
            'amount' => $validated['amount'],
            'currency' => $order->currency ?? 'PKR',
            'status' => $validated['status'],
            'bank_name' => $validated['bank_name'] ?? null,
            'sender_account_or_phone' => $validated['sender_account_or_phone'] ?? null,
            'paid_at' => $isPaid ? now() : null,
            'verified_by' => $isPaid ? $adminId : null,
            'verified_at' => $isPaid ? now() : null,
            'verification_notes' => $validated['notes'] ?? 'Manually added by Admin',
        ]);

        // Synchronize Order status
        $orderPaymentStatus = $isPaid ? 'paid' : ($validated['status'] === 'pending_verification' ? 'pending' : $validated['status']);
        $orderUpdates = [
            'payment_method' => $validated['payment_method'],
            'payment_status' => $orderPaymentStatus,
        ];
        if ($isPaid && $order->status === 'pending') {
            $orderUpdates['status'] = 'confirmed';
        }
        $order->update($orderUpdates);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => $order->status,
            'to_status' => $orderUpdates['status'] ?? $order->status,
            'reason' => "Manual payment record of Rs. {$payment->amount} added ({$payment->method_label}, Status: {$payment->status_label})",
            'changed_by' => $adminId,
            'created_at' => now(),
        ]);

        AuditLog::record(
            'payment.manually_added',
            $payment,
            "Admin manually recorded payment of Rs. {$payment->amount} for Order #{$order->order_number} ({$payment->payment_method})",
            null,
            $payment->toArray()
        );

        return back()->with('success', "Payment record for Order #{$order->order_number} added successfully!");
    }

    /**
     * Verify pending payment (Bank Transfer, EasyPaisa, JazzCash).
     */
    public function verify(Request $request, int $id): RedirectResponse
    {
        $payment = Payment::with('order')->findOrFail($id);
        $methodLabel = $payment->method_label;
        $notes = $request->input('notes', "Verified {$methodLabel} payment.");
        $adminId = Auth::id();

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'verified_by' => $adminId,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);

        if ($payment->order) {
            $orderUpdates = ['payment_status' => 'paid'];
            if ($payment->order->status === 'pending') {
                $orderUpdates['status'] = 'confirmed';
            }
            $payment->order->update($orderUpdates);

            OrderStatusHistory::create([
                'order_id' => $payment->order->id,
                'from_status' => $payment->order->status,
                'to_status' => $orderUpdates['status'] ?? $payment->order->status,
                'reason' => "Payment verified via {$methodLabel} by admin. ({$notes})",
                'changed_by' => $adminId,
                'created_at' => now(),
            ]);
        }

        AuditLog::record(
            'payment.verified',
            $payment,
            "Payment #{$payment->id} for Order #{$payment->order?->order_number} verified and marked Paid",
            ['status' => 'pending_verification'],
            ['status' => 'paid', 'verified_by' => $adminId, 'notes' => $notes]
        );

        return back()->with('success', "Payment for Order #{$payment->order?->order_number} verified and marked as Paid!");
    }

    /**
     * Reject payment.
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $payment = Payment::with('order')->findOrFail($id);
        $reason = $request->input('reason', 'Transaction reference could not be verified in payment records.');
        $adminId = Auth::id();

        $payment->update([
            'status' => 'failed',
            'verified_by' => $adminId,
            'verified_at' => now(),
            'verification_notes' => 'Payment rejected: '.$reason,
        ]);

        if ($payment->order) {
            $payment->order->update(['payment_status' => 'failed']);

            OrderStatusHistory::create([
                'order_id' => $payment->order->id,
                'from_status' => $payment->order->status,
                'to_status' => $payment->order->status,
                'reason' => "Payment verification rejected: {$reason}",
                'changed_by' => $adminId,
                'created_at' => now(),
            ]);
        }

        AuditLog::record(
            'payment.rejected',
            $payment,
            "Payment #{$payment->id} for Order #{$payment->order?->order_number} rejected: {$reason}",
            ['status' => 'pending_verification'],
            ['status' => 'failed', 'verified_by' => $adminId, 'notes' => $reason]
        );

        return back()->with('success', "Payment for Order #{$payment->order?->order_number} marked as Failed.");
    }

    /**
     * Record Cash on Delivery payment collection.
     */
    public function recordCod(Request $request, int $id): RedirectResponse
    {
        $payment = Payment::with('order')->findOrFail($id);
        $notes = $request->input('notes', 'Cash on Delivery received and confirmed.');
        $adminId = Auth::id();

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'verified_by' => $adminId,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);

        if ($payment->order) {
            $orderUpdates = ['payment_status' => 'paid'];
            if ($payment->order->status === 'pending') {
                $orderUpdates['status'] = 'confirmed';
            }
            $payment->order->update($orderUpdates);

            OrderStatusHistory::create([
                'order_id' => $payment->order->id,
                'from_status' => $payment->order->status,
                'to_status' => $orderUpdates['status'] ?? $payment->order->status,
                'reason' => "COD payment collected and confirmed by admin. ({$notes})",
                'changed_by' => $adminId,
                'created_at' => now(),
            ]);
        }

        AuditLog::record(
            'payment.cod_collected',
            $payment,
            "COD Payment #{$payment->id} for Order #{$payment->order?->order_number} recorded as Paid",
            ['status' => 'pending'],
            ['status' => 'paid', 'verified_by' => $adminId, 'notes' => $notes]
        );

        return back()->with('success', "COD Payment for Order #{$payment->order?->order_number} marked as Paid!");
    }
}
