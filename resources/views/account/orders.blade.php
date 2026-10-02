@extends('layouts.app')

@section('title', 'My Orders - ShopPulss')

@section('content')
<div class="bg-white border-b border-[#E6E8F2] py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="breadcrumb mb-2">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('account.dashboard') }}">Account</a>
            <span>/</span>
            <span class="current">Orders</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-[#0F1654] tracking-tight">
            My Orders
        </h1>
        <p class="text-xs text-gray-500 mt-1">Track your order statuses, invoices, and shipment tracking across Pakistan</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        <div class="lg:col-span-1">
            @include('account.partials.sidebar')
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 sm:p-7 shadow-sp-card">
                @if($orders->isEmpty())
                    <div class="text-center py-12 text-xs text-gray-500">
                        <div class="w-14 h-14 rounded-2xl bg-[#FFF1EA] flex items-center justify-center mx-auto mb-3 text-[#FF5A1F]">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <p class="font-bold text-gray-800 text-sm mb-1">No orders found</p>
                        <p class="mb-4">You have not placed any orders with ShopPulss yet.</p>
                        <a href="{{ route('products.index') }}" class="btn-primary">Browse Catalog</a>
                    </div>
                @else
                    <div class="divide-y divide-[#E6E8F2] text-xs">
                        @foreach($orders as $order)
                            <div class="py-4 sm:py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-black text-sm text-[#0F1654] font-mono">{{ $order->order_number }}</span>
                                        <span class="badge-navy uppercase">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </div>
                                    <div class="text-gray-400 text-[11px]">
                                        Placed on {{ $order->created_at->format('M d, Y') }} • {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                                    </div>
                                    <div class="text-gray-600">
                                        Total: <strong class="text-[#0F1654] font-black">Rs. {{ number_format($order->total_amount) }}</strong>
                                        <span class="text-gray-400 font-medium">({{ ucfirst($order->payment_method ?? 'COD') }})</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a
                                        href="{{ route('account.orders.show', $order->order_number) }}"
                                        class="btn-secondary py-2 px-4 text-xs font-bold"
                                    >
                                        <span>View Details</span>
                                        <span>→</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-4 border-t border-[#E6E8F2]">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
