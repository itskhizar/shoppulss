@extends('layouts.app')

@section('title', 'Track Your Order - ShopPulss')

@section('content')
{{-- Header --}}
<div class="bg-white border-b border-[#E6E8F2] py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="breadcrumb mb-2">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span class="current">Track Order</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-[#0F1654] tracking-tight">Track Your Order</h1>
        <p class="text-xs text-gray-500 mt-1">Get real-time updates on dispatch and courier shipment status across Pakistan</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
    {{-- Search Form Box --}}
    <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 sm:p-8 shadow-sp-card mb-8">
        <form method="GET" action="{{ route('orders.track') }}" class="space-y-4" id="order-tracking-form">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="order_number" class="sp-label">Order Number *</label>
                    <input
                        id="order_number"
                        type="text"
                        name="order_number"
                        value="{{ $orderNumber }}"
                        required
                        placeholder="e.g. SP-2026..."
                        class="sp-input font-mono uppercase"
                    >
                </div>
                <div>
                    <label for="contact" class="sp-label">Email or Phone (Optional)</label>
                    <input
                        id="contact"
                        type="text"
                        name="contact"
                        value="{{ $contact }}"
                        placeholder="e.g. 03001234567 or email"
                        class="sp-input"
                    >
                </div>
            </div>

            <button
                type="submit"
                class="btn-primary w-full py-3.5 text-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Track Order Status</span>
            </button>
        </form>
    </div>

    {{-- Result Display --}}
    @if($searched)
        @if($order)
            <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 sm:p-8 shadow-sp-card space-y-8">
                {{-- Header with current status --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[#E6E8F2]">
                    <div>
                        <div class="text-xs font-semibold text-gray-400">Tracking Order:</div>
                        <div class="text-2xl font-black text-[#0F1654] font-mono tracking-tight">{{ $order->order_number }}</div>
                        <div class="text-xs text-gray-500 mt-1">Placed on {{ $order->created_at->format('M d, Y - h:i A') }}</div>
                    </div>
                    @php
                        $statusBadges = [
                            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'processing' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                            'shipped' => 'bg-[#FFF1EA] text-[#FF5A1F] border-[#FF5A1F]/30',
                            'delivered' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                        ];
                    @endphp
                    <div>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider border {{ $statusBadges[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                </div>

                {{-- Status Timeline Steps --}}
                @php
                    $isCancelled = $order->status === 'cancelled';
                    $stages = [
                        'pending' => 'Placed',
                        'confirmed' => 'Confirmed',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered'
                    ];
                    $statusRank = [
                        'pending' => 0,
                        'confirmed' => 1,
                        'processing' => 2,
                        'packed' => 2,
                        'shipped' => 3,
                        'delivered' => 4,
                        'cancelled' => -1,
                    ];
                    $currentIndex = $statusRank[$order->status] ?? 0;
                @endphp

                @if($isCancelled)
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 text-xs">
                        <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <span class="font-bold block">This order has been cancelled.</span>
                            <span class="text-red-600">If you have any questions or would like to re-order, please contact our support team.</span>
                        </div>
                    </div>
                @else
                    <div>
                        <h3 class="text-xs font-black text-[#0F1654] uppercase tracking-wider mb-6">Delivery Progress</h3>
                        <div class="grid grid-cols-5 gap-1 sm:gap-2 text-center relative">
                            @foreach(array_values($stages) as $idx => $label)
                                @php
                                    $isDone = $idx <= $currentIndex;
                                    $isCurrent = $idx === $currentIndex;
                                @endphp
                                <div class="flex flex-col items-center">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-black transition-all {{ $isDone ? 'bg-orange-grad text-white shadow-sp-orange' : 'bg-gray-100 text-gray-400 border border-gray-200' }}">
                                        @if($isDone && !$isCurrent)
                                            ✓
                                        @else
                                            {{ $idx + 1 }}
                                        @endif
                                    </div>
                                    <span class="text-[10px] sm:text-xs font-bold mt-2 {{ $isDone ? 'text-[#0F1654]' : 'text-gray-400' }}">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Courier Logistics & Live Tracking Card --}}
                @php $shipment = $order->shipment; @endphp
                @if($shipment)
                    <div class="p-5 sm:p-6 rounded-2xl bg-[#FFF1EA] border border-[#FF5A1F]/20 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-[#FF5A1F] block">Courier Logistics</span>
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
                                        <span>Courier Portal</span>
                                        <span>↗</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Consignment details --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs pt-3 border-t border-[#FF5A1F]/15">
                            <div>
                                <span class="text-gray-500 block text-[11px]">Weight:</span>
                                <span class="font-bold text-[#0F1654]">{{ $shipment->weight }} KG</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block text-[11px]">Destination:</span>
                                <span class="font-bold text-[#0F1654]">{{ $shipment->destination_city ?? 'City on file' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block text-[11px]">Payment Collection:</span>
                                <span class="font-bold text-[#0F1654]">{{ $shipment->cod_amount > 0 ? 'COD: Rs. '.number_format($shipment->cod_amount) : 'Prepaid (Rs. 0)' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block text-[11px]">Dispatched:</span>
                                <span class="font-bold text-[#0F1654]">{{ $shipment->dispatched_at ? $shipment->dispatched_at->format('M d, Y') : 'Processing' }}</span>
                            </div>
                        </div>

                        {{-- Detailed Courier Events Timeline --}}
                        @if($shipment->events->isNotEmpty())
                            <div class="pt-3 border-t border-[#FF5A1F]/15 space-y-2">
                                <span class="text-[11px] font-black text-[#0F1654] uppercase tracking-wider block">Courier Milestones</span>
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

                {{-- Payment Details Summary --}}
                <div class="p-4 rounded-2xl bg-[#F6F7FB] border border-[#E6E8F2] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div>
                        <span class="text-gray-400 block font-semibold">Payment Method</span>
                        <span class="font-extrabold text-[#0F1654] text-sm uppercase">{{ $order->payment_method ?? 'COD' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block font-semibold">Payment Status</span>
                        <span class="font-extrabold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @if($order->payment?->transaction_reference)
                        <div>
                            <span class="text-gray-400 block font-semibold">Transaction Reference</span>
                            <span class="font-mono font-bold text-[#0F1654]">{{ $order->payment->transaction_reference }}</span>
                        </div>
                    @endif
                </div>

                {{-- Ordered Items --}}
                <div class="pt-6 border-t border-[#E6E8F2]">
                    <h3 class="text-xs font-black text-[#0F1654] uppercase tracking-wider mb-3">Items in this Order</h3>
                    <div class="divide-y divide-[#E6E8F2] border border-[#E6E8F2] rounded-2xl overflow-hidden text-xs bg-white">
                        @foreach($order->items as $item)
                            <div class="p-3.5 flex items-center justify-between">
                                <span class="font-bold text-gray-800">{{ $item->product_name }} (×{{ $item->quantity }})</span>
                                <span class="font-black text-[#0F1654]">Rs. {{ number_format($item->total_price) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 flex justify-between text-xs font-extrabold text-[#0F1654] px-1">
                        <span>Total Amount:</span>
                        <span class="text-base font-black">Rs. {{ number_format($order->total_amount) }}</span>
                    </div>
                </div>

                {{-- Status History Logs --}}
                @if($order->statusHistories->isNotEmpty())
                    <div class="pt-6 border-t border-[#E6E8F2]">
                        <h3 class="text-xs font-black text-[#0F1654] uppercase tracking-wider mb-3">Activity Log</h3>
                        <div class="space-y-2 text-xs">
                            @foreach($order->statusHistories as $history)
                                <div class="p-2.5 rounded-xl bg-[#F6F7FB] border border-[#E6E8F2] flex items-center justify-between text-gray-600">
                                    <span class="font-semibold">{{ $history->reason ?? ucfirst($history->to_status) }}</span>
                                    <span class="text-[11px] text-gray-400 font-mono">{{ $history->created_at ? \Illuminate\Support\Carbon::parse($history->created_at)->format('M d, Y - h:i A') : '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-3xl border border-[#E6E8F2] p-10 text-center shadow-sp-card">
                <div class="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center mx-auto mb-4 text-red-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="font-black text-lg text-[#0F1654]">Order Not Found</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto leading-relaxed">
                    We could not locate order <strong>"{{ $orderNumber }}"</strong>. Please double-check your order number from your confirmation SMS or email.
                </p>
            </div>
        @endif
    @endif
</div>
@endsection
