@extends('layouts.app')

@section('title', 'Shopping Cart - ShopPulss')

@section('content')
<div class="bg-gray-50 py-6 border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        <h1 class="text-2xl md:text-3xl font-black text-gray-900" style="color: #0F1B4D;">
            Your Shopping Cart
        </h1>
        <p class="text-xs text-gray-500 mt-1">Review your selected items before proceeding to checkout</p>
    </div>
</div>

<div class="max-w-screen-xl mx-auto px-4 py-8">
    @if($cart->items->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm max-w-lg mx-auto">
            <div class="w-20 h-20 rounded-full bg-teal-50 flex items-center justify-center mx-auto mb-4 text-teal-600">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900 mb-2">Your cart is currently empty</h2>
            <p class="text-xs text-gray-500 mb-6">Looks like you haven't added any products to your cart yet. Discover hundreds of direct authentic essentials today!</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg text-white font-bold text-sm shadow-md transition-all hover:opacity-95" style="background-color: #00A8B8;">
                Start Shopping Now
            </a>
        </div>
    @else
        {{-- Free Shipping Banner / Progress --}}
        @php
            $freeShippingThreshold = 2500;
            $progress = min(100, round(($totals['subtotal'] / $freeShippingThreshold) * 100));
            $difference = max(0, $freeShippingThreshold - $totals['subtotal']);
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 p-4 mb-6 shadow-sm">
            <div class="flex items-center justify-between text-xs mb-2">
                <span class="font-bold {{ $totals['shipping_free'] ? 'text-emerald-600' : 'text-gray-700' }}">
                    @if($totals['shipping_free'])
                        🎉 Congratulations! You have unlocked Free Nationwide Delivery!
                    @else
                        Add <strong>Rs. {{ number_format($difference) }}</strong> more to enjoy Free Shipping!
                    @endif
                </span>
                <span class="font-semibold text-gray-500">{{ $progress }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500" style="width: {{ $progress }}%; background-color: {{ $totals['shipping_free'] ? '#10B981' : '#00A8B8' }};"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Cart Items List --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm divide-y divide-gray-100">
                    <div class="flex items-center justify-between pb-4">
                        <span class="text-sm font-bold text-gray-900">{{ $totals['item_count'] }} Items in Cart</span>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700" onclick="return confirm('Clear entire cart?')">
                                Clear Cart
                            </button>
                        </form>
                    </div>

                    @foreach($cart->items as $item)
                        @php
                            $img = $item->product->images->first();
                            $imgUrl = $img ? $img->image_url : 'https://placehold.co/100x100/f8fafc/0F1B4D?text=Item';
                        @endphp
                        <div class="py-4 flex items-center gap-4 flex-wrap sm:flex-nowrap">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="w-20 h-20 rounded-xl bg-gray-50 border border-gray-100 p-2 flex-shrink-0 flex items-center justify-center">
                                <img src="{{ $imgUrl }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain">
                            </a>

                            <div class="flex-1 min-w-[200px]">
                                <a href="{{ route('products.show', $item->product->slug) }}" class="text-sm font-bold text-gray-900 hover:text-teal-600 line-clamp-2">
                                    {{ $item->product->name }}
                                </a>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    Unit Price: <span class="font-semibold text-gray-700">Rs. {{ number_format($item->unit_price) }}</span>
                                </div>
                            </div>

                            {{-- Quantity Form --}}
                            <div class="flex items-center gap-2">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-gray-200 rounded-lg overflow-hidden h-9">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="w-8 h-full flex items-center justify-center text-gray-500 hover:bg-gray-100 font-bold">-</button>
                                    <span class="w-9 text-center text-xs font-bold text-gray-800">{{ $item->quantity }}</span>
                                    <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="w-8 h-full flex items-center justify-center text-gray-500 hover:bg-gray-100 font-bold">+</button>
                                </form>

                                <div class="text-right min-w-[90px]">
                                    <div class="text-sm font-black text-gray-900" style="color: #0F1B4D;">
                                        Rs. {{ number_format($item->total_price) }}
                                    </div>
                                </div>

                                {{-- Remove Form --}}
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 transition-colors" title="Remove item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('products.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700 flex items-center gap-1">
                        ← Continue Shopping
                    </a>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-5 sticky top-24">
                    <h2 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100" style="color: #0F1B4D;">
                        Order Summary
                    </h2>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal ({{ $totals['item_count'] }} items)</span>
                            <span class="font-semibold text-gray-900">Rs. {{ number_format($totals['subtotal']) }}</span>
                        </div>

                        <div class="flex justify-between text-gray-600">
                            <span>Estimated Shipping</span>
                            @if($totals['shipping_free'])
                                <span class="font-bold text-emerald-600 uppercase">FREE</span>
                            @else
                                <span class="font-semibold text-gray-900">Rs. {{ number_format($totals['shipping']) }}</span>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-gray-100 flex justify-between text-sm">
                            <span class="font-bold text-gray-900">Grand Total</span>
                            <span class="font-black text-lg" style="color: #0F1B4D;">Rs. {{ number_format($totals['total']) }}</span>
                        </div>
                    </div>

                    <a
                        href="{{ route('checkout.index') }}"
                        class="w-full h-12 rounded-xl text-white font-bold text-sm shadow-md flex items-center justify-center gap-2 transition-all hover:opacity-95"
                        style="background-color: #0F1B4D;"
                    >
                        Proceed to Checkout →
                    </a>

                    <div class="text-[11px] text-gray-400 text-center space-y-1">
                        <p>🔒 256-bit Secure Checkout</p>
                        <p>Cash on Delivery available across all Pakistan</p>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection
