@extends('layouts.app')

@section('title', 'Checkout - ShopPulss')

@section('content')
<div class="bg-gray-50 py-6 border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        <h1 class="text-2xl md:text-3xl font-black text-gray-900" style="color: #0F1B4D;">
            Checkout & Delivery
        </h1>
        <p class="text-xs text-gray-500 mt-1">Provide your delivery address and choose your payment method</p>
    </div>
</div>

<div class="max-w-screen-xl mx-auto px-4 py-8">
    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.process') }}" id="checkout-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left 2 cols: Shipping & Payment --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Contact & Address Information --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background-color: #0F1B4D;">1</div>
                        <h2 class="font-bold text-base text-gray-900" style="color: #0F1B4D;">Shipping Details</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="full_name" class="block text-xs font-semibold text-gray-700 mb-1">Full Name *</label>
                            <input
                                id="full_name"
                                type="text"
                                name="full_name"
                                value="{{ old('full_name', $user?->name ?? $savedAddress?->full_name) }}"
                                required
                                class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                                placeholder="Muhammad Ali"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email Address *</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $user?->email) }}"
                                required
                                class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                                placeholder="ali@example.com"
                            >
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-semibold text-gray-700 mb-1">Phone Number (Active for courier) *</label>
                            <input
                                id="phone"
                                type="tel"
                                name="phone"
                                value="{{ old('phone', $user?->phone ?? $savedAddress?->phone) }}"
                                required
                                class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                                placeholder="03001234567"
                            >
                        </div>

                        <div>
                            <label for="province" class="block text-xs font-semibold text-gray-700 mb-1">Province *</label>
                            <select
                                id="province"
                                name="province"
                                required
                                class="w-full h-11 px-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                            >
                                @php
                                    $provinces = ['Punjab', 'Sindh', 'Khyber Pakhtunkhwa', 'Balochistan', 'Islamabad Capital Territory', 'Azad Jammu & Kashmir', 'Gilgit-Baltistan'];
                                    $selectedProv = old('province', $savedAddress?->province ?? 'Punjab');
                                @endphp
                                @foreach($provinces as $p)
                                    <option value="{{ $p }}" {{ $selectedProv === $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="city" class="block text-xs font-semibold text-gray-700 mb-1">City *</label>
                            <input
                                id="city"
                                type="text"
                                name="city"
                                value="{{ old('city', $savedAddress?->city ?? 'Lahore') }}"
                                required
                                class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                                placeholder="e.g. Karachi, Lahore, Islamabad"
                            >
                        </div>

                        <div>
                            <label for="area" class="block text-xs font-semibold text-gray-700 mb-1">Area / Sector</label>
                            <input
                                id="area"
                                type="text"
                                name="area"
                                value="{{ old('area', $savedAddress?->area) }}"
                                class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                                placeholder="e.g. DHA Phase 5, Gulshan, F-10"
                            >
                        </div>

                        <div>
                            <label for="postal_code" class="block text-xs font-semibold text-gray-700 mb-1">Postal Code</label>
                            <input
                                id="postal_code"
                                type="text"
                                name="postal_code"
                                value="{{ old('postal_code', $savedAddress?->postal_code) }}"
                                class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                                placeholder="e.g. 54000"
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label for="street_address" class="block text-xs font-semibold text-gray-700 mb-1">Street Address / House No. *</label>
                            <textarea
                                id="street_address"
                                name="street_address"
                                rows="2"
                                required
                                class="w-full p-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                                placeholder="House #, Street name, building or nearby landmark"
                            >{{ old('street_address', $savedAddress?->street_address) }}</textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="customer_notes" class="block text-xs font-semibold text-gray-700 mb-1">Delivery Instructions (Optional)</label>
                            <input
                                id="customer_notes"
                                type="text"
                                name="customer_notes"
                                value="{{ old('customer_notes') }}"
                                class="w-full h-10 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                                placeholder="Special delivery remarks (e.g., call upon arrival)"
                            >
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-bold" style="background-color: #0F1B4D;">2</div>
                        <h2 class="font-bold text-base text-gray-900" style="color: #0F1B4D;">Payment Method</h2>
                    </div>

                    <div class="space-y-3" id="payment-methods-container">
                        {{-- 1. Cash on Delivery --}}
                        <label class="payment-option-card flex flex-col p-4 rounded-xl border-2 border-teal-500 bg-teal-50/20 cursor-pointer transition-all">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="cod" checked class="mt-1 text-teal-600 focus:ring-teal-500" onchange="togglePaymentPanels('cod')">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-sm text-gray-900">Cash on Delivery (COD)</span>
                                            <span class="text-[10px] font-bold text-teal-700 bg-teal-100 px-2 py-0.5 rounded">Most Popular</span>
                                        </div>
                                        <span class="text-xs text-gray-400 font-mono">🚚 Leopards / TCS</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pay with physical cash directly to the courier representative when the parcel arrives at your doorstep.</p>
                                </div>
                            </div>
                        </label>

                        {{-- 2. Direct Bank Transfer --}}
                        <label class="payment-option-card flex flex-col p-4 rounded-xl border border-gray-200 hover:border-gray-300 cursor-pointer transition-all">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="bank_transfer" class="mt-1 text-teal-600 focus:ring-teal-500" onchange="togglePaymentPanels('bank_transfer')">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-sm text-gray-900">Direct Bank Transfer</span>
                                            <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded">0% Fee</span>
                                        </div>
                                        <span class="text-xs text-gray-400 font-medium">Any Pak Bank / 1LINK / Raast</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Transfer funds via your mobile banking app or ATM and submit the transaction reference for admin verification.</p>
                                </div>
                            </div>

                            {{-- Bank Transfer Fields (Hidden by default) --}}
                            <div id="panel-bank_transfer" class="hidden mt-4 pt-4 border-t border-gray-100 space-y-4">
                                {{-- Official ShopPulss Bank Details Notice --}}
                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-2">
                                    <div class="font-bold text-slate-800 text-xs uppercase tracking-wide flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        Official ShopPulss Bank Account Details
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-slate-700 pt-1">
                                        <div><span class="text-slate-400">Bank Name:</span> <strong>{{ $settings['bank_name'] ?? 'Meezan Bank Limited' }}</strong></div>
                                        <div><span class="text-slate-400">Account Title:</span> <strong>{{ $settings['bank_account_title'] ?? 'ShopPulss Private Limited' }}</strong></div>
                                        <div><span class="text-slate-400">Account Number:</span> <strong class="font-mono">{{ $settings['bank_account_number'] ?? '01020304050607' }}</strong></div>
                                        <div><span class="text-slate-400">IBAN:</span> <strong class="font-mono text-[11px]">{{ $settings['bank_iban'] ?? 'PK78MEZN0001020304050607' }}</strong></div>
                                    </div>
                                    <p class="text-[11px] text-slate-500 pt-1 border-t border-slate-200/60 leading-relaxed">
                                        {{ $settings['bank_instructions'] ?? 'Please transfer the exact amount and enter your transaction reference number below.' }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label for="bank_name" class="block text-xs font-semibold text-gray-700 mb-1">Your Bank Name *</label>
                                        <input
                                            type="text"
                                            id="bank_name"
                                            name="bank_name"
                                            value="{{ old('bank_name') }}"
                                            placeholder="e.g. HBL, Meezan, Alfalah"
                                            class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500"
                                        >
                                    </div>
                                    <div>
                                        <label for="transaction_reference" class="block text-xs font-semibold text-gray-700 mb-1">Transaction Ref / ID *</label>
                                        <input
                                            type="text"
                                            id="transaction_reference"
                                            name="transaction_reference"
                                            value="{{ old('transaction_reference') }}"
                                            placeholder="e.g. 123456789 or Slip #"
                                            class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500 font-mono"
                                        >
                                    </div>
                                    <div>
                                        <label for="sender_account_or_phone" class="block text-xs font-semibold text-gray-700 mb-1">Sender Account / Phone</label>
                                        <input
                                            type="text"
                                            id="sender_account_or_phone"
                                            name="sender_account_or_phone"
                                            value="{{ old('sender_account_or_phone') }}"
                                            placeholder="e.g. 03001234567"
                                            class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500"
                                        >
                                    </div>
                                </div>
                            </div>
                        </label>

                        {{-- 3. EasyPaisa --}}
                        <label class="payment-option-card flex flex-col p-4 rounded-xl border border-gray-200 hover:border-gray-300 cursor-pointer transition-all">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="easypaisa" class="mt-1 text-teal-600 focus:ring-teal-500" onchange="togglePaymentPanels('easypaisa')">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-sm text-gray-900">EasyPaisa Mobile Account</span>
                                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Instant</span>
                                        </div>
                                        <span class="text-xs font-bold text-emerald-600">EasyPaisa Gateway</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pay instantly through your EasyPaisa wallet. Enter your registered mobile number below.</p>
                                </div>
                            </div>

                            {{-- EasyPaisa Fields (Hidden by default) --}}
                            <div id="panel-easypaisa" class="hidden mt-4 pt-4 border-t border-gray-100 space-y-3">
                                <div class="max-w-xs">
                                    <label for="easypaisa_mobile_number" class="block text-xs font-semibold text-gray-700 mb-1">EasyPaisa Registered Mobile Number *</label>
                                    <input
                                        type="tel"
                                        id="easypaisa_mobile_number"
                                        name="easypaisa_mobile_number"
                                        value="{{ old('easypaisa_mobile_number', $user?->phone) }}"
                                        placeholder="03XXXXXXXXX"
                                        class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500 font-mono"
                                    >
                                    <p class="text-[10px] text-gray-400 mt-1">You will receive an instant approval prompt on your EasyPaisa app / phone.</p>
                                </div>
                            </div>
                        </label>

                        {{-- 4. JazzCash --}}
                        <label class="payment-option-card flex flex-col p-4 rounded-xl border border-gray-200 hover:border-gray-300 cursor-pointer transition-all">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="jazzcash" class="mt-1 text-teal-600 focus:ring-teal-500" onchange="togglePaymentPanels('jazzcash')">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-sm text-gray-900">JazzCash Mobile Account</span>
                                            <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded">Instant</span>
                                        </div>
                                        <span class="text-xs font-bold text-amber-600">JazzCash Gateway</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pay directly from your JazzCash wallet using your mobile number and CNIC verification.</p>
                                </div>
                            </div>

                            {{-- JazzCash Fields (Hidden by default) --}}
                            <div id="panel-jazzcash" class="hidden mt-4 pt-4 border-t border-gray-100 space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-md">
                                    <div>
                                        <label for="jazzcash_mobile_number" class="block text-xs font-semibold text-gray-700 mb-1">JazzCash Mobile Number *</label>
                                        <input
                                            type="tel"
                                            id="jazzcash_mobile_number"
                                            name="jazzcash_mobile_number"
                                            value="{{ old('jazzcash_mobile_number', $user?->phone) }}"
                                            placeholder="03XXXXXXXXX"
                                            class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500 font-mono"
                                        >
                                    </div>
                                    <div>
                                        <label for="jazzcash_cnic_last4" class="block text-xs font-semibold text-gray-700 mb-1">CNIC Last 4 Digits</label>
                                        <input
                                            type="text"
                                            id="jazzcash_cnic_last4"
                                            name="jazzcash_cnic_last4"
                                            maxlength="4"
                                            value="{{ old('jazzcash_cnic_last4') }}"
                                            placeholder="e.g. 1234"
                                            class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500 font-mono"
                                        >
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-400">An MPIN authorization prompt will appear on your JazzCash mobile screen.</p>
                            </div>
                        </label>

                        {{-- 5. Card Online --}}
                        <label class="payment-option-card flex flex-col p-4 rounded-xl border border-gray-200 hover:border-gray-300 cursor-pointer transition-all">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="card" class="mt-1 text-teal-600 focus:ring-teal-500" onchange="togglePaymentPanels('card')">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-sm text-gray-900">Debit / Credit Card & Instant Pay</span>
                                        <div class="flex items-center gap-1 text-xs text-gray-400">
                                            <span>Visa / Mastercard / PayPak</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Secure instant payment via debit or credit card with zero processing fees.</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            {{-- Right Col: Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-5 sticky top-24">
                    <h2 class="text-base font-bold text-gray-900 pb-3 border-b border-gray-100" style="color: #0F1B4D;">
                        Order Summary
                    </h2>

                    {{-- Mini items list --}}
                    <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                        @foreach($cart->items as $item)
                            <div class="flex items-center gap-3 text-xs">
                                <div class="w-12 h-12 rounded-lg bg-gray-50 p-1 flex-shrink-0 flex items-center justify-center border border-gray-100">
                                    <img src="{{ $item->product->images->first()?->image_url ?? 'https://placehold.co/50x50' }}" alt="" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-gray-800 truncate">{{ $item->product->name }}</div>
                                    <div class="text-gray-400 text-[11px]">Qty: {{ $item->quantity }} × Rs. {{ number_format($item->unit_price) }}</div>
                                </div>
                                <div class="font-bold text-gray-900">
                                    Rs. {{ number_format($item->total_price) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2.5 pt-3 border-t border-gray-100 text-xs">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold text-gray-900">Rs. {{ number_format($totals['subtotal']) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Nationwide Delivery</span>
                            @if($totals['shipping_free'])
                                <span class="font-bold text-emerald-600 uppercase">FREE</span>
                            @else
                                <span class="font-semibold text-gray-900">Rs. {{ number_format($totals['shipping']) }}</span>
                            @endif
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex justify-between text-base">
                            <span class="font-bold text-gray-900">Total to Pay</span>
                            <span class="font-black text-xl" style="color: #0F1B4D;">Rs. {{ number_format($totals['total']) }}</span>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full h-12 rounded-xl text-white font-bold text-sm shadow-md flex items-center justify-center gap-2 transition-all hover:opacity-95"
                        style="background-color: #0F1B4D;"
                        id="place-order-btn"
                    >
                        Confirm & Place Order →
                    </button>

                    <div class="text-[11px] text-gray-400 text-center leading-relaxed">
                        By placing your order, you agree to ShopPulss's Terms & Conditions and 7-day Return Policy.
                    </div>
                </div>
            </div>

    </form>
</div>

<script>
function togglePaymentPanels(method) {
    const panels = ['bank_transfer', 'easypaisa', 'jazzcash'];
    panels.forEach(p => {
        const el = document.getElementById('panel-' + p);
        if (el) {
            if (p === method) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }
    });

    // Update card borders
    const cards = document.querySelectorAll('.payment-option-card');
    cards.forEach(card => {
        const radio = card.querySelector('input[type="radio"]');
        if (radio && radio.checked) {
            card.classList.add('border-teal-500', 'bg-teal-50/20');
            card.classList.remove('border-gray-200');
        } else {
            card.classList.remove('border-teal-500', 'bg-teal-50/20');
            card.classList.add('border-gray-200');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="payment_method"]:checked');
    if (checked) {
        togglePaymentPanels(checked.value);
    }
});
</script>
@endsection
