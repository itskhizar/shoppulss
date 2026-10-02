@extends('layouts.app')

@section('title', 'ShopPulss - Discover Products You\'ll Love | Direct Retail Store Pakistan')
@section('description', 'Shop authentic electronics, smart gadgets, fashion, and lifestyle essentials shipped directly from our central Karachi fulfilment warehouse.')

@section('content')

{{-- 1. HERO SECTION --}}
<section class="relative overflow-hidden py-10 lg:py-16 bg-[#F6F7FB]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">

            {{-- Left Column: Headline, Trust & CTAs --}}
            <div class="lg:col-span-6 z-10 space-y-6">

                {{-- Trending Badge --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#FFF1EA] border border-[#FF5A1F]/20 text-[#FF5A1F] text-xs font-black tracking-wide uppercase">
                    <span class="w-2 h-2 rounded-full bg-[#FF5A1F] animate-pulse"></span>
                    <span>Direct Retail • Zero Middlemen</span>
                </div>

                {{-- Hero Headline --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-[#161616] tracking-tight leading-[1.12]">
                    Discover Products<br>
                    <span class="text-orange-gradient">You'll Love.</span>
                </h1>

                {{-- Hero Subtitle --}}
                <p class="text-gray-600 text-sm sm:text-base leading-relaxed max-w-lg">
                    Shop direct authentic electronics, smart gadgets, and lifestyle essentials. 100% genuine inventory inspected and shipped straight from our central fulfillment hub nationwide.
                </p>

                {{-- CTAs --}}
                <div class="flex flex-wrap items-center gap-3.5 pt-2">
                    <a
                        href="{{ route('products.index') }}"
                        id="hero-shop-now"
                        class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-full text-white font-bold text-sm bg-gradient-to-r from-[#FF6B1F] to-[#FF4A0A] hover:opacity-95 shadow-sp-orange transition-all hover:scale-102 active:scale-98"
                    >
                        <span>Shop Now</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>

                    <a
                        href="#categories"
                        id="hero-categories"
                        class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full font-bold text-sm text-[#0F1654] bg-white border border-[#E6E8F2] hover:bg-gray-50 shadow-xs transition-all hover:scale-102"
                    >
                        Explore Collection
                    </a>
                </div>

                {{-- Social Proof & Trust Metric --}}
                <div class="pt-4 flex items-center gap-3.5 border-t border-[#E6E8F2]/80">
                    <div class="flex -space-x-2">
                        <img class="w-8 h-8 rounded-full border-2 border-white object-cover" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Customer">
                        <img class="w-8 h-8 rounded-full border-2 border-white object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80" alt="Customer">
                        <img class="w-8 h-8 rounded-full border-2 border-white object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80" alt="Customer">
                        <div class="w-8 h-8 rounded-full border-2 border-white bg-[#0AA6B7] text-white flex items-center justify-center text-[10px] font-bold">
                            +50k
                        </div>
                    </div>
                    <div class="text-xs">
                        <div class="flex items-center text-amber-400 text-xs">
                            ★★★★★
                        </div>
                        <span class="text-gray-500 font-medium">Loved by <strong>50,000+</strong> happy buyers nationwide</span>
                    </div>
                </div>

            </div>

            {{-- Right Column: Organic 3D Orange Curve + Model + Dynamic Floating Cards --}}
            <div class="lg:col-span-6 relative flex items-center justify-center min-h-[460px] lg:min-h-[540px]">

                {{-- 3D Glossy Orange Curve / Blob Backdrop --}}
                <div class="hero-curve-blob"></div>

                {{-- Main Model Showcase --}}
                <div class="relative z-10 w-full max-w-[380px] sm:max-w-[420px] aspect-[4/5] rounded-[32px] overflow-hidden shadow-2xl border-4 border-white bg-white">
                    <img
                        src="{{ asset('images/hero-model.jpg') }}"
                        alt="ShopPulss Curated Essentials"
                        class="w-full h-full object-cover object-top transition-transform duration-700 hover:scale-103"
                    >
                    {{-- Bottom Pill on Model --}}
                    <div class="absolute bottom-4 inset-x-4 py-2.5 px-4 rounded-2xl bg-white/95 backdrop-blur-md shadow-lg border border-white/80 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                            <span class="font-bold text-[#0F1654]">100% Genuine Brands Sourced</span>
                        </div>
                        <span class="text-[#FF5A1F] font-black">Karachi Hub</span>
                    </div>
                </div>

                {{-- Dynamic Floating Preview Product Cards from Backend ($heroProducts) --}}
                @php
                    $hProd1 = $heroProducts->get(0) ?? $trendingProducts->get(0);
                    $hProd2 = $heroProducts->get(1) ?? $trendingProducts->get(1);
                    $hProd3 = $heroProducts->get(2) ?? $deals->get(0);
                @endphp

                {{-- Floating Card 1: Top Right --}}
                @if($hProd1)
                <a
                    href="{{ route('products.show', $hProd1->slug ?? $hProd1->id) }}"
                    class="floating-product-card absolute -top-4 -right-2 sm:right-2 z-20 p-2.5 flex items-center gap-3 hidden sm:flex max-w-[210px]"
                >
                    <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 p-1 flex-shrink-0 flex items-center justify-center overflow-hidden">
                        <img src="{{ $hProd1->images->first()?->image_url ?? 'https://placehold.co/80' }}" alt="{{ $hProd1->name }}" class="w-full h-full object-contain">
                    </div>
                    <div class="min-w-0 pr-1">
                        <div class="text-[11px] font-bold text-gray-900 truncate leading-tight">{{ $hProd1->name }}</div>
                        <div class="text-xs font-black text-[#FF5A1F] mt-0.5">Rs. {{ number_format($hProd1->sale_price ?? $hProd1->regular_price) }}</div>
                    </div>
                </a>
                @endif

                {{-- Floating Card 2: Middle Left --}}
                @if($hProd2)
                <a
                    href="{{ route('products.show', $hProd2->slug ?? $hProd2->id) }}"
                    class="floating-product-card absolute top-1/3 -left-4 sm:-left-6 z-20 p-2.5 flex items-center gap-3 hidden sm:flex max-w-[200px]"
                >
                    <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 p-1 flex-shrink-0 flex items-center justify-center overflow-hidden">
                        <img src="{{ $hProd2->images->first()?->image_url ?? 'https://placehold.co/80' }}" alt="{{ $hProd2->name }}" class="w-full h-full object-contain">
                    </div>
                    <div class="min-w-0 pr-1">
                        <div class="text-[11px] font-bold text-gray-900 truncate leading-tight">{{ $hProd2->name }}</div>
                        <div class="text-xs font-black text-[#0F1654] mt-0.5">Rs. {{ number_format($hProd2->sale_price ?? $hProd2->regular_price) }}</div>
                    </div>
                </a>
                @endif

                {{-- Floating Card 3: Bottom Right --}}
                @if($hProd3)
                <a
                    href="{{ route('products.show', $hProd3->slug ?? $hProd3->id) }}"
                    class="floating-product-card absolute bottom-12 -right-4 sm:-right-6 z-20 p-2.5 flex items-center gap-3 hidden sm:flex max-w-[220px]"
                >
                    <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 p-1 flex-shrink-0 flex items-center justify-center overflow-hidden">
                        <img src="{{ $hProd3->images->first()?->image_url ?? 'https://placehold.co/80' }}" alt="{{ $hProd3->name }}" class="w-full h-full object-contain">
                    </div>
                    <div class="min-w-0 pr-1">
                        <div class="text-[11px] font-bold text-gray-900 truncate leading-tight">{{ $hProd3->name }}</div>
                        <div class="text-xs font-black text-[#0AA6B7] mt-0.5">Rs. {{ number_format($hProd3->sale_price ?? $hProd3->regular_price) }}</div>
                    </div>
                </a>
                @endif

            </div>

        </div>
    </div>
</section>

{{-- 2. TRUST STRIP (Peach #FFF1EA) --}}
<section class="py-6 px-4 bg-[#FFF1EA] border-y border-[#FF5A1F]/15">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center text-[#FF5A1F] shadow-xs flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-xs sm:text-sm text-[#0F1654]">Free Shipping</h4>
                    <p class="text-[11px] text-gray-500 mt-0.5">On all orders over Rs. 2,500</p>
                </div>
            </div>

            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center text-[#0AA6B7] shadow-xs flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-xs sm:text-sm text-[#0F1654]">Secure Payments</h4>
                    <p class="text-[11px] text-gray-500 mt-0.5">Cash on Delivery & Bank</p>
                </div>
            </div>

            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center text-[#FF5A1F] shadow-xs flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-xs sm:text-sm text-[#0F1654]">Easy Returns</h4>
                    <p class="text-[11px] text-gray-500 mt-0.5">7-day hassle-free guarantee</p>
                </div>
            </div>

            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center text-[#25D366] shadow-xs flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413A11.815 11.815 0 0012.05 0z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-xs sm:text-sm text-[#0F1654]">24/7 Fast Support</h4>
                    <p class="text-[11px] text-gray-500 mt-0.5">WhatsApp & phone helpline</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- 3. FEATURED CATEGORIES --}}
<section id="categories" class="py-14 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-[#FF5A1F] mb-1">Explore Departments</p>
                <h2 class="text-2xl sm:text-3xl font-black text-[#0F1654]">Shop by Categories</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs sm:text-sm font-bold text-[#FF5A1F] hover:text-[#FF4A0A] flex items-center gap-1 transition-colors">
                <span>View All Categories</span>
                <span>→</span>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($featuredCategories as $cat)
                <a
                    href="{{ route('categories.show', $cat->slug) }}"
                    id="cat-{{ $cat->id }}"
                    class="group p-4 rounded-2xl bg-[#F6F7FB] border border-[#E6E8F2] hover:border-[#FF5A1F]/30 hover:bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-sp-card flex flex-col items-center text-center"
                >
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white border border-[#E6E8F2] p-2 flex items-center justify-center overflow-hidden mb-3 shadow-2xs group-hover:scale-108 transition-transform duration-300">
                        @if($cat->image_url)
                            <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-contain">
                        @else
                            <svg class="w-8 h-8 text-[#0AA6B7]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        @endif
                    </div>
                    <h3 class="font-bold text-xs sm:text-sm text-[#161616] group-hover:text-[#FF5A1F] transition-colors line-clamp-1">
                        {{ $cat->name }}
                    </h3>
                    <span class="text-[11px] font-semibold text-gray-400 group-hover:text-[#0AA6B7] mt-1 transition-colors flex items-center gap-1">
                        Shop Now →
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- 4. DEAL OF THE DAY HIGHLIGHT BANNER --}}
@php
    $dealHighlight = $deals->first();
@endphp
@if($dealHighlight)
@php
    $dealDiscount = $dealHighlight->regular_price > 0 && $dealHighlight->sale_price
        ? (int) round((($dealHighlight->regular_price - $dealHighlight->sale_price) / $dealHighlight->regular_price) * 100)
        : 35;
    $remainingSeconds = $dealHighlight->remainingDealSeconds() > 0 ? $dealHighlight->remainingDealSeconds() : 52319;
@endphp
<section id="deal-of-the-day" class="py-10 px-4 bg-[#F6F7FB]">
    <div class="max-w-7xl mx-auto">
        <div class="rounded-3xl bg-[#FFF1EA] border border-[#FF5A1F]/20 p-6 sm:p-10 lg:p-12 shadow-sp-card relative overflow-hidden">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                {{-- Left Side: Deal Offer & Countdown --}}
                <div class="lg:col-span-7 space-y-5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white text-[#FF5A1F] text-xs font-black shadow-2xs">
                        <span>⚡ DEAL OF THE DAY</span>
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-black text-[#0F1654] leading-tight">
                        Grab It Before<br>It's Gone!
                    </h2>

                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed max-w-md">
                        Special direct warehouse allocation at unbeatable prices. Limited stock available with express dispatch across 150+ cities.
                    </p>

                    {{-- Countdown Timer --}}
                    <div>
                        <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Deal Ends In</div>
                        <div id="deal-countdown" class="flex items-center gap-2.5">
                            <div class="bg-white border border-[#E6E8F2] rounded-xl px-3.5 py-2 text-center shadow-xs min-w-[56px]">
                                <span class="block text-lg font-black text-[#0F1654]" id="cd-hours">08</span>
                                <span class="text-[10px] uppercase font-bold text-gray-400">Hours</span>
                            </div>
                            <span class="text-lg font-black text-[#FF5A1F]">:</span>
                            <div class="bg-white border border-[#E6E8F2] rounded-xl px-3.5 py-2 text-center shadow-xs min-w-[56px]">
                                <span class="block text-lg font-black text-[#0F1654]" id="cd-mins">45</span>
                                <span class="text-[10px] uppercase font-bold text-gray-400">Mins</span>
                            </div>
                            <span class="text-lg font-black text-[#FF5A1F]">:</span>
                            <div class="bg-white border border-[#E6E8F2] rounded-xl px-3.5 py-2 text-center shadow-xs min-w-[56px]">
                                <span class="block text-lg font-black text-[#0F1654]" id="cd-secs">19</span>
                                <span class="text-[10px] uppercase font-bold text-gray-400">Secs</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Row --}}
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $dealHighlight->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full text-white text-sm font-bold bg-gradient-to-r from-[#FF6B1F] to-[#FF4A0A] hover:opacity-95 shadow-sp-orange transition-all hover:scale-102"
                            >
                                <span>Buy The Deal</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>

                        <div class="text-xs font-semibold text-[#0F1654] flex items-center gap-1.5 bg-white/80 px-3.5 py-2 rounded-full border border-white">
                            <span class="w-2 h-2 rounded-full bg-[#FF5A1F] animate-ping"></span>
                            <span>Only <strong>{{ max(2, (int) $dealHighlight->stock_quantity) }}</strong> items left in stock</span>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Highlight Product Card --}}
                <div class="lg:col-span-5 flex justify-center">
                    <div class="bg-white rounded-[26px] p-6 shadow-2xl border border-white max-w-sm w-full relative">
                        {{-- Discount Badge --}}
                        <span class="absolute top-4 right-4 z-10 px-3 py-1 rounded-full text-xs font-black text-white bg-gradient-to-r from-[#FF6B1F] to-[#FF4A0A] shadow-sm">
                            -{{ $dealDiscount }}% OFF
                        </span>

                        {{-- Product Image --}}
                        <a href="{{ route('products.show', $dealHighlight->slug ?? $dealHighlight->id) }}" class="aspect-square rounded-2xl bg-gray-50 p-4 block overflow-hidden mb-4 group">
                            <img
                                src="{{ $dealHighlight->images->first()?->image_url ?? 'https://placehold.co/400' }}"
                                alt="{{ $dealHighlight->name }}"
                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500"
                            >
                        </a>

                        {{-- Product Details --}}
                        <div class="space-y-2">
                            <div class="text-[11px] font-bold text-[#0AA6B7] uppercase tracking-wider">
                                {{ $dealHighlight->category?->name ?? 'Special Allocation' }}
                            </div>
                            <h3 class="text-base font-bold text-gray-900 leading-snug line-clamp-1">
                                <a href="{{ route('products.show', $dealHighlight->slug ?? $dealHighlight->id) }}" class="hover:text-[#FF5A1F] transition-colors">
                                    {{ $dealHighlight->name }}
                                </a>
                            </h3>

                            <div class="flex items-baseline gap-3 pt-1">
                                <span class="text-2xl font-black text-[#0F1654]">
                                    Rs. {{ number_format($dealHighlight->sale_price ?? $dealHighlight->regular_price) }}
                                </span>
                                @if($dealHighlight->sale_price)
                                    <span class="text-xs text-gray-400 line-through">
                                        Rs. {{ number_format($dealHighlight->regular_price) }}
                                    </span>
                                    <span class="text-xs font-bold text-[#FF5A1F]">
                                        Save Rs. {{ number_format($dealHighlight->regular_price - $dealHighlight->sale_price) }}
                                    </span>
                                @endif
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST" class="pt-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $dealHighlight->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full py-3 rounded-xl bg-[#0F1654] hover:bg-[#16206E] text-white text-xs font-bold transition-all shadow-md flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    <span>Add Deal to Cart</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
@endif

{{-- 5. TRENDING PRODUCTS --}}
<section id="trending" class="py-14 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-[#FF5A1F] mb-1">Top Picks</p>
                <h2 class="text-2xl sm:text-3xl font-black text-[#0F1654]">Trending Products</h2>
            </div>
            <a href="{{ route('products.index', ['sort' => 'popular']) }}" id="view-all-trending" class="text-xs sm:text-sm font-bold text-[#FF5A1F] hover:text-[#FF4A0A] flex items-center gap-1 transition-colors">
                <span>View All Trending</span>
                <span>→</span>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            @forelse($trendingProducts->take(8) as $product)
                @include('components.product-card', ['product' => $product, 'badge' => 'Trending'])
            @empty
                <div class="col-span-full py-12 text-center bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-sm text-gray-500">Trending inventory is currently updating. Check back shortly!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- 6. DUAL PROMOTIONAL BANNERS --}}
<section class="py-6 px-4 bg-[#F6F7FB]">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Banner 1: Orange Flash Sale --}}
            <div class="rounded-3xl p-8 sm:p-10 bg-gradient-to-r from-[#FF6B1F] to-[#FF4A0A] text-white flex flex-col justify-between shadow-sp-orange relative overflow-hidden min-h-[220px]">
                <div class="space-y-2 z-10">
                    <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-white text-[11px] font-black uppercase tracking-wider">Warehouse Clearance</span>
                    <h3 class="text-2xl sm:text-3xl font-black leading-tight">
                        Flash Sale<br>Up To 60% Off
                    </h3>
                    <p class="text-white/80 text-xs max-w-xs">Limited allocation direct from certified manufacturers. First come, first served.</p>
                </div>
                <div class="pt-4 z-10">
                    <a href="{{ route('products.index', ['sort' => 'sale']) }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-white text-[#FF5A1F] text-xs font-bold hover:bg-gray-50 shadow-md transition-transform hover:scale-105">
                        <span>Shop Flash Deals</span>
                        <span>→</span>
                    </a>
                </div>
                {{-- Decorative background circle --}}
                <div class="absolute -right-10 -bottom-10 w-52 h-52 rounded-full bg-white/10 pointer-events-none"></div>
            </div>

            {{-- Banner 2: Navy Direct Sourcing --}}
            <div class="rounded-3xl p-8 sm:p-10 bg-gradient-to-r from-[#0F1654] to-[#1A237E] text-white flex flex-col justify-between shadow-sp-card relative overflow-hidden min-h-[220px]">
                <div class="space-y-2 z-10">
                    <span class="inline-block px-3 py-1 rounded-full bg-[#0AA6B7]/30 text-[#0AA6B7] text-[11px] font-black uppercase tracking-wider">Direct Sourcing</span>
                    <h3 class="text-2xl sm:text-3xl font-black leading-tight">
                        Curated Direct<br>Retail Excellence
                    </h3>
                    <p class="text-blue-100/70 text-xs max-w-xs">Zero third-party seller headaches. 100% verified single-origin retail inventory.</p>
                </div>
                <div class="pt-4 z-10">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#0AA6B7] text-white text-xs font-bold hover:bg-[#088F9E] shadow-md transition-transform hover:scale-105">
                        <span>Explore Sourcing</span>
                        <span>→</span>
                    </a>
                </div>
                <div class="absolute -right-10 -bottom-10 w-52 h-52 rounded-full bg-white/5 pointer-events-none"></div>
            </div>

        </div>
    </div>
</section>

{{-- 7. NEW ARRIVALS --}}
<section id="new-arrivals" class="py-14 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-[#0AA6B7] mb-1">Fresh Inventory</p>
                <h2 class="text-2xl sm:text-3xl font-black text-[#0F1654]">New Arrivals</h2>
            </div>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="text-xs sm:text-sm font-bold text-[#FF5A1F] hover:text-[#FF4A0A] flex items-center gap-1 transition-colors">
                <span>View All New</span>
                <span>→</span>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            @forelse($newArrivals->take(4) as $product)
                @include('components.product-card', ['product' => $product, 'badge' => 'New'])
            @empty
                @foreach($trendingProducts->take(4) as $product)
                    @include('components.product-card', ['product' => $product, 'badge' => 'New'])
                @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- 8. DEALS & SPECIAL OFFERS --}}
<section id="deals" class="py-14 px-4 bg-[#F6F7FB]">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-[#FF5A1F] mb-1">Direct Markdowns</p>
                <h2 class="text-2xl sm:text-3xl font-black text-[#0F1654]">Deals & Special Offers</h2>
            </div>
            <a href="{{ route('products.index', ['sort' => 'sale']) }}" class="text-xs sm:text-sm font-bold text-[#FF5A1F] hover:text-[#FF4A0A] flex items-center gap-1 transition-colors">
                <span>View All Deals</span>
                <span>→</span>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            @forelse($deals->take(4) as $product)
                @php
                    $dPercent = $product->regular_price > 0 && $product->sale_price
                        ? round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100)
                        : 20;
                @endphp
                <div class="bg-white rounded-2xl md:rounded-[22px] border border-[#E6E8F2] overflow-hidden flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-sp-card-hover" id="deal-product-{{ $product->id }}">
                    <div class="relative aspect-square bg-[#F8FAFC] p-3 overflow-hidden">
                        <span class="absolute top-3 left-3 z-10 px-2.5 py-0.5 rounded-full text-[11px] font-black text-white bg-gradient-to-r from-[#FF6B1F] to-[#FF4A0A] shadow-xs">
                            Save {{ $dPercent }}%
                        </span>
                        <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="w-full h-full block">
                            <img
                                src="{{ $product->images->first()?->image_url ?? 'https://placehold.co/300' }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-contain mix-blend-multiply group-hover:scale-108 transition-transform duration-500"
                                loading="lazy"
                            >
                        </a>
                    </div>

                    <div class="p-4 flex flex-col flex-1 justify-between">
                        <div>
                            <span class="text-[11px] text-gray-400 font-medium block mb-1">{{ $product->category?->name ?? 'Special' }}</span>
                            <h3 class="text-xs sm:text-sm font-bold text-[#161616] line-clamp-2 mb-2 group-hover:text-[#FF5A1F] transition-colors">
                                <a href="{{ route('products.show', $product->slug ?? $product->id) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <div class="flex items-baseline gap-2 mb-3">
                                <span class="text-base sm:text-lg font-black text-[#0F1654]">
                                    Rs. {{ number_format($product->sale_price ?? $product->regular_price) }}
                                </span>
                                @if($product->sale_price)
                                    <span class="text-xs text-gray-400 line-through">
                                        Rs. {{ number_format($product->regular_price) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button
                                type="submit"
                                id="deal-order-{{ $product->id }}"
                                class="w-full py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold text-white flex items-center justify-center gap-2 bg-gradient-to-r from-[#FF6B1F] to-[#FF4A0A] hover:opacity-95 shadow-xs transition-all active:scale-97"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>Order Deal</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-gray-100">
                    <p class="text-sm text-gray-500">Flash markdown deals will be released shortly!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- 9. WHY SHOP DIRECTLY WITH SHOPPULSS (Deep Navy Band #0F1654) --}}
<section id="why-us" class="py-16 px-4 bg-[#0F1654] text-white">
    <div class="max-w-7xl mx-auto text-center">
        <p class="text-xs font-bold uppercase tracking-wider text-[#0AA6B7] mb-2">Direct Retail Philosophy</p>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black mb-3">Why Shop Directly with ShopPulss?</h2>
        <p class="text-blue-200/80 text-xs sm:text-sm max-w-2xl mx-auto mb-12 leading-relaxed">
            We operate as an exclusive direct-to-consumer store. We source, inspect, package, and dispatch 100% of our products ourselves to guarantee trust and eliminate counterfeit dropshipping.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors text-left flex flex-col justify-between">
                <div class="w-12 h-12 rounded-2xl bg-[#0AA6B7]/20 text-[#0AA6B7] flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-white mb-1.5">100% Brand Authentic</h3>
                    <p class="text-blue-100/70 text-xs leading-relaxed">Direct relationships with official brand distributors. Zero random third-party vendors.</p>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors text-left flex flex-col justify-between">
                <div class="w-12 h-12 rounded-2xl bg-[#FF5A1F]/20 text-[#FF5A1F] flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-white mb-1.5">Cash on Delivery</h3>
                    <p class="text-blue-100/70 text-xs leading-relaxed">Inspect your sealed shipment and pay comfortably at your doorstep anywhere in Pakistan.</p>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors text-left flex flex-col justify-between">
                <div class="w-12 h-12 rounded-2xl bg-[#0AA6B7]/20 text-[#0AA6B7] flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-white mb-1.5">7-Day Easy Returns</h3>
                    <p class="text-blue-100/70 text-xs leading-relaxed">Full refund or replacement managed directly by our Karachi customer support team.</p>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors text-left flex flex-col justify-between">
                <div class="w-12 h-12 rounded-2xl bg-[#FF5A1F]/20 text-[#FF5A1F] flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H11a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h4l2 4v4h-6V7z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-white mb-1.5">Express Dispatch</h3>
                    <p class="text-blue-100/70 text-xs leading-relaxed">Parcels shipped same-day via TCS, Leopards, and Swyft with real-time tracking.</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- 10. NEWSLETTER & VIP ACCESS BANNER --}}
<section id="newsletter" class="py-12 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="rounded-3xl p-8 sm:p-12 bg-gradient-to-r from-[#FFF1EA] via-white to-[#F6F7FB] border border-[#FF5A1F]/20 flex flex-col lg:flex-row items-center justify-between gap-8 shadow-xs">
            <div class="space-y-1 text-center lg:text-left">
                <h3 class="text-2xl sm:text-3xl font-black text-[#0F1654]">Get Exclusive Deals First! 🎉</h3>
                <p class="text-gray-500 text-xs sm:text-sm">Subscribe to receive instant warehouse alerts and secret flash markdown coupons.</p>
            </div>

            <form class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto" id="hero-newsletter-form" onsubmit="event.preventDefault(); alert('Subscribed to ShopPulss VIP updates!');">
                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email address..."
                    required
                    class="w-full sm:w-80 px-4 py-3 rounded-full bg-white border border-[#E6E8F2] text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-[#FF5A1F] focus:ring-2 focus:ring-[#FF5A1F]/20"
                >
                <button
                    type="submit"
                    id="newsletter-cta-btn"
                    class="px-8 py-3 rounded-full text-white text-xs font-bold bg-[#0F1654] hover:bg-[#16206E] shadow-md transition-all hover:scale-102 flex-shrink-0"
                >
                    Subscribe →
                </button>
            </form>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Live Countdown Timer for Deals
    function initDealCountdown() {
        let remainingSeconds = {{ isset($remainingSeconds) ? $remainingSeconds : 52319 }};

        function tick() {
            const h = Math.floor(remainingSeconds / 3600);
            const m = Math.floor((remainingSeconds % 3600) / 60);
            const s = Math.floor(remainingSeconds % 60);

            const hEl = document.getElementById('cd-hours');
            const mEl = document.getElementById('cd-mins');
            const sEl = document.getElementById('cd-secs');

            if (hEl) hEl.textContent = String(h).padStart(2, '0');
            if (mEl) mEl.textContent = String(m).padStart(2, '0');
            if (sEl) sEl.textContent = String(s).padStart(2, '0');

            if (remainingSeconds > 0) {
                remainingSeconds--;
                setTimeout(tick, 1000);
            }
        }
        tick();
    }
    initDealCountdown();
</script>
@endpush
