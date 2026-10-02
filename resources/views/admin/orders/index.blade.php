@extends('layouts.admin')

@section('title', 'Manage Orders')
@section('header', 'Orders Management')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <div>
        <h1 class="text-xl font-black text-gray-900" style="color: #0F1B4D;">Orders ({{ $orders->total() }})</h1>
        <p class="text-xs text-gray-500">Track and fulfill customer orders nationwide</p>
    </div>

    {{-- Status Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-semibold">
        @foreach(['all' => 'All Orders', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $key => $label)
            @php
                $isActive = (request('status') === $key) || (!$key && !request('status')) || ($key === 'all' && !request('status'));
                $cnt = $statusCounts[$key] ?? 0;
            @endphp
            <a
                href="{{ $key === 'all' ? route('admin.orders.index') : route('admin.orders.index', ['status' => $key]) }}"
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
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap items-center gap-3">
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
            
            {{-- Search input with properly centered icon --}}
            <div class="relative flex-1 min-w-[240px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search order #, customer, email, phone..."
                    class="w-full h-10 pl-10 pr-4 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs text-gray-800 placeholder-gray-400 transition-colors focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8]"
                >
            </div>

            {{-- Payment Status Filter --}}
            <select name="payment_status" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[140px] cursor-pointer" onchange="this.form.submit()">
                <option value="">Payment: All</option>
                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>

            {{-- Date Filter --}}
            <input
                type="date"
                name="from_date"
                value="{{ request('from_date') }}"
                title="From Date"
                class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[140px]"
            >

            {{-- Sort Filter --}}
            <select name="sort" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[140px] cursor-pointer" onchange="this.form.submit()">
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Sort: Newest</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Sort: Oldest</option>
                <option value="total_high" {{ request('sort') === 'total_high' ? 'selected' : '' }}>Sort: Total High</option>
                <option value="total_low" {{ request('sort') === 'total_low' ? 'selected' : '' }}>Sort: Total Low</option>
            </select>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">
                <button
                    type="submit"
                    class="h-10 px-4 rounded-xl text-white font-semibold text-xs shadow-xs hover:opacity-95 transition-opacity inline-flex items-center gap-1.5"
                    style="background-color: #00A8B8;"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['q', 'payment_status', 'from_date', 'to_date', 'sort']))
                    <a
                        href="{{ request('status') ? route('admin.orders.index', ['status' => request('status')]) : route('admin.orders.index') }}"
                        class="h-10 px-3.5 flex items-center justify-center rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:text-red-600 hover:border-red-200 hover:bg-red-50/30 transition-colors whitespace-nowrap"
                        title="Reset Filters"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-4">Order ID</th>
                        <th class="py-3.5 px-4">Date</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Destination</th>
                        <th class="py-3.5 px-4">Amount</th>
                        <th class="py-3.5 px-4">Payment</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($orders as $o)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-gray-900">
                                {{ $o->order_number }}
                            </td>
                            <td class="py-3.5 px-4 text-gray-500 text-[11px]">
                                {{ $o->created_at->format('M d, Y') }}<br>
                                <span class="text-gray-400">{{ $o->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-900">{{ $o->user?->name ?? $o->email }}</div>
                                <div class="text-[10px] text-gray-400">{{ $o->phone }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-gray-600">
                                {{ $o->shippingAddress?->city ?? 'N/A' }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-gray-900">
                                Rs. {{ number_format($o->total_amount) }}
                                <span class="text-[10px] text-gray-400 block font-normal">({{ $o->items->count() }} items)</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-[10px] font-semibold text-gray-700 uppercase block">{{ $o->payment_method ?? 'COD' }}</span>
                                <span class="text-[10px] font-bold {{ $o->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ ucfirst($o->payment_status) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                @php
                                    $colors = [
                                        'pending'    => 'bg-amber-100 text-amber-800',
                                        'processing' => 'bg-blue-100 text-blue-800',
                                        'shipped'    => 'bg-indigo-100 text-indigo-800',
                                        'delivered'  => 'bg-emerald-100 text-emerald-800',
                                        'cancelled'  => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $colors[$o->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $o->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a
                                    href="{{ route('admin.orders.show', $o->id) }}"
                                    class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold text-xs transition-colors"
                                >
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-xs text-gray-400">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
