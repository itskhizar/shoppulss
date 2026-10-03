@extends('layouts.app')

@section('title', 'My Account - ShopPulss')
@section('description', 'Manage your ShopPulss account, track orders, and update your profile.')
@section('keywords', 'online shopping Pakistan, Cash on Delivery, ShopPulss, direct retail')

@section('content')
<div class="bg-white border-b border-[#E6E8F2] py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="breadcrumb mb-2">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span class="current">My Account</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-[#0F1654] tracking-tight">
            Customer Dashboard
        </h1>
        <p class="text-xs text-gray-500 mt-1">Welcome back, <strong class="text-[#0F1654]">{{ $user->name }}</strong>! Manage your orders, tracking, and profile.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            @include('account.partials.sidebar')
        </div>

        {{-- Main Area --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-5 shadow-sp-card card-hover-lift">
                    <span class="text-xs font-bold text-gray-400 block mb-1">Total Orders</span>
                    <span class="text-3xl font-black text-[#0F1654]">{{ $totalOrders }}</span>
                    <span class="text-[11px] text-[#0AA6B7] font-semibold block mt-1">Verified direct retail</span>
                </div>
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-5 shadow-sp-card card-hover-lift">
                    <span class="text-xs font-bold text-gray-400 block mb-1">Default Shipping City</span>
                    <span class="text-sm font-black text-[#0F1654] line-clamp-1">
                        {{ $defaultAddress ? $defaultAddress->city . ', ' . $defaultAddress->province : 'Not added yet' }}
                    </span>
                    <span class="text-[11px] text-gray-400 block mt-1">Primary delivery destination</span>
                </div>
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-5 shadow-sp-card card-hover-lift">
                    <span class="text-xs font-bold text-gray-400 block mb-1">Member Since</span>
                    <span class="text-sm font-black text-[#0F1654]">{{ $user->created_at->format('M Y') }}</span>
                    <span class="text-[11px] text-[#FF5A1F] font-bold block mt-1">ShopPulss Family</span>
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 sm:p-7 shadow-sp-card">
                <div class="flex items-center justify-between pb-4 border-b border-[#E6E8F2] mb-5">
                    <div>
                        <h2 class="text-base font-black text-[#0F1654]">Recent Orders</h2>
                        <p class="text-xs text-gray-400">Your latest purchases and their fulfillment status</p>
                    </div>
                    <a href="{{ route('account.orders') }}" class="view-all-link text-xs">
                        <span>View All Orders</span>
                        <span>â†’</span>
                    </a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="text-center py-12 text-xs text-gray-500">
                        <div class="w-14 h-14 rounded-2xl bg-[#FFF1EA] text-[#FF5A1F] flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <p class="font-bold text-gray-800 text-sm mb-1">No orders yet</p>
                        <p class="mb-4">You have not placed any direct retail orders with ShopPulss yet.</p>
                        <a href="{{ route('products.index') }}" class="btn-primary">Start Shopping</a>
                    </div>
                @else
                    <div class="divide-y divide-[#E6E8F2] text-xs">
                        @foreach($recentOrders as $order)
                            <div class="py-4 flex items-center justify-between flex-wrap gap-3">
                                <div>
                                    <div class="font-black text-[#0F1654] font-mono text-sm">{{ $order->order_number }}</div>
                                    <div class="text-gray-400 text-[11px] mt-0.5">{{ $order->created_at->format('M d, Y') }} â€¢ {{ $order->items->count() }} items</div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="badge-navy uppercase">
                                        {{ $order->status }}
                                    </span>
                                    <span class="font-black text-[#0F1654] text-sm">Rs. {{ number_format($order->total_amount) }}</span>
                                    <a href="{{ route('account.orders.show', $order->order_number) }}" class="btn-secondary py-1.5 px-3.5 text-xs">
                                        <span>Details</span>
                                        <span>â†’</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
