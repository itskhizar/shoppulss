@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Top Action Bar --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900" style="color: #0F1B4D;">Store Overview</h1>
            <p class="text-xs text-gray-500 mt-0.5">Real-time metrics, fulfillment queue, and catalog health</p>
        </div>

        <div class="flex items-center gap-3">
            @if(Auth::user()->canManageCatalog())
                <a
                    href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white font-semibold text-xs shadow-sm hover:opacity-95 transition-all"
                    style="background-color: #00A8B8;"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add New Product
                </a>
            @endif
            @if(Auth::user()->canManageOrders())
                <a
                    href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-gray-700 bg-white border border-gray-200 font-semibold text-xs hover:bg-gray-50 transition-colors"
                >
                    Manage Orders
                </a>
            @endif
        </div>
    </div>

    {{-- Today's Metrics Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Today's Orders --}}
        @if(Auth::user()->canManageOrders())
        <a href="{{ route('admin.orders.index') }}" class="block bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider group-hover:text-blue-600 transition-colors">Today's Orders</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900" style="color: #0F1B4D;">{{ $todaysOrders }}</div>
                <div class="text-[11px] text-gray-400 font-medium mt-1">Total Lifetime: {{ $totalOrders }}</div>
            </div>
        </a>
        @else
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Today's Orders</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black" style="color: #0F1B4D;">{{ $todaysOrders }}</div>
                <div class="text-[11px] text-gray-400 font-medium mt-1">Total Lifetime: {{ $totalOrders }}</div>
            </div>
        </div>
        @endif

        {{-- Pending Review --}}
        @if(Auth::user()->canManageOrders())
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="block bg-white rounded-2xl border {{ $pendingReviewCount > 0 ? 'border-amber-200 bg-amber-50/20' : 'border-gray-100' }} p-6 shadow-sm hover:shadow-md hover:border-amber-300 transition-all group">
        @else
        <div class="bg-white rounded-2xl border {{ $pendingReviewCount > 0 ? 'border-amber-200 bg-amber-50/20' : 'border-gray-100' }} p-6 shadow-sm">
        @endif
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Pending Review</span>
                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center text-amber-700 font-bold text-xs group-hover:scale-105 transition-transform">!</div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-amber-900">{{ $pendingReviewCount }}</div>
                <div class="text-[11px] text-amber-600 font-medium mt-1">
                    {{ $pendingReviewCount > 0 ? 'Needs action first →' : 'All caught up ✓' }}
                </div>
            </div>
        @if(Auth::user()->canManageOrders())
        </a>
        @else
        </div>
        @endif

        {{-- Low Stock Items --}}
        @if(Auth::user()->canManageCatalog())
        <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="block bg-white rounded-2xl border {{ $lowStockCount > 0 ? 'border-red-200 bg-red-50/20' : 'border-gray-100' }} p-6 shadow-sm hover:shadow-md hover:border-red-300 transition-all group">
        @else
        <div class="bg-white rounded-2xl border {{ $lowStockCount > 0 ? 'border-red-200 bg-red-50/20' : 'border-gray-100' }} p-6 shadow-sm">
        @endif
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-red-600 uppercase tracking-wider">Low Stock Items</span>
                <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center text-red-600 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-red-900">{{ $lowStockCount }}</div>
                <div class="text-[11px] text-red-500 font-medium mt-1">Under threshold →</div>
            </div>
        @if(Auth::user()->canManageCatalog())
        </a>
        @else
        </div>
        @endif

        {{-- Revenue Today --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue Today</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-gray-900" style="color: #0F1B4D;">Rs. {{ number_format($todaysRevenue) }}</div>
                <div class="text-[11px] text-emerald-600 font-medium mt-1">Total: Rs. {{ number_format($totalSales) }}</div>
            </div>
        </div>
    </div>

    {{-- Secondary Catalog & Customer Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @if(Auth::user()->canManageCatalog())
        <a href="{{ route('admin.products.index') }}" class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:border-teal-300 transition-all flex items-center justify-between group">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Products</span>
                <span class="text-xl font-black text-gray-900 mt-1 block">{{ $totalProducts }}</span>
            </div>
            <span class="text-teal-600 text-xs font-bold group-hover:translate-x-0.5 transition-transform">→</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:border-teal-300 transition-all flex items-center justify-between group">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Categories</span>
                <span class="text-xl font-black text-gray-900 mt-1 block">{{ $totalCategories }}</span>
            </div>
            <span class="text-teal-600 text-xs font-bold group-hover:translate-x-0.5 transition-transform">→</span>
        </a>
        @else
        <div class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Products</span>
                <span class="text-xl font-black text-gray-900 mt-1 block">{{ $totalProducts }}</span>
            </div>
        </div>
        <div class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Categories</span>
                <span class="text-xl font-black text-gray-900 mt-1 block">{{ $totalCategories }}</span>
            </div>
        </div>
        @endif

        @if(Auth::user()->canManageCustomers())
        <a href="{{ route('admin.customers.index') }}" class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:border-teal-300 transition-all flex items-center justify-between group">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Customers</span>
                <span class="text-xl font-black text-gray-900 mt-1 block">{{ $totalCustomers }}</span>
            </div>
            <span class="text-teal-600 text-xs font-bold group-hover:translate-x-0.5 transition-transform">→</span>
        </a>
        @else
        <div class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Customers</span>
                <span class="text-xl font-black text-gray-900 mt-1 block">{{ $totalCustomers }}</span>
            </div>
        </div>
        @endif

        @if(Auth::user()->canManageOrders())
        <a href="{{ route('admin.orders.index') }}" class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:border-teal-300 transition-all flex items-center justify-between group">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Orders</span>
                <span class="text-xl font-black text-gray-900 mt-1 block">{{ $totalOrders }}</span>
            </div>
            <span class="text-teal-600 text-xs font-bold group-hover:translate-x-0.5 transition-transform">→</span>
        </a>
        @else
        <div class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Orders</span>
                <span class="text-xl font-black text-gray-900 mt-1 block">{{ $totalOrders }}</span>
            </div>
        </div>
        @endif
    </div>

    {{-- Order Fulfillment Status Pipeline --}}
    @if(Auth::user()->canManageOrders())
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Order Status Pipeline</h3>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center">
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="p-3 rounded-xl bg-amber-50 hover:bg-amber-100 transition-colors border border-amber-100">
                <span class="text-lg font-black text-amber-800 block">{{ $pendingCount }}</span>
                <span class="text-[11px] font-semibold text-amber-700">Pending</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="p-3 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors border border-blue-100">
                <span class="text-lg font-black text-blue-800 block">{{ $processingCount }}</span>
                <span class="text-[11px] font-semibold text-blue-700">Processing</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="p-3 rounded-xl bg-cyan-50 hover:bg-cyan-100 transition-colors border border-cyan-100">
                <span class="text-lg font-black text-cyan-800 block">{{ $shippedCount }}</span>
                <span class="text-[11px] font-semibold text-cyan-700">Shipped</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="p-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 transition-colors border border-emerald-100">
                <span class="text-lg font-black text-emerald-800 block">{{ $deliveredCount }}</span>
                <span class="text-[11px] font-semibold text-emerald-700">Delivered</span>
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="p-3 rounded-xl bg-red-50 hover:bg-red-100 transition-colors border border-red-100">
                <span class="text-lg font-black text-red-800 block">{{ $cancelledCount }}</span>
                <span class="text-[11px] font-semibold text-red-700">Cancelled</span>
            </a>
        </div>
    </div>
    @endif

    {{-- Main 2-column: Recent Orders & Right sidebar --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Recent Orders Table --}}
        @if(Auth::user()->canManageOrders())
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-base text-gray-900" style="color: #0F1B4D;">Recent Orders</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Pending and confirmed orders prioritized first</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">View All →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="py-3.5 px-4">Order ID</th>
                            <th class="py-3.5 px-4">Customer</th>
                            <th class="py-3.5 px-4">Total</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($recentOrders as $o)
                            <tr class="hover:bg-gray-50/60 transition-colors {{ in_array($o->status, ['pending', 'confirmed']) ? 'bg-amber-50/20' : '' }}">
                                <td class="py-3 px-4 font-mono font-bold text-gray-900">{{ $o->order_number }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-gray-800">{{ $o->customer_name ?? $o->user?->name ?? $o->email }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $o->phone }}</div>
                                </td>
                                <td class="py-3 px-4 font-bold text-gray-900">Rs. {{ number_format($o->total_amount) }}</td>
                                <td class="py-3 px-4">
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
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $colors[$o->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $o->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('admin.orders.show', $o->id) }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">
                                        Manage →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-xs text-gray-400">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        {{-- Catalog summary for non-order managers --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-8 flex items-center justify-center">
            <div class="text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="text-sm font-semibold text-gray-500">Order details visible to Order Managers only</p>
                <p class="text-xs mt-1">Use the catalog modules to manage products & categories</p>
            </div>
        </div>
        @endif

        {{-- Right Col: Low Stock Alerts + Recent Customers --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Low Stock Alerts List --}}
            @if(Auth::user()->canManageCatalog())
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h2 class="font-bold text-base text-gray-900" style="color: #0F1B4D;">Low Stock Alerts</h2>
                    <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">All Products →</a>
                </div>

                <div class="space-y-3">
                    @forelse($lowStockProducts as $prod)
                        <div class="flex items-center gap-3 text-xs p-2 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 p-1 flex items-center justify-center flex-shrink-0">
                                <img src="{{ $prod->images->first()?->image_url ?? 'https://placehold.co/40x40' }}" alt="" class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-gray-900 truncate">{{ $prod->name }}</div>
                                <div class="text-[11px] text-red-600 font-semibold">Only {{ $prod->stock_quantity }} left!</div>
                            </div>
                            <a href="{{ route('admin.products.edit', $prod->id) }}" class="text-teal-600 font-bold hover:underline text-xs flex-shrink-0">Restock</a>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 py-4 text-center">All products are well stocked.</p>
                    @endforelse
                </div>
            </div>
            @endif

            {{-- Recent Customers List --}}
            @if(Auth::user()->canManageCustomers())
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h2 class="font-bold text-base text-gray-900" style="color: #0F1B4D;">Recent Customers</h2>
                    <a href="{{ route('admin.customers.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">View All →</a>
                </div>

                <div class="space-y-3">
                    @forelse($recentCustomers as $customer)
                        <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="min-w-0">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="font-bold text-gray-900 hover:text-teal-600 truncate block">{{ $customer->name }}</a>
                                <span class="text-[11px] text-gray-400 block truncate">{{ $customer->email }}</span>
                            </div>
                            <div class="text-right flex-shrink-0 ml-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700">
                                    {{ $customer->orders_count }} {{ Str::plural('order', $customer->orders_count) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 py-4 text-center">No registered customers yet.</p>
                    @endforelse
                </div>
            </div>
            @endif

            {{-- If no catalog or customer access, show a simple info card --}}
            @if(!Auth::user()->canManageCatalog() && !Auth::user()->canManageCustomers())
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm text-center text-gray-400">
                <p class="text-sm font-semibold">Welcome, {{ Auth::user()->name }}</p>
                <p class="text-xs mt-1">Use the sidebar to access your modules</p>
            </div>
            @endif
        </div>

    </div>

    {{-- Charts Row --}}
    @if(Auth::user()->canManageOrders())
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Sales Trend Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-bold text-base text-gray-900" style="color: #0F1B4D;">Sales & Orders Trend</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Revenue and order volume over time</p>
                </div>
                <div class="flex gap-1.5">
                    @foreach(['today' => 'Today', '7d' => '7D', '30d' => '30D', '3m' => '3M', '12m' => '12M'] as $key => $label)
                        <a href="{{ route('admin.dashboard', ['period' => $key]) }}"
                           class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition-colors {{ $chartPeriod === $key ? 'bg-[#0F1B4D] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="relative h-56">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        {{-- Order Distribution Doughnut --}}
        <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-base text-gray-900 mb-4" style="color: #0F1B4D;">Order Distribution</h2>
            <div class="relative h-44 flex items-center justify-center">
                <canvas id="orderDistChart"></canvas>
            </div>
            <div class="mt-4 space-y-1.5">
                @php
                    $distColors = ['Pending' => '#F59E0B', 'Processing' => '#3B82F6', 'Shipped' => '#06B6D4', 'Delivered' => '#10B981', 'Cancelled' => '#EF4444'];
                @endphp
                @foreach($orderDistribution as $label => $count)
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $distColors[$label] ?? '#9CA3AF' }}"></span>
                            <span class="text-gray-600 font-medium">{{ $label }}</span>
                        </div>
                        <span class="font-black text-gray-900">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Top Selling Products Table --}}
    @if($topProducts->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-base text-gray-900" style="color: #0F1B4D;">Top Selling Products</h2>
                <p class="text-xs text-gray-500 mt-0.5">By units sold, excluding cancelled orders</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">All Products →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-600">
                <thead class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-4">#</th>
                        <th class="py-3.5 px-4">Product</th>
                        <th class="py-3.5 px-4 text-right">Units Sold</th>
                        <th class="py-3.5 px-4 text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @foreach($topProducts as $i => $tp)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-3 px-4 font-black text-gray-400">{{ $i + 1 }}</td>
                            <td class="py-3 px-4">
                                @if($tp->product_id)
                                    <a href="{{ route('admin.products.show', $tp->product_id) }}" class="font-bold text-gray-900 hover:text-teal-600">{{ $tp->product_name }}</a>
                                @else
                                    <span class="font-bold text-gray-900">{{ $tp->product_name }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right font-black text-gray-900">{{ number_format($tp->total_qty) }}</td>
                            <td class="py-3 px-4 text-right font-black text-emerald-700">Rs. {{ number_format($tp->total_revenue) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    // Sales & Orders Trend Chart
    const trendCtx = document.getElementById('salesTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Revenue (Rs.)',
                        data: @json($chartData['revenue']),
                        backgroundColor: 'rgba(0, 168, 184, 0.15)',
                        borderColor: '#00A8B8',
                        borderWidth: 2,
                        borderRadius: 6,
                        type: 'bar',
                        yAxisID: 'y',
                    },
                    {
                        label: 'Orders',
                        data: @json($chartData['orders']),
                        borderColor: '#0F1B4D',
                        backgroundColor: 'rgba(15, 27, 77, 0.08)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: false,
                        type: 'line',
                        yAxisID: 'y1',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { font: { size: 11, weight: '600' }, usePointStyle: true, padding: 16 }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                if (ctx.dataset.label === 'Revenue (Rs.)') {
                                    return ' Rs. ' + ctx.parsed.y.toLocaleString();
                                }
                                return ' ' + ctx.parsed.y + ' orders';
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 }, maxRotation: 45 } },
                    y: {
                        position: 'left',
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: { font: { size: 10 }, callback: v => 'Rs. ' + (v >= 1000 ? (v/1000).toFixed(0)+'K' : v) }
                    },
                    y1: {
                        position: 'right',
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }

    // Order Distribution Doughnut
    const distCtx = document.getElementById('orderDistChart');
    if (distCtx) {
        const distData = @json($orderDistribution);
        const distLabels = Object.keys(distData);
        const distValues = Object.values(distData);
        const distColors = ['#F59E0B', '#3B82F6', '#06B6D4', '#10B981', '#EF4444'];

        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: distLabels,
                datasets: [{
                    data: distValues,
                    backgroundColor: distColors,
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ' ' + ctx.label + ': ' + ctx.parsed
                        }
                    }
                }
            }
        });
    }
})();
</script>
@endpush
