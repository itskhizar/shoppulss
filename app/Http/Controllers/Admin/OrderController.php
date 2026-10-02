<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * List all orders in admin.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items', 'shippingAddress']);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($paymentStatus = $request->get('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($fromDate = $request->get('from_date')) {
            $query->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate = $request->get('to_date')) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        if ($sort = $request->get('sort')) {
            if ($sort === 'oldest') {
                $query->oldest();
            } elseif ($sort === 'total_high') {
                $query->orderByDesc('total_amount');
            } elseif ($sort === 'total_low') {
                $query->orderBy('total_amount');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $orders = $query->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'packed' => Order::where('status', 'packed')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Show order details.
     */
    public function show(int $id): View
    {
        $order = Order::with([
            'user',
            'items.product.images',
            'shippingAddress',
            'billingAddress',
            'statusHistories.changer',
            'shipments.courier',
            'shipments.events',
            'payments.verifiedBy',
        ])->findOrFail($id);

        $couriers = Courier::active()->get();
        $allowedNextStatuses = Order::TRANSITIONS[$order->status] ?? [];

        return view('admin.orders.show', compact('order', 'allowedNextStatuses', 'couriers'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', Order::STATUSES)],
            'payment_status' => ['nullable', 'string', 'in:pending,paid,failed,refunded'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        $order->status = $newStatus;

        if (! empty($validated['payment_status'])) {
            $order->payment_status = $validated['payment_status'];
        } elseif ($newStatus === 'delivered' && $order->payment_status === 'pending') {
            $order->payment_status = 'paid';
        }

        $order->save();

        if ($oldStatus !== $newStatus) {
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => $oldStatus,
                'to_status' => $newStatus,
                'reason' => $validated['reason'] ?? "Status updated from {$oldStatus} to {$newStatus} by admin",
                'changed_by' => Auth::id(),
                'created_at' => now(),
            ]);
        }

        return back()->with('success', "Order #{$order->order_number} status updated to ".ucfirst($newStatus).'.');
    }
}
