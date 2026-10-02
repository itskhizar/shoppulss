<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    /**
     * Show the cart page.
     */
    public function index(): View
    {
        $cart = $this->cartService->getCart();
        $totals = $this->cartService->getTotals($cart);

        return view('cart.index', compact('cart', 'totals'));
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);

        $item = $this->cartService->addItem(
            $validated['product_id'],
            $validated['quantity'] ?? 1,
            $validated['variant_id'] ?? null
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart!',
                'cart_count' => $this->cartService->getCount(),
            ]);
        }

        return back()->with('success', 'Item added to your cart!');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, int $itemId): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $this->cartService->updateItem($itemId, $validated['quantity']);

        if ($request->wantsJson()) {
            $cart = $this->cartService->getCart();
            $totals = $this->cartService->getTotals($cart);

            return response()->json([
                'success' => true,
                'totals' => $totals,
            ]);
        }

        return back()->with('success', 'Cart updated.');
    }

    /**
     * Remove item from cart.
     */
    public function remove(Request $request, int $itemId): RedirectResponse|JsonResponse
    {
        $this->cartService->removeItem($itemId);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => $this->cartService->getCount(),
            ]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * Clear entire cart.
     */
    public function clear(): RedirectResponse
    {
        $this->cartService->clear();

        return back()->with('success', 'Cart cleared.');
    }
}
