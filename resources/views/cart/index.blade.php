@extends('layouts.app')

@section('title', 'Shopping Cart - ShopPulss')
@section('description', 'Review your selected items and proceed to checkout. 100% secure Cash on Delivery across Pakistan.')
@section('keywords', 'online shopping Pakistan, Cash on Delivery, ShopPulss, direct retail')

@section('content')
{{-- Cart Header & Breadcrumbs --}}
<div class="bg-white border-b border-[#E6E8F2] py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="breadcrumb mb-2">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span class="current">Shopping Cart</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-[#0F1654] tracking-tight">Your Shopping Cart</h1>
                <p class="text-xs text-gray-500 mt-1">Review your direct retail items before proceeding to checkout</p>
            </div>
            @if(!$cart->items->isEmpty())
                <span class="badge-navy">{{ $totals['item_count'] }} {{ Str::plural('item', $totals['item_count']) }}</span>
            @endif
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    @if($cart->items->isEmpty())
        <div class="bg-white rounded-3xl border border-[#E6E8F2] p-12 md:p-16 text-center shadow-sp-card max-w-lg mx-auto">
            <div class="w-20 h-20 rounded-2xl bg-[#FFF1EA] flex items-center justify-center mx-auto mb-5 text-[#FF5A1F]">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h2 class="text-xl font-black text-[#0F1654] mb-2">Your cart is currently empty</h2>
            <p class="text-xs text-gray-500 mb-7 leading-relaxed">Looks like you haven't added any authentic direct-retail products yet. Explore our verified catalog with nationwide Cash on Delivery!</p>
            <a href="{{ route('products.index') }}" class="btn-primary">
                <span>Start Shopping Now</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    @else
        {{-- Free Shipping Banner / Progress --}}
        @php
            $freeShippingThreshold = 2500;
            $progress = min(100, round(($totals['subtotal'] / $freeShippingThreshold) * 100));
            $difference = max(0, $freeShippingThreshold - $totals['subtotal']);
        @endphp
        <div class="bg-[#FFF1EA] rounded-2xl border border-[#FF5A1F]/20 p-4 mb-6 shadow-sm">
            <div class="flex items-center justify-between text-xs mb-2">
                <span class="font-bold {{ $totals['shipping_free'] ? 'text-emerald-700' : 'text-[#0F1654]' }} flex items-center gap-2">
                    @if($totals['shipping_free'])
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        ðŸŽ‰ Congratulations! You have unlocked <strong>Free Nationwide Delivery</strong>!
                    @else
                        <span class="w-2 h-2 rounded-full bg-[#FF5A1F]"></span>
                        Add <strong class="text-[#FF5A1F]">Rs. {{ number_format($difference) }}</strong> more to unlock Free Nationwide Delivery!
                    @endif
                </span>
                <span class="font-extrabold text-[#0F1654]">{{ $progress }}%</span>
            </div>
            <div class="w-full bg-white rounded-full h-2.5 overflow-hidden p-0.5 border border-[#FF5A1F]/15">
                <div class="h-full rounded-full transition-all duration-500 {{ $totals['shipping_free'] ? 'bg-emerald-500' : 'bg-orange-grad' }}" style="width: {{ $progress }}%;"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- Cart Items List --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-5 sm:p-6 shadow-sp-card divide-y divide-[#E6E8F2]">
                    <div class="flex items-center justify-between pb-4">
                        <span class="text-sm font-extrabold text-[#0F1654]">{{ $totals['item_count'] }} {{ Str::plural('Item', $totals['item_count']) }} in Cart</span>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 transition-colors flex items-center gap-1" onclick="return confirm('Clear entire cart?')">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Clear Cart
                            </button>
                        </form>
                    </div>

                    @foreach($cart->items as $item)
                        @php
                            $img = $item->product->images->first();
                            $imgUrl = $img ? $img->image_url : 'https://placehold.co/100x100/F6F7FB/0F1654?text=Item';
                        @endphp
                        <div class="py-4 sm:py-5 flex items-center gap-4 flex-wrap sm:flex-nowrap">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-[#F8FAFC] border border-[#E6E8F2] p-2 flex-shrink-0 flex items-center justify-center overflow-hidden group">
                                <img src="{{ $imgUrl }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-300">
                            </a>

                            <div class="flex-1 min-w-[200px]">
                                <span class="badge-teal text-[10px] mb-1">Direct Retail</span>
                                <a href="{{ route('products.show', $item->product->slug) }}" class="text-sm font-bold text-[#161616] hover:text-[#FF5A1F] line-clamp-2 transition-colors block">
                                    {{ $item->product->name }}
                                </a>
                                <div class="text-xs text-gray-500 mt-1">
                                    Unit Price: <span class="font-extrabold text-[#0F1654]">Rs. {{ number_format($item->unit_price) }}</span>
                                </div>
                            </div>

                            {{-- Quantity Form & Price --}}
                            <div class="flex items-center gap-4 ml-auto sm:ml-0">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-[#E6E8F2] rounded-full bg-[#F6F7FB] overflow-hidden h-9 px-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="w-7 h-7 rounded-full flex items-center justify-center text-gray-600 hover:bg-white hover:text-[#FF5A1F] font-black text-sm transition-colors" aria-label="Decrease">-</button>
                                    <span class="w-8 text-center text-xs font-black text-[#0F1654]">{{ $item->quantity }}</span>
                                    <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="w-7 h-7 rounded-full flex items-center justify-center text-gray-600 hover:bg-white hover:text-[#FF5A1F] font-black text-sm transition-colors" aria-label="Increase">+</button>
                                </form>

                                <div class="text-right min-w-[100px]">
                                    <div class="text-base font-black text-[#0F1654]">
                                        Rs. {{ number_format($item->total_price) }}
                                    </div>
                                </div>

                                {{-- Remove Form --}}
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Remove item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('products.index') }}" class="view-all-link text-xs font-extrabold flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>Continue Shopping</span>
                    </a>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 shadow-sp-card space-y-5 sticky top-28">
                    <h2 class="text-base font-black text-[#0F1654] pb-3 border-b border-[#E6E8F2] flex items-center justify-between">
                        <span>Order Summary</span>
                        <span class="text-xs font-semibold text-gray-400">PKR</span>
                    </h2>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal ({{ $totals['item_count'] }} items)</span>
                            <span class="font-bold text-[#0F1654]">Rs. {{ number_format($totals['subtotal']) }}</span>
                        </div>

                        <div class="flex justify-between text-gray-600 items-center">
                            <span>Estimated Shipping</span>
                            @if($totals['shipping_free'])
                                <span class="badge-teal">FREE</span>
                            @else
                                <span class="font-bold text-[#0F1654]">Rs. {{ number_format($totals['shipping']) }}</span>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-[#E6E8F2] flex justify-between items-baseline text-sm">
                            <span class="font-extrabold text-[#0F1654]">Grand Total</span>
                            <span class="text-xl font-black text-[#0F1654]">Rs. {{ number_format($totals['total']) }}</span>
                        </div>
                    </div>

                    <a
                        href="{{ route('checkout.index') }}"
                        class="btn-primary w-full py-3.5 text-sm"
                        id="proceed-checkout-btn"
                    >
                        <span>Proceed to Checkout</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>

                    <div class="pt-3 border-t border-[#E6E8F2] space-y-2 text-[11px] text-gray-500">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#0AA6B7] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/></svg>
                            <span>100% Genuine Direct Retail Products</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#FF5A1F] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                            <span>Cash on Delivery available nationwide</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#0AA6B7] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>
                            <span>7-Day Easy Return & Refund Guarantee</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection
