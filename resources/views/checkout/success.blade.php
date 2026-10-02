@extends('layouts.app')

@section('title', 'Order Confirmed - ShopPulss')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
    <div class="bg-white rounded-3xl border border-[#E6E8F2] p-8 md:p-10 shadow-sp-card text-center">

        {{-- Success Icon --}}
        <div class="w-20 h-20 rounded-full bg-emerald-50 border-4 border-emerald-100 flex items-center justify-center mx-auto mb-5 text-emerald-600 shadow-sm">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>

        <span class="badge-teal mb-3">Order Successfully Placed</span>
        <h1 class="text-2xl sm:text-3xl font-black text-[#0F1654] tracking-tight">
            Thank You for Your Order!
        </h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-2 max-w-lg mx-auto leading-relaxed">
            Your direct-retail order has been recorded and is currently being packed by our Karachi central fulfillment team.
        </p>

        {{-- Order ID Badge --}}
        <div class="inline-flex items-center gap-3 mt-6 px-5 py-2.5 rounded-full bg-[#FFF1EA] border border-[#FF5A1F]/30 shadow-xs">
            <span class="text-xs font-bold text-gray-500">Order Number:</span>
            <span class="text-sm font-black text-[#FF5A1F] tracking-wider font-mono">{{ $order->order_number }}</span>
        </div>

        {{-- Details Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-left mt-8 pt-8 border-t border-[#E6E8F2] text-xs">
            <div class="p-4 rounded-2xl bg-[#F6F7FB] border border-[#E6E8F2]">
                <span class="text-gray-400 font-semibold block mb-1">Estimated Delivery:</span>
                <span class="font-extrabold text-[#0F1654] text-sm block">2 - 4 Business Days</span>
                <span class="text-[11px] text-[#0AA6B7] font-bold block mt-0.5">Nationwide TCS / Leopards</span>
            </div>

            <div class="p-4 rounded-2xl bg-[#F6F7FB] border border-[#E6E8F2]">
                <span class="text-gray-400 font-semibold block mb-1">Payment Method:</span>
                <span class="font-extrabold text-[#0F1654] text-sm block uppercase">{{ $order->payment_method ?? 'COD' }}</span>
                <span class="text-[11px] text-emerald-600 block mt-0.5 font-bold">Status: {{ ucfirst($order->payment_status) }}</span>
            </div>

            <div class="p-4 rounded-2xl bg-[#F6F7FB] border border-[#E6E8F2]">
                <span class="text-gray-400 font-semibold block mb-1">Total Paid / Payable:</span>
                <span class="font-black text-[#0F1654] text-sm block">Rs. {{ number_format($order->total_amount) }}</span>
                <span class="text-[11px] text-gray-500 block mt-0.5">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</span>
            </div>
        </div>

        @if($order->payment_method === 'bank_transfer')
            <div class="mt-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-left text-xs space-y-1.5">
                <div class="font-bold text-amber-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Bank Transfer Awaiting Verification
                </div>
                <p class="text-amber-800 leading-relaxed text-[11px]">
                    Your order #{{ $order->order_number }} has been recorded. Our verification team will cross-check your payment (Ref: <strong class="font-mono text-amber-950">{{ $order->payment?->transaction_reference ?? 'Submitted' }}</strong>). Once confirmed, your parcel will be immediately dispatched.
                </p>
            </div>
        @endif

        {{-- Order Items Table --}}
        <div class="mt-8 text-left">
            <h2 class="text-sm font-black text-[#0F1654] mb-3">Ordered Items</h2>
            <div class="divide-y divide-[#E6E8F2] border border-[#E6E8F2] rounded-2xl overflow-hidden bg-white">
                @foreach($order->items as $item)
                    <div class="p-4 flex items-center justify-between text-xs hover:bg-[#F6F7FB] transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-[#F8FAFC] p-1 flex-shrink-0 flex items-center justify-center border border-[#E6E8F2]">
                                <img src="{{ $item->product?->images->first()?->image_url ?? 'https://placehold.co/40x40/F6F7FB/0F1654' }}" alt="" class="w-full h-full object-contain mix-blend-multiply">
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">{{ $item->product_name }}</div>
                                <div class="text-gray-400 text-[11px]">Qty: {{ $item->quantity }} × Rs. {{ number_format($item->unit_price) }}</div>
                            </div>
                        </div>
                        <div class="font-black text-[#0F1654]">
                            Rs. {{ number_format($item->total_price) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Delivery Address Recap --}}
        @if($order->shippingAddress)
            <div class="mt-6 p-4 rounded-2xl bg-[#FFF1EA] border border-[#FF5A1F]/20 text-left text-xs">
                <span class="font-black text-[#0F1654] block mb-1">Delivering to:</span>
                <p class="text-gray-700 leading-relaxed">
                    <strong>{{ $order->shippingAddress->full_name }}</strong> — <span class="font-mono">{{ $order->shippingAddress->phone }}</span><br>
                    {{ $order->shippingAddress->street_address }}, {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }}
                </p>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a
                href="{{ route('orders.track', ['order_number' => $order->order_number]) }}"
                class="btn-navy w-full sm:w-auto"
            >
                <svg class="w-4 h-4 text-[#0AA6B7]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                <span>Track This Order</span>
            </a>

            <a
                href="{{ route('products.index') }}"
                class="btn-secondary w-full sm:w-auto"
            >
                <span>Continue Shopping</span>
            </a>
        </div>

    </div>
</div>
@endsection
