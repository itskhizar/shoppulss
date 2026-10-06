<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('admin.payments.index', compact('payments', 'statusCounts'));
    }

    /**
     * Verify manual bank transfer.
     */
    public function verify(Request $request, int $id): RedirectResponse
    {
        $payment = Payment::with('order')->findOrFail($id);

        $notes = $request->input('notes', 'Verified manual bank transfer.');

        $this->paymentService->verifyBankPayment($payment, Auth::id(), $notes);

        AuditLog::record(
            'payment.verified',
            $payment,
            "Payment #{$payment->id} for Order #{$payment->order?->order_number} verified and marked Paid",
            ['status' => 'pending_verification'],
            ['status' => 'paid', 'verified_by' => Auth::id(), 'notes' => $notes]
        );

        return back()->with('success', "Payment for Order #{$payment->order?->order_number} verified and marked as Paid!");
    }

    /**
     * Reject bank payment.
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $payment = Payment::with('order')->findOrFail($id);

        $reason = $request->input('reason', 'Transaction reference could not be verified in bank records.');

        $this->paymentService->rejectBankPayment($payment, Auth::id(), $reason);

        AuditLog::record(
            'payment.rejected',
            $payment,
            "Payment #{$payment->id} for Order #{$payment->order?->order_number} rejected: {$reason}",
            ['status' => 'pending_verification'],
            ['status' => 'failed', 'verified_by' => Auth::id(), 'notes' => $reason]
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

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);

        if ($payment->order) {
            $payment->order->update(['payment_status' => 'paid']);
        }

        AuditLog::record(
            'payment.cod_collected',
            $payment,
            "COD Payment #{$payment->id} for Order #{$payment->order?->order_number} recorded as Paid",
            ['status' => 'pending'],
            ['status' => 'paid', 'verified_by' => Auth::id(), 'notes' => $notes]
        );

        return back()->with('success', "COD Payment for Order #{$payment->order?->order_number} marked as Paid!");
    }
}
