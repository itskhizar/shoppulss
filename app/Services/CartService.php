<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get or create the active cart for the current user/session.
     */
    public function getCart(): Cart
    {
        if (Auth::check()) {
            $cart = Auth::user()->activeCart;

            if (! $cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'status' => 'active',
                ]);
            }

            // Sync session cart_id
            Session::put('cart_id', $cart->id);

            return $cart->load('items.product.images');
        }

        // Guest cart
        $cartId = Session::get('cart_id');

        if ($cartId) {
            $cart = Cart::active()->whereNull('user_id')->find($cartId);
        }

        if (empty($cart)) {
            $cart = Cart::create([
                'session_id' => Session::getId(),
                'status' => 'active',
            ]);
            Session::put('cart_id', $cart->id);
        }

        // Clean up any orphaned cart items where product was deleted
        $cart->items()->whereDoesntHave('product')->delete();

        return $cart->load('items.product.images');
    }

    /**
     * Add a product to the cart.
     */
    public function addItem(int $productId, int $quantity = 1, ?int $variantId = null): CartItem
    {
        $cart = $this->getCart();
        $product = Product::findOrFail($productId);

        if ($product->status !== 'published') {
            throw new \InvalidArgumentException('This product is not currently available for purchase.');
        }

        if ($product->stock_quantity <= 0) {
            throw new \InvalidArgumentException('This product is currently out of stock.');
        }

        $effectivePrice = (float) $product->effective_price;
        $addQuantity = max(1, min($quantity, $product->stock_quantity));

        // Check existing item
        $existing = $cart->items()
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($existing) {
            $newQty = min($existing->quantity + $addQuantity, $product->stock_quantity);
            $existing->update([
                'quantity' => $newQty,
                'unit_price' => $effectivePrice,
                'total_price' => $effectivePrice * $newQty,
            ]);

            return $existing;
        }

        return $cart->items()->create([
            'product_id' => $product->id,
            'product_variant_id' => $variantId,
            'quantity' => $addQuantity,
            'unit_price' => $effectivePrice,
            'total_price' => $effectivePrice * $addQuantity,
        ]);
    }

    /**
     * Update quantity for a cart item.
     */
    public function updateItem(int $itemId, int $quantity): CartItem
    {
        $cart = $this->getCart();
        $item = $cart->items()->findOrFail($itemId);

        if ($quantity <= 0) {
            $item->delete();

            return $item;
        }

        $maxQty = $item->product->stock_quantity ?? 99;
        $qty = min($quantity, $maxQty);

        $item->update([
            'quantity' => $qty,
            'total_price' => $item->unit_price * $qty,
        ]);

        return $item;
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(int $itemId): void
    {
        $cart = $this->getCart();
        $cart->items()->where('id', $itemId)->delete();
    }

    /**
     * Clear all items from the cart.
     */
    public function clear(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
    }

    /**
     * Get cart totals: subtotal, shipping, total.
     */
    public function getTotals(Cart $cart): array
    {
        $subtotal = (float) $cart->items->sum('total_price');
        $itemCount = (int) $cart->items->sum('quantity');

        if ($itemCount <= 0 || $subtotal <= 0) {
            return [
                'subtotal' => 0.0,
                'shipping' => 0.0,
                'shipping_free' => true,
                'total' => 0.0,
                'item_count' => 0,
            ];
        }

        $shipping = $subtotal >= 2500 ? 0.0 : 199.0;
        $total = $subtotal + $shipping;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'shipping_free' => $shipping === 0.0,
            'total' => $total,
            'item_count' => $itemCount,
        ];
    }

    /**
     * Get count of items in cart (for navbar badge).
     */
    public function getCount(): int
    {
        $cartId = Session::get('cart_id');
        if (! $cartId) {
            return 0;
        }

        return (int) CartItem::whereHas('cart', fn ($q) => $q->where('id', $cartId))
            ->whereHas('product')
            ->sum('quantity');
    }
}
