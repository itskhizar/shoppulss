@extends('layouts.app')

@section('title', 'My Account - ShopPulss')

@section('content')
<div class="bg-gray-50 py-6 border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        <h1 class="text-2xl font-black text-gray-900" style="color: #0F1B4D;">
            Customer Dashboard
        </h1>
        <p class="text-xs text-gray-500 mt-0.5">Welcome back, {{ $user->name }}!</p>
    </div>
</div>

<div class="max-w-screen-xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            @include('account.partials.sidebar')
        </div>

        {{-- Main Area --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <span class="text-xs text-gray-500 block mb-1">Total Orders</span>
                    <span class="text-2xl font-black text-gray-900" style="color: #0F1B4D;">{{ $totalOrders }}</span>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <span class="text-xs text-gray-500 block mb-1">Default Address</span>
                    <span class="text-xs font-semibold text-gray-800 line-clamp-2">
                        {{ $defaultAddress ? $defaultAddress->city . ', ' . $defaultAddress->province : 'None set yet' }}
                    </span>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <span class="text-xs text-gray-500 block mb-1">Member Since</span>
                    <span class="text-sm font-bold text-gray-800">{{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                    <h2 class="text-sm font-bold text-gray-900" style="color: #0F1B4D;">Recent Orders</h2>
                    <a href="{{ route('account.orders') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">View All Orders →</a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="text-center py-8 text-xs text-gray-500">
                        You have not placed any orders yet.
                        <div class="mt-3">
                            <a href="{{ route('products.index') }}" class="inline-flex px-4 py-2 rounded-lg text-white font-semibold text-xs" style="background-color: #00A8B8;">Start Shopping</a>
                        </div>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 text-xs">
                        @foreach($recentOrders as $order)
                            <div class="py-3.5 flex items-center justify-between flex-wrap gap-2">
                                <div>
                                    <div class="font-bold text-gray-900 font-mono">{{ $order->order_number }}</div>
                                    <div class="text-gray-400 text-[11px]">{{ $order->created_at->format('M d, Y') }} • {{ $order->items->count() }} items</div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-gray-100 text-gray-700">
                                        {{ $order->status }}
                                    </span>
                                    <span class="font-black text-gray-900">Rs. {{ number_format($order->total_amount) }}</span>
                                    <a href="{{ route('account.orders.show', $order->order_number) }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">Details →</a>
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
