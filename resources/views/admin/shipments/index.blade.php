@extends('layouts.admin')

@section('title', 'Courier Shipments & Delivery')
@section('header', 'Shipments & Delivery')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-gray-900" style="color: #0F1B4D;">Courier Shipments ({{ $shipments->total() }})</h1>
            <p class="text-xs text-gray-500">Track all nationwide parcels dispatched via Leopards, TCS, Trax, and direct riders</p>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-semibold">
        @foreach(['all' => 'All Shipments', 'booked' => 'Booked', 'picked_up' => 'Picked Up', 'in_transit' => 'In Transit', 'out_for_delivery' => 'Out for Delivery', 'delivered' => 'Delivered', 'failed' => 'Failed / Returned'] as $key => $label)
            @php
                $isActive = (request('status') === $key) || (!$key && !request('status')) || ($key === 'all' && !request('status'));
                $cnt = $statusCounts[$key] ?? 0;
            @endphp
            <a
                href="{{ $key === 'all' ? route('admin.shipments.index') : route('admin.shipments.index', ['status' => $key]) }}"
                class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl transition-colors whitespace-nowrap {{ $isActive ? 'bg-[#0F1B4D] text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}"
            >
                <span>{{ $label }}</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">
                    {{ $cnt }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Search & Filter Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.shipments.index') }}" class="flex flex-wrap items-center gap-3">
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif

            <div class="relative flex-1 min-w-[240px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search tracking #, order #, customer, phone..."
                    class="w-full h-10 pl-10 pr-4 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs text-gray-800 placeholder-gray-400 transition-colors focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8]"
                >
            </div>

            <select name="courier_id" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] cursor-pointer" onchange="this.form.submit()">
                <option value="">Courier: All</option>
                @foreach($couriers as $c)
                    <option value="{{ $c->id }}" {{ request('courier_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="h-10 px-5 bg-[#00A8B8] text-white rounded-xl text-xs font-semibold hover:opacity-95 shadow-sm transition-all">
                Filter
            </button>
            @if(request()->hasAny(['q', 'status', 'courier_id']))
                <a href="{{ route('admin.shipments.index') }}" class="h-10 px-3 flex items-center justify-center text-xs text-gray-500 hover:text-gray-800">Clear</a>
            @endif
        </form>
    </div>

    {{-- Shipments Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/75 border-b border-gray-100 text-gray-500 uppercase font-semibold">
                    <tr>
                        <th class="py-3 px-4">Tracking # / Courier</th>
                        <th class="py-3 px-4">Order #</th>
                        <th class="py-3 px-4">Consignee & Destination</th>
                        <th class="py-3 px-4">Weight / COD</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Booked Date</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($shipments as $s)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-900 font-mono text-sm text-teal-800">{{ $s->tracking_number }}</div>
                                <div class="text-[11px] text-gray-500 font-medium">🚚 {{ $s->courier?->name ?? 'Courier' }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-gray-800">
                                <a href="{{ route('admin.orders.show', $s->order_id) }}" class="text-teal-600 hover:underline">
                                    {{ $s->order?->order_number }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-900">{{ $s->consignee_name }}</div>
                                <div class="text-[11px] text-gray-500">📍 {{ $s->destination_city }} ({{ $s->consignee_phone }})</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-800">{{ $s->weight }} KG</div>
                                <div class="text-[11px] font-semibold {{ $s->cod_amount > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                                    {{ $s->cod_amount > 0 ? 'COD: Rs. '.number_format($s->cod_amount) : 'Prepaid' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200">
                                    {{ $s->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-gray-500">
                                {{ $s->created_at->format('M d, Y') }}<br>
                                <span class="text-[10px] text-gray-400">{{ $s->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a
                                        href="{{ route('admin.shipments.label', $s->id) }}"
                                        target="_blank"
                                        title="Print Shipping Label"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-[#0F1B4D] hover:text-white transition-colors"
                                    >
                                        🖨 Label
                                    </a>
                                    <a
                                        href="{{ route('admin.orders.show', $s->order_id) }}"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-teal-50 text-teal-700 hover:bg-teal-600 hover:text-white transition-colors"
                                    >
                                        Manage
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400 text-xs">
                                No shipments matching the current criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($shipments->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
