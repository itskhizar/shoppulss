@extends('layouts.app')

@section('content')
<div class="bg-slate-50 border-b border-pulse-border py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-pulse-orange transition-colors">Home</a>
            <span>/</span>
            <span class="text-pulse-navy font-bold">About Us</span>
        </nav>
    </div>
</div>

{{-- Hero Section --}}
<section class="bg-gradient-to-b from-white to-slate-50 border-b border-pulse-border py-14 lg:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-pulse-orange/10 text-pulse-orange border border-pulse-orange/20 mb-4">
            <i class="fa-solid fa-store mr-1.5 text-[11px]"></i> Direct Retail Storefront
        </span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-pulse-navy tracking-tight leading-tight">
            About ShopPulss
        </h1>
        <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Your destination for curated consumer technology, gadgets, and everyday lifestyle essentials in Pakistan, backed by central warehouse fulfillment and direct customer support.
        </p>
    </div>
</section>

{{-- Main Content & Principles --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">

        {{-- Left Narrative (7 Cols) --}}
        <div class="lg:col-span-7 space-y-8">
            <div class="bg-white rounded-3xl border border-pulse-border p-6 sm:p-10 shadow-subtle space-y-6">
                <h2 class="text-xl sm:text-2xl font-black text-pulse-navy">
                    A Fresh Approach to Online Shopping in Pakistan
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Online shopping in Pakistan often involves unverified third-party sellers, misleading product photos, and uncertain post-purchase support. ShopPulss was created to solve these frustrations through a straightforward direct-retail model.
                </p>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Every product in our catalog is stocked, evaluated, and dispatched directly from our central fulfillment center in Karachi, Pakistan. By managing our own inventory directly, we ensure what you see on your screen matches the product that arrives at your doorstep.
                </p>

                <h3 class="text-lg font-bold text-pulse-navy pt-2">
                    How We Operate
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-pulse-teal/10 text-pulse-teal flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <h4 class="text-xs font-bold text-pulse-navy">Intake Quality Check</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">Items undergo physical inspection and functional verification prior to catalog listing.</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-pulse-orange/10 text-pulse-orange flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-boxes-packing"></i>
                        </div>
                        <h4 class="text-xs font-bold text-pulse-navy">Central Fulfillment</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">All orders are packed in secure protective flyers directly from our Karachi logistics hub.</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <h4 class="text-xs font-bold text-pulse-navy">Nationwide COD</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">Pay cash directly to courier personnel at your doorstep anywhere across Pakistan.</p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-rotate-left"></i>
                        </div>
                        <h4 class="text-xs font-bold text-pulse-navy">7-Day Return Help</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">Simple return assistance for defective, damaged, or mismatched items.</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-base font-bold text-pulse-navy mb-2">Future Scalability</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        While our primary focus is providing reliable service across Pakistan, ShopPulss may expand its catalog depth, fulfillment facilities, and regional reach over time as our technology infrastructure grows.
                    </p>
                </div>
            </div>
        </div>

        {{-- Right Column: Categories & Support (5 Cols) --}}
        <div class="lg:col-span-5 space-y-6">
            {{-- Explore Active Catalog Categories --}}
            <div class="bg-white rounded-3xl border border-pulse-border p-6 sm:p-8 shadow-subtle space-y-4">
                <h2 class="text-base font-black text-pulse-navy flex items-center">
                    <i class="fa-solid fa-layer-group text-pulse-teal mr-2 text-sm"></i>
                    Explore Popular Categories
                </h2>
                <p class="text-xs text-slate-500">
                    Discover products across our active direct-fulfillment catalog:
                </p>
                <div class="space-y-2.5 pt-1">
                    @foreach($categories as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}" class="flex items-center justify-between p-3 rounded-2xl border border-slate-100 hover:border-pulse-orange hover:bg-slate-50 transition-all group">
                            <div class="flex items-center space-x-3">
                                @if($cat->image_url)
                                    <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-9 h-9 rounded-xl object-cover">
                                @else
                                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-xs font-bold">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-xs font-bold text-slate-800 group-hover:text-pulse-orange transition-colors">{{ $cat->name }}</span>
                                    <span class="block text-[10px] text-slate-400">{{ $cat->children->count() }} subcategories</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-arrow-right text-xs text-slate-300 group-hover:text-pulse-orange group-hover:translate-x-1 transition-all"></i>
                        </a>
                    @endforeach
                </div>
                <div class="pt-3">
                    <a href="{{ route('products.index') }}" class="block text-center w-full py-3 rounded-xl bg-pulse-orange hover:bg-pulse-orange-dark text-white font-bold text-xs uppercase tracking-wider transition-colors shadow-2xs">
                        Browse Full Catalog
                    </a>
                </div>
            </div>

            {{-- Policy Transparency Quick Links --}}
            <div class="bg-slate-100 rounded-3xl p-6 border border-slate-200 text-xs space-y-3">
                <h3 class="font-bold text-pulse-navy">Published Store Policies</h3>
                <ul class="space-y-2 text-slate-600">
                    <li><a href="{{ url('/shipping-delivery-policy') }}" class="hover:text-pulse-orange flex items-center"><i class="fa-solid fa-angle-right text-[10px] text-slate-400 mr-2"></i> Shipping & Nationwide Logistics Policy</a></li>
                    <li><a href="{{ url('/return-refund-policy') }}" class="hover:text-pulse-orange flex items-center"><i class="fa-solid fa-angle-right text-[10px] text-slate-400 mr-2"></i> 7-Day Return & Refund Process</a></li>
                    <li><a href="{{ url('/payment-policy') }}" class="hover:text-pulse-orange flex items-center"><i class="fa-solid fa-angle-right text-[10px] text-slate-400 mr-2"></i> Cash on Delivery & Payment Policy</a></li>
                    <li><a href="{{ url('/privacy-policy') }}" class="hover:text-pulse-orange flex items-center"><i class="fa-solid fa-angle-right text-[10px] text-slate-400 mr-2"></i> Customer Data & Privacy Policy</a></li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
