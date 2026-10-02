<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Show tracking form or search result.
     */
    public function track(Request $request): View
    {
        $orderNumber = $request->get('order_number');
        $contact = $request->get('contact');
        $order = null;
        $searched = false;

        if ($orderNumber) {
            $searched = true;
            $query = Order::with(['items.product.images', 'statusHistories', 'shipments.courier', 'shipments.events', 'payments', 'shippingAddress'])
                ->where('order_number', trim($orderNumber));

            if ($contact) {
                $query->where(function ($q) use ($contact) {
                    $q->where('email', trim($contact))
                        ->orWhere('phone', 'like', '%'.trim($contact).'%');
                });
            }

            $order = $query->first();
        }

        return view('orders.track', compact('order', 'searched', 'orderNumber', 'contact'));
    }
}
