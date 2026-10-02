@extends('layouts.app')

@section('title', 'Track Your Order - ShopPulss')

@section('content')
<div class="bg-gray-50 py-6 border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        <h1 class="text-2xl md:text-3xl font-black text-gray-900" style="color: #0F1B4D;">
            Track Your Order
        </h1>
        <p class="text-xs text-gray-500 mt-1">Get real-time updates on your dispatch and courier shipment status</p>
    </div>
</div>

<div class="max-w-screen-md mx-auto px-4 py-8">
    {{-- Search Form Box --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-8">
        <form method="GET" action="{{ route('orders.track') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="order_number" class="block text-xs font-semibold text-gray-700 mb-1">Order Number *</label>
                    <input
                        id="order_number"
                        type="text"
                        name="order_number"
                        value="{{ $orderNumber }}"
                        required
                        placeholder="e.g. SP-20260927-ABCD"
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500 uppercase font-mono"
                    >
                </div>
                <div>
                    <label for="contact" class="block text-xs font-semibold text-gray-700 mb-1">Email or Phone (Optional)</label>
                    <input
                        id="contact"
                        type="text"
                        name="contact"
                        value="{{ $contact }}"
                        placeholder="e.g. 03001234567 or email"
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >
                </div>
            </div>

            <button
                type="submit"
                class="w-full h-11 rounded-lg text-white font-bold text-sm shadow-md transition-all hover:opacity-95"
                style="background-color: #00A8B8;"
            >
                Track Order Status
            </button>
        </form>
    </div>

    {{-- Result Display --}}
    @if($searched)
        @if($order)
            <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 shadow-sm space-y-8">
                {{-- Header with current status --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-6 border-b border-gray-100">
                    <div>
                        <div class="text-xs text-gray-400">Order Number</div>
                        <div class="text-xl font-black text-gray-900 font-mono" style="color: #0F1B4D;">{{ $order->order_number }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">Placed on {{ $order->created_at->format('M d, Y - h:i A') }}</div>
                    </div>
                    @php
                        $statusColors = [
                            'pending' => 'bg-amber-100 text-amber-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'shipped' => 'bg-indigo-100 text-indigo-800',
                            'delivered' => 'bg-emerald-100 text-emerald-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                </div>

                {{-- Status Timeline Steps --}}
                @php
                    $isCancelled = $order->status === 'cancelled';
                    $stages = [
                        'pending' => 'Order Placed',
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
                    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-xs">
                            <span class="font-bold block">This order has been cancelled.</span>
                            <span class="text-red-600">If you have any questions or would like to re-order, please contact our support team.</span>
                        </div>
                    </div>
                @else
                    <div>
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-6">Delivery Progress</h3>
                        <div class="grid grid-cols-5 gap-1 sm:gap-2 text-center relative">
                            @foreach(array_values($stages) as $idx => $label)
                                @php
                                    $isDone = $idx <= $currentIndex;
                                    $isCurrent = $idx === $currentIndex;
                                @endphp
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all {{ $isDone ? 'text-white' : 'bg-gray-100 text-gray-400' }}" style="{{ $isDone ? 'background-color: #00A8B8;' : '' }}">
                                        @if($isDone && !$isCurrent)
                                            ✓
                                        @else
                                            {{ $idx + 1 }}
                                        @endif
                                    </div>
                                    <span class="text-[10px] sm:text-[11px] font-semibold mt-2 {{ $isDone ? 'text-gray-900' : 'text-gray-400' }}">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Courier Logistics & Live Tracking Card --}}
                @php $shipment = $order->shipment; @endphp
                @if($shipment)
                    <div class="p-5 rounded-2xl bg-gradient-to-r from-blue-50/80 to-teal-50/50 border border-blue-100/80 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-800 block">Courier Fulfillment</span>
                                <div class="text-base font-black text-gray-900 flex items-center gap-2" style="color: #0F1B4D;">
                                    <span>🚚 {{ $shipment->courier?->name ?? 'Courier Partner' }}</span>
                                    <span class="text-xs font-bold font-mono px-2 py-0.5 rounded bg-white border border-gray-200 text-gray-700">
                                        {{ $shipment->tracking_number }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white shadow-xs border border-gray-100 text-teal-800">
                                    {{ $shipment->status_label }}
                                </span>
                                @if($shipment->tracking_url)
                                    <a
                                        href="{{ $shipment->tracking_url }}"
                                        target="_blank"
                                        class="px-3 py-1 rounded-lg text-xs font-bold text-white shadow-xs hover:opacity-95 transition-all inline-flex items-center gap-1"
                                        style="background-color: #00A8B8;"
                                    >
                                        <span>Courier Portal</span>
                                        <span class="text-[10px]">↗</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Consignment details --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs pt-3 border-t border-blue-100">
                            <div>
                                <span class="text-gray-400 block text-[11px]">Weight:</span>
                                <span class="font-bold text-gray-800">{{ $shipment->weight }} KG</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block text-[11px]">Destination:</span>
                                <span class="font-bold text-gray-800">{{ $shipment->destination_city ?? 'City on file' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block text-[11px]">Payment Collection:</span>
                                <span class="font-bold text-gray-800">{{ $shipment->cod_amount > 0 ? 'COD: Rs. '.number_format($shipment->cod_amount) : 'Prepaid (Rs. 0)' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 block text-[11px]">Dispatched:</span>
                                <span class="font-bold text-gray-800">{{ $shipment->dispatched_at ? $shipment->dispatched_at->format('M d, Y') : 'Processing' }}</span>
                            </div>
                        </div>

                        {{-- Detailed Courier Events Timeline --}}
                        @if($shipment->events->isNotEmpty())
                            <div class="pt-3 border-t border-blue-100 space-y-2">
                                <span class="text-[11px] font-bold text-gray-700 uppercase tracking-wider block">Courier Milestones</span>
                                <div class="space-y-2">
                                    @foreach($shipment->events as $evt)
                                        <div class="p-2.5 rounded-xl bg-white border border-gray-100 flex items-start justify-between text-xs gap-3">
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

                {{-- Payment Details Summary --}}
                <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div>
                        <span class="text-gray-400 block">Payment Method</span>
                        <span class="font-bold text-gray-900 text-sm uppercase">{{ $order->payment_method ?? 'COD' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Payment Status</span>
                        <span class="font-bold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @if($order->payment?->transaction_reference)
                        <div>
                            <span class="text-gray-400 block">Transaction Reference</span>
                            <span class="font-mono font-bold text-gray-800">{{ $order->payment->transaction_reference }}</span>
                        </div>
                    @endif
                </div>

                {{-- Ordered Items --}}
                <div class="pt-6 border-t border-gray-100">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">Items in this Order</h3>
                    <div class="divide-y divide-gray-100 border border-gray-100 rounded-xl overflow-hidden text-xs">
                        @foreach($order->items as $item)
                            <div class="p-3 flex items-center justify-between">
                                <span class="font-medium text-gray-800">{{ $item->product_name }} (×{{ $item->quantity }})</span>
                                <span class="font-bold text-gray-900">Rs. {{ number_format($item->total_price) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 flex justify-between text-xs font-bold text-gray-900 px-1">
                        <span>Total Amount:</span>
                        <span>Rs. {{ number_format($order->total_amount) }}</span>
                    </div>
                </div>

                {{-- Status History Logs --}}
                @if($order->statusHistories->isNotEmpty())
                    <div class="pt-6 border-t border-gray-100">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">Activity Log</h3>
                        <div class="space-y-2 text-xs">
                            @foreach($order->statusHistories as $history)
                                <div class="p-2.5 rounded-lg bg-gray-50 flex items-center justify-between text-gray-600">
                                    <span>{{ $history->reason ?? ucfirst($history->to_status) }}</span>
                                    <span class="text-[11px] text-gray-400">{{ $history->created_at ? \Illuminate\Support\Carbon::parse($history->created_at)->format('M d, Y - h:i A') : '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center shadow-sm">
                <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-3 text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="font-bold text-base text-gray-900">Order Not Found</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                    We could not locate order <strong>"{{ $orderNumber }}"</strong>. Please double-check your order ID from your confirmation email or message.
                </p>
            </div>
        @endif
    @endif
</div>
@endsection
