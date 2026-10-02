@extends('layouts.app')

@section('title', 'My Orders - ShopPulss')

@section('content')
<div class="bg-gray-50 py-6 border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        <h1 class="text-2xl font-black text-gray-900" style="color: #0F1B4D;">
            My Orders
        </h1>
        <p class="text-xs text-gray-500 mt-0.5">Track your order statuses, invoices, and shipment tracking</p>
    </div>
</div>

<div class="max-w-screen-xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
            @include('account.partials.sidebar')
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                @if($orders->isEmpty())
                    <div class="text-center py-12 text-xs text-gray-500">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3 text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <p class="font-bold text-gray-800 text-sm mb-1">No orders found</p>
                        <p class="mb-4">You have not placed any orders with ShopPulss yet.</p>
                        <a href="{{ route('products.index') }}" class="inline-flex px-4 py-2 rounded-lg text-white font-semibold text-xs" style="background-color: #00A8B8;">Browse Catalog</a>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 text-xs">
                        @foreach($orders as $order)
                            <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-black text-sm text-gray-900 font-mono">{{ $order->order_number }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </div>
                                    <div class="text-gray-400 text-[11px]">
                                        Placed on {{ $order->created_at->format('M d, Y') }} • {{ $order->items->count() }} items
                                    </div>
                                    <div class="text-gray-600">
                                        Total: <strong class="text-gray-900">Rs. {{ number_format($order->total_amount) }}</strong>
                                        <span class="text-gray-400">({{ ucfirst($order->payment_method ?? 'COD') }})</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a
                                        href="{{ route('account.orders.show', $order->order_number) }}"
                                        class="px-4 py-2 rounded-lg text-xs font-semibold text-white shadow-sm hover:opacity-95"
                                        style="background-color: #0F1B4D;"
                                    >
                                        View Order Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
