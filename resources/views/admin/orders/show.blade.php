@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)
@section('header', 'Order Details')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Top Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-gray-500 hover:text-gray-900">← Back to Orders</a>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-mono text-gray-500">{{ $order->order_number }}</span>
            </div>
            <h1 class="text-2xl font-black text-gray-900 font-mono mt-1" style="color: #0F1B4D;">
                Order {{ $order->order_number }}
            </h1>
            <p class="text-xs text-gray-500">Placed on {{ $order->created_at->format('M d, Y - h:i A') }}</p>
        </div>

        <div class="flex items-center gap-3">
            @php
                $colors = [
                    'pending'    => 'bg-amber-100 text-amber-800',
                    'confirmed'  => 'bg-blue-100 text-blue-800',
                    'processing' => 'bg-indigo-100 text-indigo-800',
                    'packed'     => 'bg-purple-100 text-purple-800',
                    'shipped'    => 'bg-cyan-100 text-cyan-800',
                    'delivered'  => 'bg-emerald-100 text-emerald-800',
                    'cancelled'  => 'bg-red-100 text-red-800',
                ];
            @endphp
            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                Status: {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 cols: Items & Fulfillment Status Update --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Status Update Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 mb-4" style="color: #0F1B4D;">
                    Order Status Transition
                </h2>

                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="status" class="block text-xs font-semibold text-gray-700 mb-1">Update Lifecycle Status</label>
                            <select
                                id="status"
                                name="status"
                                required
                                class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500"
                            >
                                <option value="{{ $order->status }}" selected>Current: {{ ucfirst($order->status) }}</option>
                                @if(!empty($allowedNextStatuses))
                                    <optgroup label="Next Step">
                                        @foreach($allowedNextStatuses as $st)
                                            <option value="{{ $st }}">→ Advance to: {{ ucfirst($st) }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                <optgroup label="All Lifecycle Statuses">
                                    @foreach(\App\Models\Order::STATUSES as $st)
                                        @if($st !== $order->status && !in_array($st, $allowedNextStatuses))
                                            <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <div>
                            <label for="payment_status" class="block text-xs font-semibold text-gray-700 mb-1">Payment Status</label>
                            <select
                                id="payment_status"
                                name="payment_status"
                                class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500"
                            >
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>

                        <div>
                            <label for="reason" class="block text-xs font-semibold text-gray-700 mb-1">Reason / Tracking Note</label>
                            <input
                                id="reason"
                                type="text"
                                name="reason"
                                placeholder="e.g. Courier tracking #12345"
                                class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500"
                            >
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-[11px] text-gray-400">
                            Valid next steps: {{ empty($allowedNextStatuses) ? 'Order reached final state' : implode(' or ', array_map('ucfirst', $allowedNextStatuses)) }}
                        </span>
                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-lg text-white font-semibold text-xs shadow-sm hover:opacity-95"
                            style="background-color: #00A8B8;"
                        >
                            Update Status
                        </button>
                    </div>
                </form>
            </div>

            {{-- Courier Fulfillment & Delivery Management Card --}}
            @php $shipment = $order->shipment; @endphp
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2.5">
                        <span class="text-xl">🚚</span>
                        <div>
                            <h2 class="text-sm font-bold text-gray-900" style="color: #0F1B4D;">
                                Courier Fulfillment & Logistics
                            </h2>
                            <p class="text-[11px] text-gray-400">Manage TCS, Leopards, Trax bookings, tracking and shipping labels</p>
                        </div>
                    </div>

                    @if($shipment)
                        <div class="flex items-center gap-2">
                            <a
                                href="{{ route('admin.shipments.label', $shipment->id) }}"
                                target="_blank"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold text-white shadow-xs hover:opacity-95 transition-all inline-flex items-center gap-1.5"
                                style="background-color: #0F1B4D;"
                            >
                                <span>🖨 Print Shipping Label</span>
                            </a>

                            @if($shipment->tracking_url)
                                <a
                                    href="{{ $shipment->tracking_url }}"
                                    target="_blank"
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition-colors inline-flex items-center gap-1"
                                >
                                    <span>Courier Portal</span>
                                    <span class="text-[10px]">↗</span>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                @if($shipment)
                    {{-- Active Shipment Overview --}}
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                            <div>
                                <span class="text-gray-400 block text-[11px]">Courier Partner & Consignment #</span>
                                <div class="font-bold text-sm text-gray-900 flex items-center gap-2">
                                    <span>{{ $shipment->courier?->name }}</span>
                                    <span class="font-mono bg-white px-2 py-0.5 rounded border border-gray-200 text-teal-800">{{ $shipment->tracking_number }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-400 block text-[11px] sm:text-right">Shipment Status</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white border border-gray-200 text-teal-800 sm:text-right inline-block">
                                    {{ $shipment->status_label }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs pt-2 border-t border-slate-200/60">
                            <div>
                                <span class="text-gray-400 block text-[11px]">Package Weight:</span>
                                <strong class="text-gray-800">{{ $shipment->weight }} KG ({{ $shipment->pieces }} pc)</strong>
                            </div>
                            <div>
                                <span class="text-gray-400 block text-[11px]">COD to Collect:</span>
                                <strong class="text-gray-800">{{ $shipment->cod_amount > 0 ? 'Rs. '.number_format($shipment->cod_amount) : 'Prepaid (Rs. 0)' }}</strong>
                            </div>
                            <div>
                                <span class="text-gray-400 block text-[11px]">Destination:</span>
                                <strong class="text-gray-800">{{ $shipment->destination_city }}</strong>
                            </div>
                            <div>
                                <span class="text-gray-400 block text-[11px]">Booked At:</span>
                                <strong class="text-gray-800">{{ $shipment->created_at->format('M d, Y - h:i A') }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Form to Update Shipment Status & Milestone --}}
                    <form method="POST" action="{{ route('admin.shipments.status', $shipment->id) }}" class="p-4 rounded-xl bg-gray-50 border border-gray-100 space-y-3">
                        @csrf
                        @method('PATCH')
                        <div class="text-xs font-bold text-gray-800">Advance Delivery Milestone</div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label for="shipment_status" class="block text-[11px] font-semibold text-gray-600 mb-1">New Shipment Status *</label>
                                <select id="shipment_status" name="status" class="w-full h-9 px-2.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500">
                                    @foreach(\App\Models\Shipment::STATUSES as $st)
                                        <option value="{{ $st }}" {{ $shipment->shipment_status === $st ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $st)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="location" class="block text-[11px] font-semibold text-gray-600 mb-1">Current Hub / Location</label>
                                <input type="text" id="location" name="location" placeholder="e.g. Lahore Sorting Hub, Abbottabad Center" class="w-full h-9 px-2.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500">
                            </div>
                            <div>
                                <label for="description" class="block text-[11px] font-semibold text-gray-600 mb-1">Milestone Description</label>
                                <input type="text" id="description" name="description" placeholder="e.g. Out with rider for final delivery" class="w-full h-9 px-2.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 rounded-lg text-white font-semibold text-xs shadow-sm hover:opacity-95" style="background-color: #00A8B8;">
                                Log Milestone & Update
                            </button>
                        </div>
                    </form>

                    {{-- Milestone History --}}
                    @if($shipment->events->isNotEmpty())
                        <div class="space-y-2 pt-2">
                            <span class="text-xs font-bold text-gray-800">Courier Milestones Timeline</span>
                            <div class="space-y-1.5 max-h-48 overflow-y-auto">
                                @foreach($shipment->events as $evt)
                                    <div class="p-2.5 rounded-lg bg-white border border-gray-100 flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                                            <span class="font-semibold text-gray-800">{{ $evt->description }}</span>
                                            @if($evt->location)
                                                <span class="text-gray-400 font-normal">({{ $evt->location }})</span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] text-gray-400 font-mono">{{ $evt->event_time ? \Illuminate\Support\Carbon::parse($evt->event_time)->format('M d, h:i A') : '' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                @else
                    {{-- Create / Book Shipment Form --}}
                    <form method="POST" action="{{ route('admin.orders.shipment.store', $order->id) }}" class="space-y-4">
                        @csrf
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-800">
                            <strong>Ready to dispatch?</strong> Pack the products, generate the courier booking below, and print the shipping label to paste on the parcel.
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label for="courier_id" class="block text-xs font-semibold text-gray-700 mb-1">Select Courier *</label>
                                <select id="courier_id" name="courier_id" required class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500">
                                    @foreach($couriers as $c)
                                        <option value="{{ $c->id }}" {{ $c->code === 'leopards' ? 'selected' : '' }}>
                                            {{ $c->name }} ({{ strtoupper($c->code) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="tracking_number" class="block text-xs font-semibold text-gray-700 mb-1">Courier Tracking / CN #</label>
                                <input
                                    type="text"
                                    id="tracking_number"
                                    name="tracking_number"
                                    placeholder="Leave empty to auto-generate"
                                    class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500 font-mono"
                                >
                            </div>

                            <div>
                                <label for="weight" class="block text-xs font-semibold text-gray-700 mb-1">Package Weight (KG) *</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    id="weight"
                                    name="weight"
                                    value="0.50"
                                    required
                                    class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500"
                                >
                            </div>

                            <div>
                                <label for="cod_amount" class="block text-xs font-semibold text-gray-700 mb-1">COD Cash to Collect (Rs.)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    id="cod_amount"
                                    name="cod_amount"
                                    value="{{ $order->payment_method === 'cod' ? $order->total_amount : '0' }}"
                                    class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500"
                                >
                            </div>

                            <div>
                                <label for="advance_order_status" class="block text-xs font-semibold text-gray-700 mb-1">Advance Order Status</label>
                                <select id="advance_order_status" name="advance_order_status" class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500">
                                    <option value="shipped" selected>Advance to Shipped</option>
                                    <option value="packed">Advance to Packed</option>
                                    <option value="processing">Keep as Processing</option>
                                </select>
                            </div>

                            <div>
                                <label for="notes" class="block text-xs font-semibold text-gray-700 mb-1">Packing / Delivery Notes</label>
                                <input
                                    type="text"
                                    id="notes"
                                    name="notes"
                                    placeholder="e.g. Fragile, call customer before delivery"
                                    class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500"
                                >
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button
                                type="submit"
                                class="px-5 py-2.5 rounded-lg text-white font-bold text-xs shadow-sm hover:opacity-95"
                                style="background-color: #0F1B4D;"
                            >
                                Book Courier Shipment & Create Label →
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            {{-- Ordered Items Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-900" style="color: #0F1B4D;">
                        Order Items ({{ $order->items->count() }})
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase font-semibold">
                            <tr>
                                <th class="py-3 px-4">Item</th>
                                <th class="py-3 px-4">Unit Price</th>
                                <th class="py-3 px-4">Qty</th>
                                <th class="py-3 px-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-gray-50 border border-gray-100 p-1 flex items-center justify-center flex-shrink-0">
                                                <img src="{{ $item->product?->images->first()?->image_url ?? 'https://placehold.co/40x40' }}" alt="" class="w-full h-full object-contain">
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900">{{ $item->product_name }}</div>
                                                <div class="text-[10px] text-gray-400 font-mono">{{ $item->product_sku }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-gray-700">
                                        Rs. {{ number_format($item->unit_price) }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 font-bold">
                                        × {{ $item->quantity }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-black text-gray-900">
                                        Rs. {{ number_format($item->total_price) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50/50 border-t border-gray-100 font-semibold text-xs">
                            <tr>
                                <td colspan="3" class="py-2.5 px-4 text-right text-gray-500">Subtotal:</td>
                                <td class="py-2.5 px-4 text-right text-gray-900">Rs. {{ number_format($order->subtotal) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="py-2.5 px-4 text-right text-gray-500">Delivery Shipping:</td>
                                <td class="py-2.5 px-4 text-right text-gray-900">
                                    {{ $order->shipping_amount > 0 ? 'Rs. ' . number_format($order->shipping_amount) : 'FREE' }}
                                </td>
                            </tr>
                            <tr class="border-t border-gray-200 text-sm font-black">
                                <td colspan="3" class="py-3 px-4 text-right text-gray-900">Total Charged:</td>
                                <td class="py-3 px-4 text-right text-teal-600">Rs. {{ number_format($order->total_amount) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Audit Trail / Status History --}}
            @if($order->statusHistories->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 mb-4" style="color: #0F1B4D;">
                        Status Audit Trail
                    </h2>
                    <div class="space-y-3">
                        @foreach($order->statusHistories as $h)
                            <div class="p-3 rounded-xl bg-gray-50 flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-gray-800">{{ $h->reason ?? ucfirst($h->to_status) }}</span>
                                    <span class="text-[11px] text-gray-400 block mt-0.5">By: {{ $h->changer?->name ?? 'System' }}</span>
                                </div>
                                <span class="text-gray-400 text-[11px]">{{ $h->created_at ? \Illuminate\Support\Carbon::parse($h->created_at)->format('M d, Y - h:i A') : '' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Right 1 col: Customer Info & Shipping Address --}}
        <div class="space-y-6">

            {{-- Customer Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm text-xs space-y-4">
                <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100" style="color: #0F1B4D;">Customer Information</h2>
                <div>
                    <span class="text-gray-400 block">Name:</span>
                    <span class="font-bold text-gray-900 text-sm">{{ $order->customer_name ?? $order->user?->name ?? 'Guest' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block">Email:</span>
                    <a href="mailto:{{ $order->email }}" class="font-medium text-teal-600 hover:underline">{{ $order->email }}</a>
                </div>
                <div>
                    <span class="text-gray-400 block">Phone:</span>
                    <a href="tel:{{ $order->phone }}" class="font-medium text-teal-600 hover:underline">{{ $order->phone }}</a>
                </div>
                @if($order->customer_notes)
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-100 text-amber-900">
                        <span class="font-bold block mb-0.5">Customer Delivery Note:</span>
                        {{ $order->customer_notes }}
                    </div>
                @endif
            </div>

            {{-- Shipping Address Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm text-xs space-y-3">
                <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100" style="color: #0F1B4D;">Shipping Address</h2>
                @if($order->shippingAddress)
                    <p class="text-gray-700 leading-relaxed">
                        <strong class="text-gray-900">{{ $order->shippingAddress->full_name }}</strong><br>
                        {{ $order->shippingAddress->street_address }}<br>
                        @if($order->shippingAddress->area)
                            {{ $order->shippingAddress->area }}<br>
                        @endif
                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }}<br>
                        @if($order->shippingAddress->postal_code)
                            Postal Code: {{ $order->shippingAddress->postal_code }}<br>
                        @endif
                        Phone: {{ $order->shippingAddress->phone }}
                    </p>
                @else
                    <p class="text-gray-400">Direct courier shipping details recorded via phone.</p>
                @endif
            </div>

            {{-- Payment Card & Bank Verification --}}
            @php $payment = $order->payment; @endphp
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm text-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-900" style="color: #0F1B4D;">Payment & Accounting</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ ($order->payment_status === 'paid') ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Method:</span>
                        <span class="font-bold text-gray-900 uppercase">{{ $order->payment_method ?? 'COD' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Amount Charged:</span>
                        <span class="font-black text-gray-900">Rs. {{ number_format($order->total_amount) }}</span>
                    </div>

                    @if($payment)
                        @if($payment->transaction_reference)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Transaction Ref:</span>
                                <span class="font-mono font-bold text-teal-800">{{ $payment->transaction_reference }}</span>
                            </div>
                        @endif

                        @if($payment->bank_name)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Bank Name:</span>
                                <span class="font-medium text-gray-800">{{ $payment->bank_name }}</span>
                            </div>
                        @endif

                        @if($payment->sender_account_or_phone)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Sender Info:</span>
                                <span class="font-mono text-gray-800">{{ $payment->sender_account_or_phone }}</span>
                            </div>
                        @endif

                        @if($payment->verified_by)
                            <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-100 text-[11px] text-emerald-800">
                                <strong>Verified By:</strong> {{ $payment->verifiedBy?->name ?? 'Admin' }}<br>
                                <span class="text-gray-500">{{ $payment->verified_at?->format('M d, Y - h:i A') }}</span>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Bank Transfer Manual Verification Action Box --}}
                @if($order->payment_method === 'bank_transfer' && $order->payment_status !== 'paid' && $payment)
                    <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200 space-y-3">
                        <div class="font-bold text-amber-900 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            Verify Bank Transfer
                        </div>
                        <p class="text-[11px] text-amber-800 leading-relaxed">
                            Check ShopPulss bank account statement for Ref: <strong class="font-mono">{{ $payment->transaction_reference ?? 'None' }}</strong> (Rs. {{ number_format($order->total_amount) }}).
                        </p>

                        <div class="flex flex-col gap-2 pt-1">
                            <form method="POST" action="{{ route('admin.payments.verify', $payment->id) }}">
                                @csrf
                                <input type="hidden" name="notes" value="Verified in bank statement. Amount received.">
                                <button
                                    type="submit"
                                    class="w-full py-2 rounded-lg text-white font-bold text-xs shadow-sm hover:opacity-95 transition-all text-center"
                                    style="background-color: #00A8B8;"
                                >
                                    ✓ Approve & Mark as Paid
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.payments.reject', $payment->id) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full py-1.5 rounded-lg text-red-600 font-semibold text-xs border border-red-200 hover:bg-red-50 transition-colors text-center"
                                    onclick="return confirm('Are you sure you want to mark this payment as failed?')"
                                >
                                    ✗ Reject Payment (Not Received)
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
