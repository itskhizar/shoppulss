<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * List registered retail customers only (excluding admin/staff accounts).
     */
    public function index(Request $request): View
    {
        $query = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', [
                'super-admin', 'Super Admin', 'admin', 'Admin',
                'Store Admin', 'Catalog Manager', 'Order Manager', 'Support Agent',
            ]);
        })
            ->withCount('orders')
            ->withSum('orders', 'total_amount');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest(),
            'orders_high' => $query->orderByDesc('orders_count'),
            'spent_high' => $query->orderByDesc('orders_sum_total_amount'),
            default => $query->latest(),
        };

        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show customer profile and their orders.
     */
    public function show(Request $request, int $id): View
    {
        $customer = User::with(['addresses'])
            ->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->findOrFail($id);

        $ordersQuery = $customer->orders()->with('items.product')->latest();

        if ($search = $request->get('q')) {
            $ordersQuery->where('order_number', 'like', "%{$search}%");
        }

        if ($status = $request->get('status')) {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery->paginate(10)->withQueryString();

        return view('admin.customers.show', compact('customer', 'orders'));
    }
}
