@extends('layouts.app')

@section('title', 'Order Confirmed - ShopPulss')

@section('content')
<div class="max-w-screen-md mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm text-center">

        {{-- Success Icon --}}
        <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4 text-emerald-600">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>

        <h1 class="text-2xl md:text-3xl font-black text-gray-900" style="color: #0F1B4D;">
            Thank You for Your Order!
        </h1>
        <p class="text-xs text-gray-500 mt-2">
            Your order has been placed and is currently being processed by our warehouse fulfillment team.
        </p>

        {{-- Order ID Badge --}}
        <div class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-xl bg-gray-50 border border-gray-200">
            <span class="text-xs text-gray-500 font-medium">Order Number:</span>
            <span class="text-sm font-black text-navy-900 tracking-wider font-mono" style="color: #0F1B4D;">{{ $order->order_number }}</span>
        </div>

        {{-- Details Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-left mt-8 pt-6 border-t border-gray-100 text-xs">
            <div class="p-4 rounded-xl bg-gray-50">
                <span class="text-gray-400 font-medium block mb-1">Estimated Delivery:</span>
                <span class="font-bold text-gray-800 text-sm">2 - 4 Business Days</span>
                <span class="text-[11px] text-gray-500 block mt-0.5">Nationwide TCS / Leopards</span>
            </div>

            <div class="p-4 rounded-xl bg-gray-50">
                <span class="text-gray-400 font-medium block mb-1">Payment Method:</span>
                <span class="font-bold text-gray-800 text-sm uppercase">{{ $order->payment_method ?? 'COD' }}</span>
                <span class="text-[11px] text-emerald-600 block mt-0.5 font-semibold">Status: {{ ucfirst($order->payment_status) }}</span>
            </div>

            <div class="p-4 rounded-xl bg-gray-50">
                <span class="text-gray-400 font-medium block mb-1">Total Paid / Payable:</span>
                <span class="font-black text-gray-900 text-sm" style="color: #0F1B4D;">Rs. {{ number_format($order->total_amount) }}</span>
                <span class="text-[11px] text-gray-500 block mt-0.5">{{ $order->items->count() }} items</span>
            </div>
        </div>

        @if($order->payment_method === 'bank_transfer')
            <div class="mt-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-left text-xs space-y-1.5">
                <div class="font-bold text-amber-900 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Bank Transfer Pending Verification
                </div>
                <p class="text-amber-800 leading-relaxed">
                    Your order #{{ $order->order_number }} has been recorded. Our accounts team will verify your transfer (Ref: <strong class="font-mono">{{ $order->payment?->transaction_reference ?? 'Submitted' }}</strong>). Once confirmed, your parcel will be immediately dispatched via Leopards/TCS Courier.
                </p>
            </div>
        @endif

        {{-- Order Items Table --}}
        <div class="mt-8 text-left">
            <h2 class="text-sm font-bold text-gray-900 mb-3" style="color: #0F1B4D;">Ordered Items</h2>
            <div class="divide-y divide-gray-100 border border-gray-100 rounded-xl overflow-hidden">
                @foreach($order->items as $item)
                    <div class="p-3.5 flex items-center justify-between text-xs bg-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-50 p-1 flex-shrink-0 flex items-center justify-center border border-gray-100">
                                <img src="{{ $item->product?->images->first()?->image_url ?? 'https://placehold.co/40x40' }}" alt="" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">{{ $item->product_name }}</div>
                                <div class="text-gray-400 text-[11px]">Qty: {{ $item->quantity }} × Rs. {{ number_format($item->unit_price) }}</div>
                            </div>
                        </div>
                        <div class="font-bold text-gray-900">
                            Rs. {{ number_format($item->total_price) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Delivery Address Recap --}}
        @if($order->shippingAddress)
            <div class="mt-6 p-4 rounded-xl bg-blue-50/50 border border-blue-100 text-left text-xs">
                <span class="font-bold text-blue-950 block mb-1">Delivering to:</span>
                <p class="text-gray-700">
                    {{ $order->shippingAddress->full_name }} — {{ $order->shippingAddress->phone }}<br>
                    {{ $order->shippingAddress->street_address }}, {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }}
                </p>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a
                href="{{ route('orders.track', ['order_number' => $order->order_number]) }}"
                class="w-full sm:w-auto px-6 py-2.5 rounded-lg text-white font-semibold text-xs shadow-sm hover:opacity-95 transition-all"
                style="background-color: #0F1B4D;"
            >
                Track This Order
            </a>

            <a
                href="{{ route('products.index') }}"
                class="w-full sm:w-auto px-6 py-2.5 rounded-lg text-gray-700 font-semibold text-xs border border-gray-200 hover:bg-gray-50 transition-colors"
            >
                Continue Shopping
            </a>
        </div>

    </div>
</div>
@endsection
