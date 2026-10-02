@extends('layouts.admin')

@section('title', 'Manage Customers')
@section('header', 'Retail Customers')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-xl font-black text-gray-900" style="color: #0F1B4D;">Retail Customers ({{ $customers->total() }})</h1>
            <p class="text-xs text-gray-500">Registered shoppers — excluding admin and management accounts</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-wrap items-center gap-3">
            {{-- Search input with properly centered icon --}}
            <div class="relative flex-1 min-w-[240px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search by customer name, email or phone..."
                    class="w-full h-10 pl-10 pr-4 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs text-gray-800 placeholder-gray-400 transition-colors focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8]"
                >
            </div>

            {{-- Status filter --}}
            <select name="status" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[130px] cursor-pointer" onchange="this.form.submit()">
                <option value="">Status: All</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            {{-- Sort --}}
            <select name="sort" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[150px] cursor-pointer" onchange="this.form.submit()">
                <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Sort: Newest</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Sort: Oldest</option>
                <option value="orders_high" {{ request('sort') === 'orders_high' ? 'selected' : '' }}>Most Orders</option>
                <option value="spent_high" {{ request('sort') === 'spent_high' ? 'selected' : '' }}>Highest Spend</option>
            </select>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">
                <button type="submit" class="h-10 px-4 rounded-xl text-white font-semibold text-xs shadow-xs hover:opacity-95 transition-opacity inline-flex items-center gap-1.5" style="background-color: #00A8B8;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['q', 'status', 'sort']))
                    <a href="{{ route('admin.customers.index') }}" class="h-10 px-3.5 flex items-center justify-center rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:text-red-600 hover:border-red-200 hover:bg-red-50/30 transition-colors whitespace-nowrap" title="Reset Filters">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Customers Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4">Phone</th>
                        <th class="py-3.5 px-4">Joined</th>
                        <th class="py-3.5 px-4">Orders</th>
                        <th class="py-3.5 px-4">Total Spent</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($customers as $c)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-400 to-blue-500 text-white font-black flex items-center justify-center text-xs flex-shrink-0">
                                        {{ strtoupper(substr($c->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.customers.show', $c->id) }}" class="font-bold text-gray-900 hover:text-teal-600 block">{{ $c->name }}</a>
                                        <div class="text-[11px] text-gray-400">{{ $c->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-gray-700">{{ $c->phone ?? '—' }}</td>
                            <td class="py-3.5 px-4 text-gray-500 text-[11px]">{{ $c->created_at->format('M d, Y') }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700">
                                    {{ $c->orders_count }} orders
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-black text-gray-900">
                                Rs. {{ number_format($c->orders_sum_total_amount ?? 0) }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ ($c->status ?? 'active') === 'active' ? 'bg-teal-100 text-teal-800' : 'bg-red-100 text-red-700' }}">
                                    {{ $c->status ?? 'active' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.customers.show', $c->id) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 font-bold text-xs transition-colors">
                                    View Profile →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="text-sm font-semibold">No customers found</p>
                                <p class="text-xs mt-1">Try adjusting your search or filter criteria</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $customers->links() }}
        </div>
    </div>

</div>
@endsection
