@extends('layouts.app')

@section('content')
<div class="bg-slate-50 border-b border-pulse-border py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-pulse-orange transition-colors">Home</a>
            <span>/</span>
            <span class="text-pulse-navy font-bold">Contact Us</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16 w-full">
    <div class="max-w-3xl mx-auto text-center mb-8 sm:mb-12">
        <span class="inline-flex items-center px-3.5 py-1 rounded-full text-[11px] sm:text-xs font-bold bg-pulse-teal/10 text-pulse-teal border border-pulse-teal/20 mb-3">
            <i class="fa-solid fa-headset mr-1.5 text-[11px]"></i> Customer Assistance
        </span>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-pulse-navy tracking-tight">
            Contact ShopPulss Support
        </h1>
        <p class="mt-3 text-xs sm:text-sm text-slate-500 leading-relaxed max-w-xl mx-auto">
            Need help tracking a shipment, inquiring about product availability, or processing a return? Our Karachi support desk is here for you.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

        {{-- Left: Contact Channels & Operational Details (5 cols) --}}
        <div class="lg:col-span-5 space-y-6 min-w-0 w-full">
            {{-- Quick Channels Card --}}
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-pulse-border p-5 sm:p-8 shadow-subtle space-y-5 sm:space-y-6">
                <h2 class="text-base font-black text-pulse-navy">
                    Official Contact Channels
                </h2>

                <div class="space-y-4">
                    {{-- WhatsApp --}}
                    <div class="flex items-start space-x-3.5 p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-100">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                            <i class="fa-brands fa-whatsapp text-lg"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-700">WhatsApp Helpline</span>
                            <a href="https://wa.me/923328912706" target="_blank" rel="noopener" class="text-sm font-black text-emerald-700 hover:underline">
                                {{ $whatsapp }}
                            </a>
                            <span class="block text-[11px] text-slate-500 mt-0.5">Fastest response during working hours</span>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="flex items-start space-x-3.5 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-pulse-orange text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-phone text-sm"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-700">Direct Telephone</span>
                            <a href="tel:{{ $phone }}" class="text-sm font-black text-pulse-navy hover:text-pulse-orange transition-colors">
                                {{ $phone }}
                            </a>
                            <span class="block text-[11px] text-slate-500 mt-0.5">Call for urgent order inquiries</span>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-start space-x-3.5 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-pulse-navy text-white flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-envelope text-sm"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-700">Support Email</span>
                            <a href="mailto:{{ $email }}" class="text-sm font-black text-pulse-navy hover:text-pulse-orange transition-colors">
                                {{ $email }}
                            </a>
                            <span class="block text-[11px] text-slate-500 mt-0.5">Typical response within 24 business hours</span>
                        </div>
                    </div>

                    {{-- Hours --}}
                    <div class="flex items-start space-x-3.5 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center shrink-0">
                            <i class="fa-regular fa-clock text-sm"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-700">Support Hours</span>
                            <span class="text-xs font-medium text-slate-600">{{ $hours }}</span>
                            <span class="block text-[11px] text-slate-400 mt-0.5">Closed on Sundays & Gazetted Public Holidays</span>
                        </div>
                    </div>

                    {{-- Facility Address --}}
                    <div class="flex items-start space-x-3.5 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-slate-200 text-slate-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-location-dot text-sm"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-700">Central Logistics Facility</span>
                            <span class="text-xs font-medium text-slate-600">{{ $address }}</span>
                            <span class="block text-[11px] text-slate-400 mt-0.5">Dispatch & Returns Intake Hub</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Policy Assistance Box --}}
            <div class="bg-pulse-navy text-white rounded-3xl p-6 shadow-subtle space-y-3">
                <h3 class="text-xs font-black uppercase tracking-wider text-pulse-teal">Self-Service Resources</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Most customer questions can be solved immediately through our self-service guides:
                </p>
                <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                    <a href="{{ route('orders.track') }}" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition-colors flex items-center">
                        <i class="fa-solid fa-location-crosshairs mr-2 text-pulse-orange text-xs"></i> Track Order
                    </a>
                    <a href="{{ route('faq') }}" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition-colors flex items-center">
                        <i class="fa-solid fa-circle-question mr-2 text-pulse-teal text-xs"></i> Read FAQ
                    </a>
                    <a href="{{ url('/return-refund-policy') }}" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition-colors flex items-center">
                        <i class="fa-solid fa-rotate-left mr-2 text-blue-400 text-xs"></i> Returns Guide
                    </a>
                    <a href="{{ url('/shipping-delivery-policy') }}" class="p-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition-colors flex items-center">
                        <i class="fa-solid fa-truck-fast mr-2 text-emerald-400 text-xs"></i> Shipping Info
                    </a>
                </div>
            </div>
        </div>

        {{-- Right: Contact Form (7 cols) --}}
        <div class="lg:col-span-7 min-w-0 w-full">
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-pulse-border p-5 sm:p-8 lg:p-10 shadow-subtle">
                <h2 class="text-lg sm:text-xl font-black text-pulse-navy mb-1 sm:mb-2">Send an Inquiry</h2>
                <p class="text-xs text-slate-500 mb-5 sm:mb-6">
                    Fill out the form below. Our support team logs each inquiry and responds to your email or WhatsApp number.
                </p>

                {{-- Success Banner --}}
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-start space-x-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base mt-0.5"></i>
                        <div>
                            <strong class="font-bold">Message Delivered!</strong>
                            <p class="mt-0.5 text-emerald-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Rate Limit or Validation Banner --}}
                @if($errors->has('rate_limit'))
                    <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start space-x-3">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 text-base mt-0.5"></i>
                        <div>
                            <strong class="font-bold">Please Wait</strong>
                            <p class="mt-0.5 text-amber-700">{{ $errors->first('rate_limit') }}</p>
                        </div>
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Honeypot Spam Trap (Hidden from genuine users) --}}
                    <div class="hidden" aria-hidden="true">
                        <label for="website_trap">Leave empty</label>
                        <input type="text" name="website_trap" id="website_trap" tabindex="-1" autocomplete="off">
                    </div>

                    {{-- Name & Email Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="contact_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Your Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="contact_name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-rose-300 bg-rose-50/30' : 'border-slate-200 bg-slate-50' }} text-base sm:text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-pulse-orange focus:ring-2 focus:ring-pulse-orange/20 transition-all"
                                placeholder="e.g. Khurram Shahzad"
                            >
                            @error('name')
                                <p class="text-[11px] text-rose-500 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_email" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Email Address <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="email"
                                id="contact_email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-300 bg-rose-50/30' : 'border-slate-200 bg-slate-50' }} text-base sm:text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-pulse-orange focus:ring-2 focus:ring-pulse-orange/20 transition-all"
                                placeholder="name@example.com"
                            >
                            @error('email')
                                <p class="text-[11px] text-rose-500 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Phone & Subject Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="contact_phone" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Phone / WhatsApp <span class="text-slate-400 font-normal">(Optional)</span>
                            </label>
                            <input
                                type="text"
                                id="contact_phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-base sm:text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-pulse-orange focus:ring-2 focus:ring-pulse-orange/20 transition-all"
                                placeholder="0300 1234567"
                            >
                            @error('phone')
                                <p class="text-[11px] text-rose-500 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_subject" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Inquiry Topic <span class="text-rose-500">*</span>
                            </label>
                            <select
                                id="contact_subject"
                                name="subject"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('subject') ? 'border-rose-300 bg-rose-50/30' : 'border-slate-200 bg-slate-50' }} text-base sm:text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-pulse-orange focus:ring-2 focus:ring-pulse-orange/20 transition-all"
                            >
                                <option value="">Select a topic</option>
                                <option value="Order Tracking & Status" {{ old('subject') == 'Order Tracking & Status' ? 'selected' : '' }}>Order Tracking & Delivery Status</option>
                                <option value="Product Availability & Specs" {{ old('subject') == 'Product Availability & Specs' ? 'selected' : '' }}>Product Availability & Specifications</option>
                                <option value="Return or Replacement Request" {{ old('subject') == 'Return or Replacement Request' ? 'selected' : '' }}>Return or Replacement Assistance</option>
                                <option value="Payment or COD Inquiry" {{ old('subject') == 'Payment or COD Inquiry' ? 'selected' : '' }}>Payment / Cash on Delivery Question</option>
                                <option value="General Store Inquiry" {{ old('subject') == 'General Store Inquiry' ? 'selected' : '' }}>General Store Feedback</option>
                            </select>
                            @error('subject')
                                <p class="text-[11px] text-rose-500 mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Message Area --}}
                    <div>
                        <label for="contact_message" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Detailed Message <span class="text-rose-500">*</span>
                        </label>
                        <textarea
                            id="contact_message"
                            name="message"
                            rows="5"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('message') ? 'border-rose-300 bg-rose-50/30' : 'border-slate-200 bg-slate-50' }} text-base sm:text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-pulse-orange focus:ring-2 focus:ring-pulse-orange/20 transition-all"
                            placeholder="Please include your Order Number (if applicable) and describe how we can assist you..."
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-[11px] text-rose-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Privacy statement --}}
                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        By submitting this form, you acknowledge that your contact information will be used exclusively to respond to your inquiry in accordance with our <a href="{{ url('/privacy-policy') }}" class="text-pulse-orange underline">Privacy Policy</a>.
                    </p>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full sm:w-auto px-8 py-3 rounded-xl bg-pulse-orange hover:bg-pulse-orange-dark text-white font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center space-x-2 shadow-xs cursor-pointer"
                        >
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Send Message</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
