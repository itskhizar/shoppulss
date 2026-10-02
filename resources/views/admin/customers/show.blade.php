@extends('layouts.admin')

@section('title', 'Customer - ' . $customer->name)
@section('header', 'Customer Profile')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.customers.index') }}" class="text-xs text-gray-500 hover:text-gray-900">← Back to Customers</a>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-mono text-gray-500">ID: #{{ $customer->id }}</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 mt-1" style="color: #0F1B4D;">{{ $customer->name }}</h1>
            <p class="text-xs text-gray-500">Member since {{ $customer->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 cols: Orders --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-sm text-gray-900" style="color: #0F1B4D;">
                            Order History ({{ $orders->total() }})
                        </h2>
                        <span class="text-xs font-bold text-teal-600">Total Spent: Rs. {{ number_format($customer->orders_sum_total_amount ?? 0) }}</span>
                    </div>

                    {{-- Order Search & Filter Form --}}
                    <form method="GET" action="{{ route('admin.customers.show', $customer->id) }}" class="flex items-center gap-2">
                        <input
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Search Order #..."
                            class="h-9 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500 w-36 sm:w-44"
                        >
                        <select name="status" class="h-9 px-2.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="h-9 px-3 rounded-lg bg-teal-600 text-white font-semibold text-xs hover:bg-teal-700">Filter</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="py-3 px-4">Order #</th>
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4">Items</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right">Total</th>
                                <th class="py-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($orders as $ord)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="py-3 px-4 font-mono font-bold text-gray-900">
                                        {{ $ord->order_number }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-500">
                                        {{ $ord->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ $ord->items->count() }} items
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $ord->status_color ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($ord->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right font-black text-gray-900">
                                        Rs. {{ number_format($ord->total_amount) }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('admin.orders.show', $ord->id) }}" class="text-teal-600 hover:text-teal-800 font-bold">
                                            View →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-gray-400">No matching orders found.</td>
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

        {{-- Right 1 col: Contact & Addresses --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm text-xs space-y-4">
                <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100" style="color: #0F1B4D;">Customer Details</h2>
                <div>
                    <span class="text-gray-400 block">Email:</span>
                    <a href="mailto:{{ $customer->email }}" class="font-medium text-teal-600 hover:underline">{{ $customer->email }}</a>
                </div>
                <div>
                    <span class="text-gray-400 block">Phone:</span>
                    <a href="tel:{{ $customer->phone }}" class="font-medium text-teal-600 hover:underline">{{ $customer->phone ?? 'Not provided' }}</a>
                </div>
                <div>
                    <span class="text-gray-400 block">Account Status:</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $customer->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($customer->status ?? 'active') }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm text-xs space-y-3">
                <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100" style="color: #0F1B4D;">Saved Addresses</h2>
                @forelse($customer->addresses as $addr)
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 leading-relaxed text-gray-700">
                        <strong class="text-gray-900">{{ $addr->full_name }}</strong><br>
                        {{ $addr->street_address }}<br>
                        {{ $addr->city }}, {{ $addr->province }}<br>
                        Phone: {{ $addr->phone }}
                    </div>
                @empty
                    <p class="text-gray-400">No saved addresses on file.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
