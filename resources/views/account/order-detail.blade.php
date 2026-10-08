@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - ShopPulss')
@section('robots', 'noindex, follow')

@section('content')
<div class="bg-white border-b border-[#E6E8F2] py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="breadcrumb mb-2">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('account.dashboard') }}">Account</a>
            <span>/</span>
            <a href="{{ route('account.orders') }}">Orders</a>
            <span>/</span>
            <span class="current font-mono">{{ $order->order_number }}</span>
        </div>
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-[#0F1654] font-mono tracking-tight">
                    Order {{ $order->order_number }}
                </h1>
                <p class="text-xs text-gray-500 mt-1">Direct-retail verified order details and shipment tracking</p>
            </div>
            <span class="badge-navy uppercase text-xs py-1.5 px-3.5">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        <div class="lg:col-span-1">
            @include('account.partials.sidebar')
        </div>

        <div class="lg:col-span-3 space-y-6">
            {{-- Order Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-5 shadow-sp-card">
                    <span class="text-gray-400 font-bold block mb-1">Date Placed</span>
                    <span class="font-extrabold text-[#0F1654] text-sm">{{ $order->created_at->format('M d, Y - h:i A') }}</span>
                </div>
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-5 shadow-sp-card">
                    <span class="text-gray-400 font-bold block mb-1">Payment Method & Status</span>
                    <span class="font-extrabold text-[#0F1654] text-sm uppercase">{{ $order->payment_method ?? 'COD' }}</span>
                    <span class="text-emerald-600 font-extrabold block mt-0.5">{{ ucfirst($order->payment_status) }}</span>
                </div>
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-5 shadow-sp-card">
                    <span class="text-gray-400 font-bold block mb-1">Total Amount</span>
                    <span class="font-black text-lg text-[#0F1654]">Rs. {{ number_format($order->total_amount) }}</span>
                </div>
            </div>

            {{-- Items Card --}}
            <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 sm:p-7 shadow-sp-card">
                <h2 class="text-base font-black text-[#0F1654] mb-4 pb-3 border-b border-[#E6E8F2]">Items in this Order</h2>
                <div class="divide-y divide-[#E6E8F2] text-xs">
                    @foreach($order->items as $item)
                        <div class="py-4 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-[#F8FAFC] border border-[#E6E8F2] p-1 flex items-center justify-center flex-shrink-0">
                                    <img src="{{ $item->product?->images->first()?->image_url ?? 'https://placehold.co/40x40/F6F7FB/0F1654' }}" alt="" class="w-full h-full object-contain mix-blend-multiply">
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800">{{ $item->product_name }}</div>
                                    <div class="text-gray-400 text-[11px] mt-0.5">Qty: {{ $item->quantity }} × Rs. {{ number_format($item->unit_price) }}</div>
                                </div>
                            </div>
                            <div class="font-black text-[#0F1654]">
                                Rs. {{ number_format($item->total_price) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pricing Breakdowns --}}
                <div class="pt-5 border-t border-[#E6E8F2] space-y-2 text-xs text-gray-600 max-w-xs ml-auto">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span class="font-bold text-[#0F1654]">Rs. {{ number_format($order->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span>Shipping:</span>
                        <span class="font-bold text-[#0F1654]">Rs. {{ number_format($order->shipping_amount) }}</span>
                    </div>
                    <div class="pt-2 border-t border-[#E6E8F2] flex justify-between text-sm font-extrabold text-[#0F1654]">
                        <span>Grand Total:</span>
                        <span class="font-black text-lg text-[#FF5A1F]">Rs. {{ number_format($order->total_amount) }}</span>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            @if($order->shippingAddress)
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 shadow-sp-card text-xs">
                    <h2 class="text-base font-black text-[#0F1654] mb-3">Shipping Address</h2>
                    <p class="text-gray-700 leading-relaxed">
                        <strong>{{ $order->shippingAddress->full_name }}</strong> (<span class="font-mono">{{ $order->shippingAddress->phone }}</span>)<br>
                        {{ $order->shippingAddress->street_address }}<br>
                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}
                    </p>
                </div>
            @endif

            {{-- Courier Logistics & Live Tracking Card --}}
            @php $shipment = $order->shipment; @endphp
            @if($shipment)
                <div class="bg-[#FFF1EA] rounded-3xl border border-[#FF5A1F]/20 p-6 shadow-sp-card text-xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[#FF5A1F]/20">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-[#FF5A1F] block">Courier Fulfillment</span>
                            <div class="text-base font-black text-[#0F1654] flex items-center gap-2 mt-0.5">
                                <span>🚚 {{ $shipment->courier?->name ?? 'Courier Partner' }}</span>
                                <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-lg bg-white border border-[#FF5A1F]/20 text-[#0F1654]">
                                    {{ $shipment->tracking_number }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="badge-navy">
                                {{ $shipment->status_label }}
                            </span>
                            @if($shipment->tracking_url)
                                <a
                                    href="{{ $shipment->tracking_url }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="btn-teal py-1.5 px-3 text-xs"
                                >
                                    <span>Track on Courier Site</span>
                                    <span>↗</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div>
                            <span class="text-gray-500 block text-[11px]">Consignment Weight:</span>
                            <span class="font-bold text-[#0F1654]">{{ $shipment->weight }} KG</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[11px]">Collection Amount:</span>
                            <span class="font-bold text-[#0F1654]">{{ $shipment->cod_amount > 0 ? 'Rs. '.number_format($shipment->cod_amount).' (COD)' : 'Prepaid' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-[11px]">Dispatched:</span>
                            <span class="font-bold text-[#0F1654]">{{ $shipment->dispatched_at ? $shipment->dispatched_at->format('M d, Y') : 'In Preparation' }}</span>
                        </div>
                    </div>

                    {{-- Milestone logs --}}
                    @if($shipment->events->isNotEmpty())
                        <div class="pt-3 border-t border-[#FF5A1F]/20 space-y-2">
                            <span class="text-[11px] font-black text-[#0F1654] uppercase tracking-wider block">Shipment Journey</span>
                            <div class="space-y-2">
                                @foreach($shipment->events as $evt)
                                    <div class="p-3 rounded-xl bg-white border border-[#E6E8F2] flex items-start justify-between text-xs gap-3">
                                        <div class="flex items-start gap-2">
                                            <div class="w-2 h-2 rounded-full bg-[#0AA6B7] mt-1.5 flex-shrink-0"></div>
                                            <div>
                                                <span class="font-bold text-gray-800 block">{{ $evt->description }}</span>
                                                @if($evt->location)
                                                    <span class="text-[11px] text-gray-400 font-medium">📍 {{ $evt->location }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="text-[11px] text-gray-400 flex-shrink-0 font-mono">
                                            {{ $evt->event_time ? \Illuminate\Support\Carbon::parse($evt->event_time)->format('M d, h:i A') : '' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Tracking Timeline --}}
            @if($order->statusHistories->isNotEmpty())
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 shadow-sp-card text-xs">
                    <h2 class="text-base font-black text-[#0F1654] mb-4">Status Timeline</h2>
                    <div class="space-y-2">
                        @foreach($order->statusHistories as $h)
                            <div class="p-3 rounded-xl bg-[#F6F7FB] border border-[#E6E8F2] flex items-center justify-between">
                                <span class="font-bold text-gray-800">{{ $h->reason ?? ucfirst($h->to_status) }}</span>
                                <span class="text-gray-400 text-[11px] font-mono">{{ $h->created_at ? \Illuminate\Support\Carbon::parse($h->created_at)->format('M d, Y - h:i A') : '' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
