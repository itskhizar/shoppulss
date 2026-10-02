@extends('layouts.admin')

@section('title', 'Payment Verification & Accounts')
@section('header', 'Payments & Verification')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-gray-900" style="color: #0F1B4D;">Payments & Verification ({{ $payments->total() }})</h1>
            <p class="text-xs text-gray-500">Monitor Bank Transfers, EasyPaisa, JazzCash, and COD cash collections</p>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-semibold">
        @foreach(['all' => 'All Payments', 'pending_verification' => 'Pending Verification', 'paid' => 'Paid / Verified', 'pending' => 'Pending COD', 'failed' => 'Failed'] as $key => $label)
            @php
                $isActive = (request('status') === $key) || (!$key && !request('status')) || ($key === 'all' && !request('status'));
                $cnt = $statusCounts[$key] ?? 0;
            @endphp
            <a
                href="{{ $key === 'all' ? route('admin.payments.index') : route('admin.payments.index', ['status' => $key]) }}"
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
        <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-wrap items-center gap-3">
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif

            <div class="relative flex-1 min-w-[240px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search transaction ref, order #, customer, bank..."
                    class="w-full h-10 pl-10 pr-4 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs text-gray-800 placeholder-gray-400 transition-colors focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8]"
                >
            </div>

            <select name="method" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] cursor-pointer" onchange="this.form.submit()">
                <option value="">Payment Method: All</option>
                <option value="bank_transfer" {{ request('method') === 'bank_transfer' ? 'selected' : '' }}>Direct Bank Transfer</option>
                <option value="easypaisa" {{ request('method') === 'easypaisa' ? 'selected' : '' }}>EasyPaisa</option>
                <option value="jazzcash" {{ request('method') === 'jazzcash' ? 'selected' : '' }}>JazzCash</option>
                <option value="cod" {{ request('method') === 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                <option value="card" {{ request('method') === 'card' ? 'selected' : '' }}>Credit / Debit Card</option>
            </select>

            <button type="submit" class="h-10 px-5 bg-[#00A8B8] text-white rounded-xl text-xs font-semibold hover:opacity-95 shadow-sm transition-all">
                Filter
            </button>
            @if(request()->hasAny(['q', 'status', 'method']))
                <a href="{{ route('admin.payments.index') }}" class="h-10 px-3 flex items-center justify-center text-xs text-gray-500 hover:text-gray-800">Clear</a>
            @endif
        </form>
    </div>

    {{-- Payments Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50/75 border-b border-gray-100 text-gray-500 uppercase font-semibold">
                    <tr>
                        <th class="py-3 px-4">Transaction Ref / Order #</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Method & Details</th>
                        <th class="py-3 px-4">Amount</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Verification</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $p)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-mono font-bold text-gray-900 text-xs">
                                    {{ $p->transaction_reference ?: 'COD / Direct' }}
                                </div>
                                <div class="text-[11px] font-mono text-teal-700">
                                    <a href="{{ route('admin.orders.show', $p->order_id) }}" class="hover:underline">
                                        {{ $p->order?->order_number }}
                                    </a>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-900">{{ $p->order?->customer_name }}</div>
                                <div class="text-[11px] text-gray-500 font-mono">{{ $p->order?->phone }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-800">{{ $p->method_label }}</div>
                                @if($p->bank_name || $p->sender_account_or_phone)
                                    <div class="text-[11px] text-gray-500">
                                        {{ $p->bank_name ? $p->bank_name.' · ' : '' }}{{ $p->sender_account_or_phone }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-black text-gray-900 text-sm">
                                Rs. {{ number_format($p->amount) }}
                            </td>
                            <td class="py-3.5 px-4">
                                @php
                                    $badge = match($p->status) {
                                        'paid' => 'bg-emerald-100 text-emerald-800',
                                        'pending_verification' => 'bg-amber-100 text-amber-800',
                                        'pending' => 'bg-blue-100 text-blue-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badge }}">
                                    {{ $p->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-gray-500">
                                @if($p->verified_by)
                                    <span class="font-semibold text-gray-800 block">✓ {{ $p->verifiedBy?->name }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $p->verified_at?->format('M d, h:i A') }}</span>
                                @elseif($p->status === 'pending_verification')
                                    <span class="text-amber-700 font-bold text-[11px]">Needs Action</span>
                                @else
                                    <span class="text-gray-400 text-[11px]">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($p->status === 'pending_verification')
                                        <form method="POST" action="{{ route('admin.payments.verify', $p->id) }}" class="inline">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition-colors shadow-xs"
                                                title="Approve & Mark Paid"
                                            >
                                                ✓ Verify
                                            </button>
                                        </form>
                                    @endif
                                    <a
                                        href="{{ route('admin.orders.show', $p->order_id) }}"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors"
                                    >
                                        Order
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400 text-xs">
                                No payment records found matching the search criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
