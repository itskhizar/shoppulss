@extends('layouts.app')

@section('content')
<div class="bg-slate-50 border-b border-pulse-border py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-pulse-orange transition-colors">Home</a>
            <span>/</span>
            <span class="text-slate-400 capitalize">{{ $page->category ?? 'Policy' }}</span>
            <span>/</span>
            <span class="text-pulse-navy font-bold truncate max-w-xs">{{ $page->title }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

        {{-- Left Content Area (8 Cols) --}}
        <article class="lg:col-span-8 bg-white rounded-3xl border border-pulse-border p-6 sm:p-10 shadow-subtle">
            {{-- Header Badge & Metadata --}}
            <header class="border-b border-slate-100 pb-6 mb-8">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-pulse-teal/10 text-pulse-teal border border-pulse-teal/20">
                        <i class="fa-solid fa-shield-check mr-1.5 text-[11px]"></i> Official Store Policy
                    </span>
                    <span class="text-xs text-slate-400 flex items-center">
                        <i class="fa-regular fa-clock mr-1.5 text-slate-400"></i>
                        Last updated: <strong class="text-slate-600 ml-1">{{ $page->effective_date_formatted }}</strong>
                    </span>
                </div>

                {{-- Page H1 --}}
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-pulse-navy tracking-tight leading-tight">
                    {{ $page->title }}
                </h1>

                @if($page->summary)
                    <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                        {{ $page->summary }}
                    </p>
                @endif
            </header>

            {{-- Policy Body Content with Enhanced Formatting --}}
            <div class="policy-content text-slate-700 text-sm leading-relaxed space-y-6">
                {!! $renderedBody !!}
            </div>

            {{-- Post-Policy Support Card --}}
            <div class="mt-12 pt-8 border-t border-slate-100 bg-slate-50 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-pulse-navy">Questions about this policy?</h3>
                    <p class="text-xs text-slate-500 mt-1">Our support desk in Karachi is ready to assist you regarding any order or policy inquiry.</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:border-pulse-orange hover:text-pulse-orange transition-all shadow-2xs">
                        <i class="fa-solid fa-envelope mr-1.5 text-xs text-pulse-teal"></i> Contact Us
                    </a>
                    <a href="https://wa.me/923328912706" target="_blank" rel="noopener" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-2xs">
                        <i class="fa-brands fa-whatsapp mr-1.5 text-sm"></i> WhatsApp
                    </a>
                </div>
            </div>
        </article>

        {{-- Right Sidebar Navigation (4 Cols) --}}
        <aside class="lg:col-span-4 space-y-6 sticky top-28">
            {{-- Quick Links Card --}}
            <div class="bg-white rounded-3xl border border-pulse-border p-6 shadow-subtle">
                <h2 class="text-xs font-black text-pulse-navy uppercase tracking-wider mb-4 flex items-center">
                    <i class="fa-solid fa-scale-balanced mr-2 text-pulse-orange"></i> Store Policies & Legal
                </h2>
                <nav class="space-y-1.5" aria-label="Related Policies">
                    @php
                        $policyLinks = [
                            ['slug' => 'shipping-delivery-policy', 'title' => 'Shipping & Delivery', 'icon' => 'fa-truck-fast'],
                            ['slug' => 'return-refund-policy', 'title' => 'Returns & Refunds', 'icon' => 'fa-arrow-rotate-left'],
                            ['slug' => 'payment-policy', 'title' => 'Payment Options & COD', 'icon' => 'fa-wallet'],
                            ['slug' => 'warranty-policy', 'title' => 'Warranty Coverage', 'icon' => 'fa-award'],
                            ['slug' => 'privacy-policy', 'title' => 'Privacy Policy', 'icon' => 'fa-user-shield'],
                            ['slug' => 'terms-and-conditions', 'title' => 'Terms & Conditions', 'icon' => 'fa-file-lines'],
                            ['slug' => 'cookie-policy', 'title' => 'Cookie Policy', 'icon' => 'fa-cookie-bite'],
                            ['slug' => 'accessibility', 'title' => 'Accessibility Statement', 'icon' => 'fa-universal-access'],
                        ];
                    @endphp
                    @foreach($policyLinks as $link)
                        <a
                            href="{{ url('/' . $link['slug']) }}"
                            class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->is($link['slug']) ? 'bg-pulse-orange text-white font-bold shadow-2xs' : 'text-slate-600 hover:bg-slate-50 hover:text-pulse-navy' }}"
                        >
                            <span class="flex items-center">
                                <i class="fa-solid {{ $link['icon'] }} w-5 mr-2 text-[11px] {{ request()->is($link['slug']) ? 'text-white' : 'text-slate-400' }}"></i>
                                {{ $link['title'] }}
                            </span>
                            <i class="fa-solid fa-chevron-right text-[10px] opacity-70"></i>
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Support Hub Card --}}
            <div class="bg-gradient-to-br from-pulse-navy to-pulse-navy-dark text-white rounded-3xl p-6 shadow-subtle space-y-4">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-pulse-teal">
                    <i class="fa-solid fa-headset text-lg"></i>
                </div>
                <h3 class="text-base font-black">Direct Customer Care</h3>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Have questions about an order or delivery in Pakistan? Speak with our fulfillment team.
                </p>
                <div class="space-y-2 pt-1 text-xs">
                    <div class="flex items-center space-x-2 text-slate-200">
                        <i class="fa-solid fa-phone text-pulse-orange w-4"></i>
                        <a href="tel:+923328912706" class="hover:text-white font-medium">+923328912706</a>
                    </div>
                    <div class="flex items-center space-x-2 text-slate-200">
                        <i class="fa-solid fa-envelope text-pulse-teal w-4"></i>
                        <a href="mailto:support@shoppulss.com" class="hover:text-white font-medium">support@shoppulss.com</a>
                    </div>
                    <div class="flex items-center space-x-2 text-slate-200">
                        <i class="fa-regular fa-clock text-amber-400 w-4"></i>
                        <span>Mon – Sat: 9:00 AM – 7:00 PM PKT</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-white/10">
                    <a href="{{ route('orders.track') }}" class="block text-center w-full py-2.5 rounded-xl bg-pulse-orange hover:bg-pulse-orange-dark text-white font-bold text-xs transition-colors shadow-2xs">
                        <i class="fa-solid fa-location-crosshairs mr-1.5"></i> Track Your Order
                    </a>
                </div>
            </div>
        </aside>

    </div>
</div>

<style>
/* Policy Content Typography Enhancements */
.policy-content h2 {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0c1b33;
    margin-top: 2rem;
    margin-bottom: 0.75rem;
    padding-bottom: 0.35rem;
    border-bottom: 1px solid #f1f5f9;
}
.policy-content h3 {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1e293b;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
}
.policy-content p {
    margin-bottom: 1rem;
    color: #334155;
    line-height: 1.75;
}
.policy-content ul, .policy-content ol {
    margin-left: 1.25rem;
    margin-bottom: 1.25rem;
    space-y: 0.5rem;
}
.policy-content ul {
    list-style-type: disc;
}
.policy-content ol {
    list-style-type: decimal;
}
.policy-content li {
    margin-bottom: 0.5rem;
    color: #334155;
    line-height: 1.6;
}
.policy-content a {
    color: #ff5722;
    text-decoration: underline;
    font-weight: 600;
}
.policy-content a:hover {
    color: #e64a19;
}
.policy-content strong {
    color: #0f172a;
}
@media print {
    aside, nav, .bg-slate-50, button {
        display: none !important;
    }
    article {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
}
</style>
@endsection
