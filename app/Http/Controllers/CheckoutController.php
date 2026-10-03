<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Setting;
use App\Services\CartService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected PaymentService $paymentService
    ) {}

    /**
     * Show checkout page.
     */
    public function index(): View|RedirectResponse
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $totals = $this->cartService->getTotals($cart);
        $user = Auth::user();
        $savedAddress = $user ? $user->defaultAddress ?? $user->addresses()->latest()->first() : null;

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('checkout.index', compact('cart', 'totals', 'user', 'savedAddress', 'settings'));
    }

    /**
     * Process order placement.
     */
    public function process(Request $request): RedirectResponse
    {
        $cart = $this->cartService->getCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:25'],
            'province' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:100'],
            'area' => ['nullable', 'string', 'max:100'],
            'street_address' => ['required', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'in:cod,bank_transfer,easypaisa,jazzcash'],
            'customer_notes' => ['nullable', 'string', 'max:500'],

            // Bank Transfer fields
            'bank_name' => ['nullable', 'required_if:payment_method,bank_transfer', 'string', 'max:100'],
            'transaction_reference' => ['nullable', 'required_if:payment_method,bank_transfer', 'string', 'max:100'],
            'sender_account_or_phone' => ['nullable', 'string', 'max:100'],

            // EasyPaisa fields
            'easypaisa_mobile_number' => ['nullable', 'required_if:payment_method,easypaisa', 'string', 'max:25'],

            // JazzCash fields
            'jazzcash_mobile_number' => ['nullable', 'required_if:payment_method,jazzcash', 'string', 'max:25'],
            'jazzcash_cnic_last4' => ['nullable', 'string', 'max:4'],
        ]);

        $totals = $this->cartService->getTotals($cart);

        $order = DB::transaction(function () use ($validated, $cart, $totals) {
            $user = Auth::user();

            // Save address (works for authenticated user or guest)
            $address = Address::create([
                'user_id' => $user?->id,
                'type' => 'shipping',
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'province' => $validated['province'],
                'city' => $validated['city'],
                'area' => $validated['area'] ?? null,
                'street_address' => $validated['street_address'],
                'postal_code' => $validated['postal_code'] ?? null,
                'is_default' => $user ? ! $user->addresses()->exists() : false,
            ]);
            $addressId = $address->id;

            // Generate order number SP-YYYYMMDD-XXXX
            do {
                $orderNumber = 'SP-'.date('Ymd').'-'.strtoupper(Str::random(4));
            } while (Order::where('order_number', $orderNumber)->exists());

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user?->id,
                'customer_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'shipping_address_id' => $addressId,
                'billing_address_id' => $addressId,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'subtotal' => $totals['subtotal'],
                'shipping_amount' => $totals['shipping'],
                'total_amount' => $totals['total'],
                'customer_notes' => $validated['customer_notes'] ?? null,
            ]);

            // Create order items and adjust stock
            foreach ($cart->items as $cartItem) {
                $product = $cartItem->product;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_sku' => $product->sku ?? ('SKU-'.$product->id),
                    'product_name' => $product->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'total_price' => $cartItem->total_price,
                    'discount_per_item' => 0,
                    'tax_per_item' => 0,
                ]);

                // Decrement inventory stock safely
                if ($product && $product->stock_quantity >= $cartItem->quantity) {
                    $product->decrement('stock_quantity', $cartItem->quantity);
                }

                if ($cartItem->variant && $cartItem->variant->stock_quantity >= $cartItem->quantity) {
                    $cartItem->variant->decrement('stock_quantity', $cartItem->quantity);
                }
            }

            // Create initial status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => null,
                'to_status' => 'pending',
                'reason' => 'Order placed with payment method: '.strtoupper(str_replace('_', ' ', $validated['payment_method'])),
                'changed_by' => $user?->id,
                'created_at' => now(),
            ]);

            // Delegate to PaymentService to create Payment record and handle gateway
            $this->paymentService->createAndProcessPayment($order, $validated['payment_method'], $validated);

            // Clear cart
            $this->cartService->clear();

            return $order;
        });

        $successMsg = match ($validated['payment_method']) {
            'bank_transfer' => 'Thank you! Your order has been placed. We will verify your bank transfer shortly.',
            'easypaisa' => 'Thank you! Your EasyPaisa transaction has been confirmed.',
            'jazzcash' => 'Thank you! Your JazzCash transaction has been confirmed.',
            default => 'Thank you! Your order has been placed successfully.',
        };

        return redirect()->route('checkout.confirmation', $order->order_number)
            ->with('success', $successMsg);
    }

    /**
     * Show order confirmation page.
     */
    public function confirmation(string $orderNumber): View
    {
        $order = Order::with([
            'items.product.images',
            'statusHistories',
            'shippingAddress',
            'payments',
            'shipments.courier',
            'shipment.events',
        ])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}
