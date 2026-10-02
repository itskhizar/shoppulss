<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Customer dashboard overview.
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        $recentOrders = $user->orders()
            ->with(['items.product.images'])
            ->latest()
            ->limit(5)
            ->get();

        $totalOrders = $user->orders()->count();
        $defaultAddress = $user->defaultAddress ?? $user->addresses()->latest()->first();

        return view('account.dashboard', compact('user', 'recentOrders', 'totalOrders', 'defaultAddress'));
    }

    /**
     * Customer orders list.
     */
    public function orders(): View
    {
        $orders = Auth::user()->orders()
            ->with(['items.product.images'])
            ->latest()
            ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    /**
     * Single order detail for customer.
     */
    public function orderDetail(string $orderNumber): View
    {
        $order = Auth::user()->orders()
            ->with(['items.product.images', 'shippingAddress', 'statusHistories', 'shipments.courier', 'shipments.events', 'payments'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('account.order-detail', compact('order'));
    }

    /**
     * Customer profile view.
     */
    public function profile(): View
    {
        $user = Auth::user()->load('addresses');

        return view('account.profile', compact('user'));
    }

    /**
     * Update customer profile info.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:25'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update customer password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
