@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' - ShopPulss')

@section('content')
<div class="bg-gray-50 py-6 border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
            <a href="{{ route('account.dashboard') }}" class="hover:text-teal-600">Account</a>
            <span>/</span>
            <a href="{{ route('account.orders') }}" class="hover:text-teal-600">Orders</a>
            <span>/</span>
            <span class="text-gray-800 font-semibold font-mono">{{ $order->order_number }}</span>
        </div>
        <div class="flex items-center justify-between flex-wrap gap-2">
            <h1 class="text-2xl font-black text-gray-900 font-mono" style="color: #0F1B4D;">
                Order {{ $order->order_number }}
            </h1>
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-teal-100 text-teal-800">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </span>
        </div>
    </div>
</div>

<div class="max-w-screen-xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
            @include('account.partials.sidebar')
        </div>

        <div class="lg:col-span-3 space-y-6">
            {{-- Order Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                    <span class="text-gray-400 block mb-1">Date Placed</span>
                    <span class="font-bold text-gray-900 text-sm">{{ $order->created_at->format('M d, Y - h:i A') }}</span>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                    <span class="text-gray-400 block mb-1">Payment Method & Status</span>
                    <span class="font-bold text-gray-900 text-sm uppercase">{{ $order->payment_method ?? 'COD' }}</span>
                    <span class="text-emerald-600 font-semibold block mt-0.5">{{ ucfirst($order->payment_status) }}</span>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                    <span class="text-gray-400 block mb-1">Total Amount</span>
                    <span class="font-black text-base" style="color: #0F1B4D;">Rs. {{ number_format($order->total_amount) }}</span>
                </div>
            </div>

            {{-- Items Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h2 class="text-sm font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100" style="color: #0F1B4D;">Items in this Order</h2>
                <div class="divide-y divide-gray-100 text-xs">
                    @foreach($order->items as $item)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-gray-50 border border-gray-100 p-1 flex items-center justify-center flex-shrink-0">
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

                {{-- Pricing Breakdowns --}}
                <div class="pt-4 border-t border-gray-100 space-y-1.5 text-xs text-gray-600 max-w-xs ml-auto">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span class="font-semibold text-gray-900">Rs. {{ number_format($order->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping:</span>
                        <span class="font-semibold text-gray-900">Rs. {{ number_format($order->shipping_amount) }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100 flex justify-between text-sm font-bold text-gray-900">
                        <span>Total:</span>
                        <span class="font-black text-base" style="color: #0F1B4D;">Rs. {{ number_format($order->total_amount) }}</span>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            @if($order->shippingAddress)
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm text-xs">
                    <h2 class="text-sm font-bold text-gray-900 mb-2" style="color: #0F1B4D;">Shipping Address</h2>
                    <p class="text-gray-700 leading-relaxed">
                        <strong>{{ $order->shippingAddress->full_name }}</strong> ({{ $order->shippingAddress->phone }})<br>
                        {{ $order->shippingAddress->street_address }}<br>
                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}
                    </p>
                </div>
            @endif

            {{-- Courier Logistics & Live Tracking Card --}}
            @php $shipment = $order->shipment; @endphp
            @if($shipment)
                <div class="bg-white rounded-2xl border border-blue-100 p-6 shadow-sm text-xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-800 block">Courier Tracking</span>
                            <div class="text-base font-black text-gray-900 flex items-center gap-2" style="color: #0F1B4D;">
                                <span>🚚 {{ $shipment->courier?->name ?? 'Courier Partner' }}</span>
                                <span class="text-xs font-bold font-mono px-2 py-0.5 rounded bg-gray-50 border border-gray-200 text-gray-700">
                                    {{ $shipment->tracking_number }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-teal-50 border border-teal-200 text-teal-800">
                                {{ $shipment->status_label }}
                            </span>
                            @if($shipment->tracking_url)
                                <a
                                    href="{{ $shipment->tracking_url }}"
                                    target="_blank"
                                    class="px-3 py-1 rounded-lg text-xs font-bold text-white shadow-xs hover:opacity-95 transition-all inline-flex items-center gap-1"
                                    style="background-color: #00A8B8;"
                                >
                                    <span>Track on Courier Site</span>
                                    <span class="text-[10px]">↗</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div>
                            <span class="text-gray-400 block text-[11px]">Consignment Weight:</span>
                            <span class="font-bold text-gray-800">{{ $shipment->weight }} KG</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[11px]">Collection Amount:</span>
                            <span class="font-bold text-gray-800">{{ $shipment->cod_amount > 0 ? 'Rs. '.number_format($shipment->cod_amount).' (COD)' : 'Prepaid' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[11px]">Dispatched:</span>
                            <span class="font-bold text-gray-800">{{ $shipment->dispatched_at ? $shipment->dispatched_at->format('M d, Y') : 'In Preparation' }}</span>
                        </div>
                    </div>

                    {{-- Milestone logs --}}
                    @if($shipment->events->isNotEmpty())
                        <div class="pt-3 border-t border-gray-100 space-y-2">
                            <span class="text-[11px] font-bold text-gray-700 uppercase tracking-wider block">Shipment Journey</span>
                            <div class="space-y-2">
                                @foreach($shipment->events as $evt)
                                    <div class="p-2.5 rounded-xl bg-gray-50 flex items-start justify-between text-xs gap-3">
                                        <div class="flex items-start gap-2">
                                            <div class="w-2 h-2 rounded-full bg-teal-500 mt-1.5 flex-shrink-0"></div>
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
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm text-xs">
                    <h2 class="text-sm font-bold text-gray-900 mb-3" style="color: #0F1B4D;">Status Timeline</h2>
                    <div class="space-y-2">
                        @foreach($order->statusHistories as $h)
                            <div class="p-2.5 rounded-lg bg-gray-50 flex items-center justify-between">
                                <span class="font-medium text-gray-700">{{ $h->reason ?? ucfirst($h->to_status) }}</span>
                                <span class="text-gray-400 text-[11px]">{{ $h->created_at ? \Illuminate\Support\Carbon::parse($h->created_at)->format('M d, Y - h:i A') : '' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
