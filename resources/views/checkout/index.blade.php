@extends('layouts.app')

@section('title', 'Secure Checkout - ShopPulss')
@section('description', 'Complete your order with Cash on Delivery across Pakistan.')
@section('robots', 'noindex, follow')

@section('content')
{{-- Breadcrumbs & Header --}}
<div class="bg-white border-b border-[#E6E8F2] py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="breadcrumb mb-2">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('cart.index') }}">Cart</a>
            <span>/</span>
            <span class="current">Checkout</span>
        </div>
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-[#0F1654] tracking-tight">Checkout & Delivery</h1>
            <p class="text-xs text-gray-500 mt-1">Provide your delivery address and choose your preferred payment method</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    @if($errors->any())
        <div class="alert alert-error mb-6">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <div>
                <strong class="font-bold block mb-1">Please review the following errors:</strong>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.process') }}" id="checkout-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- Left 2 cols: Shipping & Payment --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Contact & Address Information --}}
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 sm:p-7 shadow-sp-card space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-[#E6E8F2]">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-xs font-black shadow-sm bg-gradient-to-br from-[#0F1654] to-[#16206E]">1</div>
                        <div>
                            <h2 class="font-black text-base text-[#0F1654]">Shipping & Contact Information</h2>
                            <p class="text-[11px] text-gray-500">Accurate details ensure speedy courier dispatch</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="full_name" class="sp-label">Full Name *</label>
                            <input
                                id="full_name"
                                type="text"
                                name="full_name"
                                value="{{ old('full_name', $user?->name ?? $savedAddress?->full_name) }}"
                                required
                                class="sp-input"
                                placeholder="Muhammad Ali"
                            >
                        </div>

                        <div>
                            <label for="email" class="sp-label">Email Address *</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $user?->email) }}"
                                required
                                class="sp-input"
                                placeholder="ali@example.com"
                            >
                        </div>

                        <div>
                            <label for="phone" class="sp-label">Active Mobile Phone Number *</label>
                            <input
                                id="phone"
                                type="tel"
                                name="phone"
                                value="{{ old('phone', $user?->phone ?? $savedAddress?->phone) }}"
                                required
                                class="sp-input font-mono"
                                placeholder="03001234567"
                            >
                            <p class="text-[10px] text-gray-400 mt-1">Courier rider will call on this number upon arrival</p>
                        </div>

                        <div>
                            <label for="province" class="sp-label">Province *</label>
                            <select
                                id="province"
                                name="province"
                                required
                                class="sp-input"
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
                            <label for="city" class="sp-label">City *</label>
                            <input
                                id="city"
                                type="text"
                                name="city"
                                value="{{ old('city', $savedAddress?->city ?? 'Lahore') }}"
                                required
                                class="sp-input"
                                placeholder="e.g. Karachi, Lahore, Islamabad"
                            >
                        </div>

                        <div>
                            <label for="area" class="sp-label">Area / Sector</label>
                            <input
                                id="area"
                                type="text"
                                name="area"
                                value="{{ old('area', $savedAddress?->area) }}"
                                class="sp-input"
                                placeholder="e.g. DHA Phase 5, Gulshan, F-10"
                            >
                        </div>

                        <div>
                            <label for="postal_code" class="sp-label">Postal Code</label>
                            <input
                                id="postal_code"
                                type="text"
                                name="postal_code"
                                value="{{ old('postal_code', $savedAddress?->postal_code) }}"
                                class="sp-input font-mono"
                                placeholder="e.g. 54000"
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label for="street_address" class="sp-label">Street Address / House No. *</label>
                            <textarea
                                id="street_address"
                                name="street_address"
                                rows="2"
                                required
                                class="w-full p-3 rounded-xl bg-[#F6F7FB] border border-[#E6E8F2] text-sm text-[#161616] focus:bg-white focus:outline-none focus:border-[#FF5A1F] focus:ring-2 focus:ring-[#FF5A1F]/15 transition-all"
                                placeholder="House #, Street name, building or nearby landmark"
                            >{{ old('street_address', $savedAddress?->street_address) }}</textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="customer_notes" class="sp-label">Delivery Instructions (Optional)</label>
                            <input
                                id="customer_notes"
                                type="text"
                                name="customer_notes"
                                value="{{ old('customer_notes') }}"
                                class="sp-input"
                                placeholder="Special delivery remarks (e.g. Call before delivery, deliver in afternoon)"
                            >
                        </div>
                    </div>
                </div>

                {{-- Payment Method Selection --}}
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 sm:p-7 shadow-sp-card space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-[#E6E8F2]">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-xs font-black shadow-sm bg-gradient-to-br from-[#FF6B1F] to-[#FF4A0A]">2</div>
                        <div>
                            <h2 class="font-black text-base text-[#0F1654]">Select Payment Method</h2>
                            <p class="text-[11px] text-gray-500">All transactions are direct, verified, and secure</p>
                        </div>
                    </div>

                    <div class="space-y-3" id="payment-methods-container">
                        {{-- 1. Cash on Delivery --}}
                        <label class="payment-option-card flex flex-col p-4 rounded-2xl border-2 border-[#FF5A1F] bg-[#FFF1EA] cursor-pointer transition-all">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="cod" checked class="mt-1 text-[#FF5A1F] focus:ring-[#FF5A1F]" onchange="togglePaymentPanels('cod')">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-black text-sm text-[#0F1654]">Cash on Delivery (COD)</span>
                                            <span class="badge-orange">Most Popular</span>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-500">ðŸšš Leopards / TCS</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">Pay with physical cash directly to the courier representative when your parcel is delivered at your doorstep.</p>
                                </div>
                            </div>
                        </label>

                        {{-- 2. Direct Bank Transfer --}}
                        <label class="payment-option-card flex flex-col p-4 rounded-2xl border border-[#E6E8F2] hover:border-[#FF5A1F]/40 cursor-pointer transition-all">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="bank_transfer" class="mt-1 text-[#FF5A1F] focus:ring-[#FF5A1F]" onchange="togglePaymentPanels('bank_transfer')">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-black text-sm text-[#0F1654]">Direct Bank Transfer</span>
                                            <span class="badge-teal">0% Fee</span>
                                        </div>
                                        <span class="text-xs text-gray-400 font-medium">Any Bank / 1LINK / Raast</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Transfer funds via your mobile banking app or ATM and submit the transaction reference for swift verification.</p>
                                </div>
                            </div>

                            {{-- Bank Transfer Fields (Hidden by default) --}}
                            <div id="panel-bank_transfer" class="hidden mt-4 pt-4 border-t border-[#E6E8F2] space-y-4">
                                {{-- Official ShopPulss Bank Details Notice --}}
                                <div class="p-4 bg-[#F6F7FB] rounded-2xl border border-[#E6E8F2] text-xs space-y-2">
                                    <div class="font-bold text-[#0F1654] text-xs uppercase tracking-wide flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-[#0AA6B7]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        Official ShopPulss Bank Account Details
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-gray-700 pt-1">
                                        <div><span class="text-gray-400">Bank Name:</span> <strong>{{ $settings['bank_name'] ?? 'Meezan Bank Limited' }}</strong></div>
                                        <div><span class="text-gray-400">Account Title:</span> <strong>{{ $settings['bank_account_title'] ?? 'ShopPulss Private Limited' }}</strong></div>
                                        <div><span class="text-gray-400">Account Number:</span> <strong class="font-mono text-[#0F1654]">{{ $settings['bank_account_number'] ?? '01020304050607' }}</strong></div>
                                        <div><span class="text-gray-400">IBAN:</span> <strong class="font-mono text-[11px] text-[#0F1654]">{{ $settings['bank_iban'] ?? 'PK78MEZN0001020304050607' }}</strong></div>
                                    </div>
                                    <p class="text-[11px] text-gray-500 pt-1 border-t border-gray-200 leading-relaxed">
                                        {{ $settings['bank_instructions'] ?? 'Please transfer the exact amount and enter your transaction reference number below.' }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label for="bank_name" class="sp-label">Your Bank Name *</label>
                                        <input
                                            type="text"
                                            id="bank_name"
                                            name="bank_name"
                                            value="{{ old('bank_name') }}"
                                            placeholder="e.g. HBL, Meezan, Alfalah"
                                            class="sp-input text-xs"
                                        >
                                    </div>
                                    <div>
                                        <label for="transaction_reference" class="sp-label">Transaction Ref / ID *</label>
                                        <input
                                            type="text"
                                            id="transaction_reference"
                                            name="transaction_reference"
                                            value="{{ old('transaction_reference') }}"
                                            placeholder="e.g. 123456789 or Slip #"
                                            class="sp-input text-xs font-mono"
                                        >
                                    </div>
                                    <div>
                                        <label for="sender_account_or_phone" class="sp-label">Sender Account / Phone</label>
                                        <input
                                            type="text"
                                            id="sender_account_or_phone"
                                            name="sender_account_or_phone"
                                            value="{{ old('sender_account_or_phone') }}"
                                            placeholder="e.g. 03001234567"
                                            class="sp-input text-xs font-mono"
                                        >
                                    </div>
                                </div>
                            </div>
                        </label>

                        {{-- 3. EasyPaisa --}}
                        <label class="payment-option-card flex flex-col p-4 rounded-2xl border border-[#E6E8F2] hover:border-[#FF5A1F]/40 cursor-pointer transition-all">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="easypaisa" class="mt-1 text-[#FF5A1F] focus:ring-[#FF5A1F]" onchange="togglePaymentPanels('easypaisa')">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-black text-sm text-[#0F1654]">EasyPaisa Mobile Account</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">Instant</span>
                                        </div>
                                        <span class="text-xs font-bold text-emerald-600">EasyPaisa Manual Transfer / Direct</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pay instantly through your EasyPaisa wallet. Enter your registered mobile number below.</p>
                                </div>
                            </div>

                            <div id="panel-easypaisa" class="hidden mt-4 pt-4 border-t border-[#E6E8F2] space-y-4">
                                {{-- Official ShopPulss EasyPaisa Details Notice --}}
                                <div class="p-4 bg-emerald-50/60 rounded-2xl border border-emerald-200/80 text-xs space-y-2">
                                    <div class="font-bold text-emerald-900 text-xs uppercase tracking-wide flex items-center gap-1.5">
                                        <i class="fa-solid fa-mobile-screen-button text-emerald-600"></i>
                                        Official ShopPulss EasyPaisa Account Details
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-slate-800 pt-1">
                                        <div><span class="text-slate-500">Account Title:</span> <strong>{{ $settings['easypaisa_account_title'] ?? 'ShopPulss / Muhammad Khizar' }}</strong></div>
                                        <div><span class="text-slate-500">EasyPaisa Mobile Number:</span> <strong class="font-mono text-emerald-800">{{ $settings['easypaisa_account_number'] ?? ($settings['store_phone'] ?? '03328912706') }}</strong></div>
                                    </div>
                                    <p class="text-[11px] text-emerald-950 pt-1 border-t border-emerald-200/60 leading-relaxed">
                                        Please send <strong>Rs. {{ number_format($totals['total']) }}</strong> to our EasyPaisa account and provide your sender registered mobile number & Transaction ID below for manual verification.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label for="easypaisa_mobile_number" class="sp-label">EasyPaisa Registered Mobile Number *</label>
                                        <input
                                            type="tel"
                                            id="easypaisa_mobile_number"
                                            name="easypaisa_mobile_number"
                                            value="{{ old('easypaisa_mobile_number', $user?->phone) }}"
                                            placeholder="03XXXXXXXXX"
                                            class="sp-input font-mono text-xs"
                                        >
                                    </div>
                                    <div>
                                        <label for="easypaisa_transaction_id" class="sp-label">Transaction ID / TID (Optional)</label>
                                        <input
                                            type="text"
                                            id="easypaisa_transaction_id"
                                            name="easypaisa_transaction_id"
                                            value="{{ old('easypaisa_transaction_id') }}"
                                            placeholder="e.g. 1234567890"
                                            class="sp-input font-mono text-xs"
                                        >
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-500">You will receive an instant approval prompt on your EasyPaisa app / phone or SMS receipt.</p>
                            </div>
                        </label>

                        {{-- 4. JazzCash --}}
                        <label class="payment-option-card flex flex-col p-4 rounded-2xl border border-[#E6E8F2] hover:border-[#FF5A1F]/40 cursor-pointer transition-all">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="payment_method" value="jazzcash" class="mt-1 text-[#FF5A1F] focus:ring-[#FF5A1F]" onchange="togglePaymentPanels('jazzcash')">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="font-black text-sm text-[#0F1654]">JazzCash Mobile Account</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800">Instant</span>
                                        </div>
                                        <span class="text-xs font-bold text-amber-600">JazzCash Manual Transfer / Direct</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Pay directly from your JazzCash wallet using your mobile number and CNIC verification.</p>
                                </div>
                            </div>

                            <div id="panel-jazzcash" class="hidden mt-4 pt-4 border-t border-[#E6E8F2] space-y-4">
                                {{-- Official ShopPulss JazzCash Details Notice --}}
                                <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200/80 text-xs space-y-2">
                                    <div class="font-bold text-amber-900 text-xs uppercase tracking-wide flex items-center gap-1.5">
                                        <i class="fa-solid fa-wallet text-amber-600"></i>
                                        Official ShopPulss JazzCash Account Details
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-slate-800 pt-1">
                                        <div><span class="text-slate-500">Account Title:</span> <strong>{{ $settings['jazzcash_account_title'] ?? 'ShopPulss / Muhammad Khizar' }}</strong></div>
                                        <div><span class="text-slate-500">JazzCash Mobile Number:</span> <strong class="font-mono text-amber-800">{{ $settings['jazzcash_account_number'] ?? ($settings['store_phone'] ?? '03328912706') }}</strong></div>
                                    </div>
                                    <p class="text-[11px] text-amber-950 pt-1 border-t border-amber-200/60 leading-relaxed">
                                        Please send <strong>Rs. {{ number_format($totals['total']) }}</strong> to our JazzCash account and provide your sender registered mobile number & Transaction ID below for manual verification.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label for="jazzcash_mobile_number" class="sp-label">JazzCash Mobile Number *</label>
                                        <input
                                            type="tel"
                                            id="jazzcash_mobile_number"
                                            name="jazzcash_mobile_number"
                                            value="{{ old('jazzcash_mobile_number', $user?->phone) }}"
                                            placeholder="03XXXXXXXXX"
                                            class="sp-input font-mono text-xs"
                                        >
                                    </div>
                                    <div>
                                        <label for="jazzcash_cnic_last4" class="sp-label">CNIC Last 4 Digits</label>
                                        <input
                                            type="text"
                                            id="jazzcash_cnic_last4"
                                            name="jazzcash_cnic_last4"
                                            maxlength="4"
                                            value="{{ old('jazzcash_cnic_last4') }}"
                                            placeholder="e.g. 1234"
                                            class="sp-input font-mono text-xs"
                                        >
                                    </div>
                                    <div>
                                        <label for="jazzcash_transaction_id" class="sp-label">Transaction ID / TID (Optional)</label>
                                        <input
                                            type="text"
                                            id="jazzcash_transaction_id"
                                            name="jazzcash_transaction_id"
                                            value="{{ old('jazzcash_transaction_id') }}"
                                            placeholder="e.g. 1234567890"
                                            class="sp-input font-mono text-xs"
                                        >
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-500">Pay directly from your JazzCash wallet using your mobile number and CNIC verification.</p>
                            </div>
                        </label>

                    </div>
                </div>

            </div>

            {{-- Right Col: Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 shadow-sp-card space-y-5 sticky top-28">
                    <h2 class="text-base font-black text-[#0F1654] pb-3 border-b border-[#E6E8F2] flex items-center justify-between">
                        <span>Order Summary</span>
                        <span class="badge-navy">{{ $cart->items->count() }} {{ Str::plural('item', $cart->items->count()) }}</span>
                    </h2>

                    {{-- Mini items list --}}
                    <div class="space-y-3 max-h-64 overflow-y-auto pr-1 divide-y divide-[#E6E8F2]">
                        @foreach($cart->items as $item)
                            <div class="pt-3 first:pt-0 flex items-center gap-3 text-xs">
                                <div class="w-12 h-12 rounded-xl bg-[#F8FAFC] p-1 flex-shrink-0 flex items-center justify-center border border-[#E6E8F2]">
                                    <img src="{{ $item->product->images->first()?->image_url ?? 'https://placehold.co/50x50/F6F7FB/0F1654' }}" alt="" class="w-full h-full object-contain mix-blend-multiply">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-gray-800 truncate">{{ $item->product->name }}</div>
                                    <div class="text-gray-400 text-[11px]">Qty: {{ $item->quantity }} Ã— Rs. {{ number_format($item->unit_price) }}</div>
                                </div>
                                <div class="font-black text-[#0F1654]">
                                    Rs. {{ number_format($item->total_price) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2.5 pt-3 border-t border-[#E6E8F2] text-xs">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-[#0F1654]">Rs. {{ number_format($totals['subtotal']) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600 items-center">
                            <span>Nationwide Delivery</span>
                            @if($totals['shipping_free'])
                                <span class="badge-teal">FREE</span>
                            @else
                                <span class="font-bold text-[#0F1654]">Rs. {{ number_format($totals['shipping']) }}</span>
                            @endif
                        </div>
                        <div class="pt-3 border-t border-[#E6E8F2] flex justify-between items-baseline text-base">
                            <span class="font-extrabold text-[#0F1654]">Total to Pay</span>
                            <span class="text-xl font-black text-[#0F1654]">Rs. {{ number_format($totals['total']) }}</span>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="btn-primary w-full py-4 text-sm"
                        id="place-order-btn"
                    >
                        <span>Confirm & Place Order</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>

                    <div class="pt-2 text-[11px] text-gray-400 text-center leading-relaxed">
                        ðŸ”’ 256-bit encrypted checkout. By placing your order, you agree to ShopPulss's Terms & Conditions and 7-day Return Policy.
                    </div>
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

    const cards = document.querySelectorAll('.payment-option-card');
    cards.forEach(card => {
        const radio = card.querySelector('input[type="radio"]');
        if (radio && radio.checked) {
            card.classList.add('border-2', 'border-[#FF5A1F]', 'bg-[#FFF1EA]');
            card.classList.remove('border-[#E6E8F2]');
        } else {
            card.classList.remove('border-2', 'border-[#FF5A1F]', 'bg-[#FFF1EA]');
            card.classList.add('border', 'border-[#E6E8F2]');
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
