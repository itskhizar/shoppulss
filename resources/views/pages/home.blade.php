@extends('layouts.app')

@section('title', 'ShopPulss | Direct Retail Store - 100% Authentic Products & Nationwide COD')
@section('description', 'Shop authentic electronics, smart gadgets, fashion, and lifestyle essentials shipped directly from our central Karachi fulfillment hub. Cash on Delivery available nationwide across Pakistan.')

@section('content')

@php
    // Hero featured product
    $heroProduct = $heroProducts->first() ?? $trendingProducts->first() ?? $deals->first();

    // Deal of the Day product
    $dealProduct = $deals->first() ?? $trendingProducts->first();
    $dealDiscount = 0;
    if ($dealProduct) {
        $hasDealSale = $dealProduct->sale_price && $dealProduct->sale_price > 0 && $dealProduct->sale_price < $dealProduct->regular_price;
        $dealDiscount = $dealProduct->discount_percentage > 0
            ? $dealProduct->discount_percentage
            : ($hasDealSale ? (int) round((($dealProduct->regular_price - $dealProduct->sale_price) / $dealProduct->regular_price) * 100) : 0);
        $dealSavings = $dealProduct->regular_price - ($dealProduct->sale_price ?? $dealProduct->regular_price);
    }

    // Category visual mappings
    $catVisuals = [
        'electronics' => ['icon' => 'fa-headphones', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
        'fashion' => ['icon' => 'fa-shirt', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
        'fashion-apparel' => ['icon' => 'fa-shirt', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
        'beauty-personal-care' => ['icon' => 'fa-spa', 'bg' => 'bg-rose-50', 'text' => 'text-rose-500'],
        'beauty' => ['icon' => 'fa-spa', 'bg' => 'bg-rose-50', 'text' => 'text-rose-500'],
        'sports-outdoors' => ['icon' => 'fa-dumbbell', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
        'fitness' => ['icon' => 'fa-dumbbell', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
        'home-living' => ['icon' => 'fa-couch', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
        'home-decor' => ['icon' => 'fa-couch', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
        'accessories' => ['icon' => 'fa-stopwatch-20', 'bg' => 'bg-teal-50', 'text' => 'text-pulse-teal'],
        'toys-games' => ['icon' => 'fa-gamepad', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-600'],
        'automotive' => ['icon' => 'fa-car', 'bg' => 'bg-orange-50', 'text' => 'text-pulse-orange'],
    ];
@endphp

{{-- 1. BEGIN: HeroSection --}}
<section class="hero-pattern relative overflow-hidden py-10 lg:py-16 border-b border-slate-200" data-purpose="hero-banner">
    {{-- Glow decorations --}}
    <div class="absolute -top-32 -left-20 w-96 h-96 bg-pulse-orange/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 -right-32 w-96 h-96 bg-pulse-teal/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            {{-- Left Column: Copy & Value Proposition --}}
            <div class="lg:col-span-6 space-y-6">
                {{-- Tag Eyebrow --}}
                <div class="inline-flex items-center space-x-2 bg-pulse-orange-light border border-pulse-orange/20 px-3.5 py-1.5 rounded-full text-xs font-bold text-pulse-orange">
                    <span class="w-2 h-2 rounded-full bg-pulse-orange pulse-dot"></span>
                    <span>DIRECT RETAIL · ZERO MIDDLEMEN</span>
                </div>

                {{-- Bold Headline --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-pulse-navy tracking-tight leading-[1.08]">
                    Discover Products <br>
                    <span class="bg-gradient-to-r from-pulse-orange via-amber-500 to-pulse-orange bg-clip-text text-transparent">
                        You'll Love.
                    </span>
                </h1>

                {{-- Value Statement --}}
                <p class="text-base sm:text-lg text-slate-600 font-body max-w-xl leading-relaxed">
                    Shop direct authentic electronics, smart gadgets, and lifestyle essentials. 
                    <strong class="text-slate-800 font-semibold">100% genuine inventory</strong> inspected and shipped straight from our central fulfillment hub nationwide.
                </p>

                {{-- Action CTAs --}}
                <div class="pt-2 flex flex-wrap items-center gap-4">
                    <a class="bg-pulse-orange hover:bg-pulse-orange-dark text-white px-8 py-3.5 rounded-xl font-bold text-sm tracking-wide transition-all shadow-orange-glow hover:shadow-lg flex items-center space-x-2 group" href="#trending">
                        <span>Shop Now</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a class="bg-white hover:bg-slate-50 text-pulse-navy border border-slate-300 hover:border-slate-400 px-7 py-3.5 rounded-xl font-bold text-sm tracking-wide transition-all shadow-subtle" href="#categories">
                        Explore Collection
                    </a>
                </div>

                {{-- Social Proof Cluster --}}
                <div class="pt-4 flex flex-wrap items-center gap-4 sm:gap-6 border-t border-slate-200/80">
                    <div class="flex -space-x-2">
                        <div class="w-9 h-9 rounded-full bg-pulse-navy text-white font-bold text-xs flex items-center justify-center border-2 border-white">HK</div>
                        <div class="w-9 h-9 rounded-full bg-amber-500 text-white font-bold text-xs flex items-center justify-center border-2 border-white">SM</div>
                        <div class="w-9 h-9 rounded-full bg-pulse-teal text-white font-bold text-xs flex items-center justify-center border-2 border-white">AR</div>
                        <div class="w-9 h-9 rounded-full bg-slate-700 text-white font-bold text-xs flex items-center justify-center border-2 border-white">+5k</div>
                    </div>
                    <div>
                        <div class="flex items-center space-x-1 text-amber-400 text-xs">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star-half-stroke"></i>
                            <span class="font-bold text-slate-800 ml-1">4.9 / 5</span>
                        </div>
                        <span class="text-xs text-slate-500">Loved by 50,000+ happy buyers nationwide</span>
                    </div>
                </div>
            </div>

            {{-- Right Column: Interactive Bento Showcase (Real Featured Product) --}}
            <div class="lg:col-span-6 relative">
                <div class="relative bg-gradient-to-b from-white to-slate-50 rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-card">
                    {{-- Hero Product Stage --}}
                    <div class="relative rounded-2xl overflow-hidden bg-slate-100 flex items-center justify-center h-80 sm:h-96">
                        @if($heroProduct)
                            <a href="{{ route('products.show', $heroProduct->slug ?? $heroProduct->id) }}" class="w-full h-full relative flex items-center justify-center bg-gradient-to-tr from-amber-50/60 to-orange-50/60 p-6 group">
                                <div class="text-center w-full">
                                    <div class="w-48 h-48 mx-auto relative flex items-center justify-center mb-2">
                                        @if($heroProduct->primary_image_url && !str_contains($heroProduct->primary_image_url, 'placeholder'))
                                            <img
                                                src="{{ $heroProduct->primary_image_url }}"
                                                alt="{{ $heroProduct->name }}"
                                                class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-300"
                                            >
                                        @else
                                            <i class="fa-solid fa-headphones-simple text-8xl text-pulse-navy/90 drop-shadow-2xl"></i>
                                        @endif
                                        {{-- Pulse badge --}}
                                        <span class="absolute top-2 right-2 bg-pulse-orange text-white text-[10px] font-black uppercase px-2 py-0.5 rounded-full shadow-xs">
                                            Direct Hub
                                        </span>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-extrabold text-pulse-navy group-hover:text-pulse-orange transition-colors truncate max-w-sm mx-auto">
                                        {{ $heroProduct->name }}
                                    </h3>
                                    <p class="text-xs text-slate-500 truncate max-w-xs mx-auto">
                                        {{ $heroProduct->short_description ?? ($heroProduct->category?->name ?? 'Verified Central Inventory') }}
                                    </p>
                                </div>
                            </a>

                            {{-- Floating Tag 1 (Top Left) --}}
                            <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md border border-slate-200 px-3.5 py-2 rounded-xl shadow-lg flex items-center space-x-3 pointer-events-none">
                                <div class="w-8 h-8 rounded-lg bg-orange-100 text-pulse-orange flex items-center justify-center font-bold text-xs">
                                    <i class="fa-solid fa-bolt"></i>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-slate-400 font-semibold uppercase">{{ $heroProduct->category?->name ?? 'Smart Series' }}</span>
                                    <span class="text-xs font-extrabold text-pulse-navy">Rs. {{ number_format($heroProduct->effective_price) }}</span>
                                </div>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-8 text-center bg-gradient-to-tr from-amber-50/40 to-orange-50/40">
                                <div class="w-20 h-20 rounded-2xl bg-white shadow-card flex items-center justify-center text-pulse-orange text-3xl mb-3">
                                    <i class="fa-solid fa-sparkles"></i>
                                </div>
                                <h3 class="text-base font-extrabold text-pulse-navy">Direct Retail Central Hub</h3>
                                <p class="text-xs text-slate-500 max-w-xs mt-1">100% Genuine Direct Products. Ready for Nationwide COD.</p>
                            </div>
                        @endif

                        {{-- Floating Tag 2 (Top Right) --}}
                        <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-md border border-slate-200 px-3.5 py-2 rounded-xl shadow-lg flex items-center space-x-2.5 pointer-events-none">
                            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">
                                <i class="fa-solid fa-shield-check"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] text-slate-400 font-semibold uppercase">Quality Certified</span>
                                <span class="text-xs font-bold text-slate-800">100% Genuine</span>
                            </div>
                        </div>

                        {{-- Floating Bottom Hub Bar --}}
                        <div class="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md border border-slate-200 px-4 py-2.5 rounded-xl shadow-md flex items-center justify-between pointer-events-none">
                            <div class="flex items-center space-x-2 text-xs font-bold text-pulse-navy">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span>Authentic Stock Verified</span>
                            </div>
                            <div class="text-[11px] font-semibold text-pulse-orange">
                                Express Dispatch <i class="fa-solid fa-bolt ml-0.5"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Bento Mini Grid Below Main Card --}}
                    <div class="grid grid-cols-2 gap-3.5 mt-4">
                        <div class="bg-white border border-slate-200 p-3.5 rounded-xl flex items-center space-x-3 hover:border-pulse-orange/40 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-pulse-navy">Unopened Factory Box</div>
                                <div class="text-[10px] text-slate-500">Intact Seals Guaranteed</div>
                            </div>
                        </div>
                        <div class="bg-white border border-slate-200 p-3.5 rounded-xl flex items-center space-x-3 hover:border-pulse-orange/40 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-orange-50 text-pulse-orange flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid fa-truck-fast"></i>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-pulse-navy">Same Day Dispatch</div>
                                <div class="text-[10px] text-slate-500">For 150+ major cities</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- END: HeroSection --}}

{{-- 2. BEGIN: TrustGuarantees --}}
<section class="py-6 bg-white border-b border-slate-200" data-purpose="trust-badges">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-8">
            <div class="flex items-center space-x-3.5 p-2">
                <div class="w-12 h-12 rounded-xl bg-orange-50 text-pulse-orange flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-pulse-navy">Free Express Shipping</h4>
                    <p class="text-[11px] text-slate-500">On all orders over Rs. 2,500</p>
                </div>
            </div>
            <div class="flex items-center space-x-3.5 p-2">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-pulse-teal flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-pulse-navy">Cash on Delivery</h4>
                    <p class="text-[11px] text-slate-500">Inspect &amp; pay at doorstep</p>
                </div>
            </div>
            <div class="flex items-center space-x-3.5 p-2">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-pulse-navy">7-Day Easy Returns</h4>
                    <p class="text-[11px] text-slate-500">Hassle-free direct policy</p>
                </div>
            </div>
            <div class="flex items-center space-x-3.5 p-2">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold text-pulse-navy">24/7 Priority Support</h4>
                    <p class="text-[11px] text-slate-500">Direct WhatsApp &amp; Helpline</p>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- END: TrustGuarantees --}}

{{-- 3. BEGIN: ShopByCategories --}}
<section class="py-14 bg-pulse-bg" data-purpose="category-grid" id="categories">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold text-pulse-orange uppercase tracking-wider block">Explore Departments</span>
                <h2 class="text-2xl sm:text-3xl font-black text-pulse-navy tracking-tight mt-0.5">Shop by Categories</h2>
            </div>
            <a class="text-xs sm:text-sm font-bold text-pulse-orange hover:text-pulse-orange-dark flex items-center space-x-1.5 transition-colors" href="{{ route('products.index') }}#categories">
                <span>View All Categories</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        {{-- Categories Grid: 6 Pillars from DB --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @forelse($featuredCategories as $cat)
                @php
                    $visual = $catVisuals[$cat->slug] ?? ['icon' => 'fa-shapes', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'];
                    $catProductsCount = $cat->products()->count();
                @endphp
                <a class="group bg-white p-4 rounded-2xl border border-pulse-border text-center hover:shadow-card hover:border-pulse-orange/40 transition-all flex flex-col justify-between" href="{{ route('categories.show', $cat->slug) }}">
                    <div>
                        <div class="w-16 h-16 mx-auto mb-3 rounded-2xl {{ $visual['bg'] }} flex items-center justify-center {{ $visual['text'] }} text-2xl group-hover:scale-110 transition-transform overflow-hidden p-2">
                            @if($cat->image_url && !str_contains($cat->image_url, 'placeholder'))
                                <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-contain mix-blend-multiply" loading="lazy">
                            @else
                                <i class="fa-solid {{ $visual['icon'] }}"></i>
                            @endif
                        </div>
                        <h3 class="text-xs font-bold text-pulse-navy group-hover:text-pulse-orange transition-colors truncate">
                            {{ $cat->name }}
                        </h3>
                    </div>
                    <span class="text-[10px] text-slate-400 font-medium block mt-2">
                        Shop Now →
                    </span>
                </a>
            @empty
                <p class="col-span-full text-center text-xs text-slate-400 py-6">Categories loading from warehouse database...</p>
            @endforelse
        </div>
    </div>
</section>
{{-- END: ShopByCategories --}}

{{-- 4. BEGIN: DealOfTheDaySection --}}
@if($dealProduct)
<section class="py-10 bg-white" data-purpose="deal-of-the-day" id="deals">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-amber-50/80 via-orange-50/60 to-white border border-amber-200/80 rounded-3xl p-6 sm:p-10 shadow-card">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                {{-- Left: Deal Urgency Details --}}
                <div class="lg:col-span-6 space-y-4">
                    <div class="inline-flex items-center space-x-2 bg-pulse-orange text-white text-xs font-extrabold uppercase px-3 py-1 rounded-full shadow-sm">
                        <i class="fa-solid fa-fire"></i>
                        <span>DEAL OF THE DAY</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-pulse-navy tracking-tight leading-tight">
                        Grab It Before <br>It's Gone!
                    </h2>
                    <p class="text-sm text-slate-600 max-w-md">
                        Special direct warehouse allocation at unbeatable prices. Limited stock available with express dispatch across 150+ cities.
                    </p>

                    {{-- Countdown Timer Blocks --}}
                    <div class="pt-2">
                        <span class="text-xs font-bold uppercase text-slate-500 tracking-wider block mb-2">Deal Ends In</span>
                        <div class="flex items-center space-x-2.5 text-center">
                            <div class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 min-w-[64px] shadow-sm">
                                <span class="block text-xl font-black text-pulse-navy" id="deal-hours">14</span>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase">Hours</span>
                            </div>
                            <span class="font-bold text-slate-400">:</span>
                            <div class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 min-w-[64px] shadow-sm">
                                <span class="block text-xl font-black text-pulse-navy" id="deal-minutes">31</span>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase">Mins</span>
                            </div>
                            <span class="font-bold text-slate-400">:</span>
                            <div class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 min-w-[64px] shadow-sm">
                                <span class="block text-xl font-black text-pulse-navy" id="deal-seconds">43</span>
                                <span class="block text-[9px] font-bold text-slate-400 uppercase">Secs</span>
                            </div>
                        </div>
                    </div>

                    {{-- CTA & Inventory Stock Indicator --}}
                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <form action="{{ route('cart.add') }}" method="POST" class="ajax-add-to-cart inline-block">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $dealProduct->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="bg-pulse-orange hover:bg-pulse-orange-dark text-white px-7 py-3 rounded-xl font-bold text-sm shadow-orange-glow transition-all flex items-center space-x-2">
                                <span>Buy The Deal</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </button>
                        </form>
                        <div class="flex items-center space-x-2 text-xs font-bold text-pulse-orange bg-white px-3.5 py-2.5 rounded-xl border border-pulse-orange/30">
                            <span class="w-2 h-2 rounded-full bg-pulse-orange pulse-dot"></span>
                            <span>Only <strong>{{ $dealProduct->stock_quantity }} items</strong> left in stock!</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Featured Deal Product Card --}}
                <div class="lg:col-span-6">
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-card relative max-w-md mx-auto">
                        @if($dealDiscount > 0)
                            <span class="absolute top-4 right-4 bg-pulse-orange text-white text-xs font-black px-2.5 py-1 rounded-lg">
                                -{{ $dealDiscount }}% OFF
                            </span>
                        @endif
                        <div class="h-56 bg-amber-50/40 rounded-xl flex items-center justify-center p-4 mb-4 overflow-hidden">
                            @if($dealProduct->primary_image_url && !str_contains($dealProduct->primary_image_url, 'placeholder'))
                                <img src="{{ $dealProduct->primary_image_url }}" alt="{{ $dealProduct->name }}" class="w-full h-full object-contain mix-blend-multiply" loading="lazy">
                            @else
                                <i class="fa-solid fa-headphones-simple text-7xl text-pulse-navy drop-shadow-md"></i>
                            @endif
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-pulse-teal">
                                {{ $dealProduct->category?->name ?? 'Audio & Electronics' }}
                            </span>
                            <h3 class="text-base font-bold text-pulse-navy mt-0.5 line-clamp-2">
                                <a href="{{ route('products.show', $dealProduct->slug ?? $dealProduct->id) }}" class="hover:text-pulse-orange transition-colors">
                                    {{ $dealProduct->name }}
                                </a>
                            </h3>
                            <div class="flex items-center space-x-3 mt-3 flex-wrap gap-y-1">
                                <span class="text-2xl font-black text-pulse-orange">
                                    Rs. {{ number_format($dealProduct->sale_price ?? $dealProduct->regular_price) }}
                                </span>
                                @if($dealProduct->sale_price)
                                    <span class="text-sm text-slate-400 line-through">
                                        Rs. {{ number_format($dealProduct->regular_price) }}
                                    </span>
                                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">
                                        Save Rs. {{ number_format($dealSavings) }}
                                    </span>
                                @endif
                            </div>
                            <form action="{{ route('cart.add') }}" method="POST" class="ajax-add-to-cart mt-5">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $dealProduct->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-pulse-navy hover:bg-pulse-navy-dark text-white py-3 rounded-xl font-bold text-xs tracking-wider transition-colors flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-cart-shopping"></i>
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
{{-- END: DealOfTheDaySection --}}

{{-- 5. BEGIN: TrendingProductsGrid --}}
<section class="py-14 bg-pulse-bg" data-purpose="trending-products" id="trending">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold text-pulse-orange uppercase tracking-wider block">Top Picks</span>
                <h2 class="text-2xl sm:text-3xl font-black text-pulse-navy tracking-tight mt-0.5">Trending Products</h2>
            </div>
            <a class="text-xs sm:text-sm font-bold text-pulse-orange hover:text-pulse-orange-dark flex items-center space-x-1.5 transition-colors" href="{{ route('products.index', ['sort' => 'popular']) }}">
                <span>View All Trending</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        {{-- 8-Product Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse($trendingProducts->take(8) as $tProduct)
                <x-product-card :product="$tProduct" />
            @empty
                <p class="col-span-full text-center text-xs text-slate-400 py-10">Trending products catalog updating...</p>
            @endforelse
        </div>
    </div>
</section>
{{-- END: TrendingProductsGrid --}}

{{-- 6. BEGIN: PromotionalDualBanners --}}
<section class="py-8 bg-pulse-bg" data-purpose="promotional-banners">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Banner 1: Energetic Orange Flash Sale --}}
            <div class="bg-gradient-to-r from-pulse-orange to-amber-500 rounded-3xl p-8 sm:p-10 text-white relative overflow-hidden shadow-card flex flex-col justify-between min-h-[220px]">
                <div class="relative z-10 space-y-2">
                    <span class="text-xs font-black uppercase tracking-wider bg-white/20 px-3 py-1 rounded-full inline-block backdrop-blur-sm">Warehouse Clearance</span>
                    <h3 class="text-2xl sm:text-3xl font-black">Flash Sale <br>Up To 60% Off</h3>
                    <p class="text-xs text-white/90 max-w-xs font-body">Limited allocation direct from certified manufacturers. First come, first served.</p>
                </div>
                <div class="pt-5 relative z-10">
                    <a class="bg-white hover:bg-slate-100 text-pulse-orange px-6 py-2.5 rounded-xl font-extrabold text-xs tracking-wider inline-flex items-center space-x-2 transition-transform hover:scale-105 shadow-md" href="{{ route('products.index', ['sort' => 'sale']) }}">
                        <span>Shop Flash Deals</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                <i class="fa-solid fa-tags absolute -right-6 -bottom-8 text-white/15 text-9xl"></i>
            </div>

            {{-- Banner 2: Deep Ink Navy Direct Retail Excellence --}}
            <div class="bg-gradient-to-r from-pulse-navy to-pulse-navy-surface rounded-3xl p-8 sm:p-10 text-white relative overflow-hidden shadow-card flex flex-col justify-between min-h-[220px] border border-white/10">
                <div class="relative z-10 space-y-2">
                    <span class="text-xs font-black uppercase tracking-wider bg-pulse-teal/20 text-pulse-teal px-3 py-1 rounded-full inline-block backdrop-blur-sm border border-pulse-teal/30">Direct Sourcing</span>
                    <h3 class="text-2xl sm:text-3xl font-black">Curated Direct <br>Retail Excellence</h3>
                    <p class="text-xs text-slate-300 max-w-xs font-body">Zero third-party seller headaches. 100% verified single-origin retail inventory.</p>
                </div>
                <div class="pt-5 relative z-10">
                    <a class="bg-pulse-teal hover:bg-teal-500 text-white px-6 py-2.5 rounded-xl font-extrabold text-xs tracking-wider inline-flex items-center space-x-2 transition-transform hover:scale-105 shadow-md" href="{{ route('products.index') }}">
                        <span>Explore Sourcing</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                <i class="fa-solid fa-circle-check absolute -right-6 -bottom-8 text-white/10 text-9xl"></i>
            </div>
        </div>
    </div>
</section>
{{-- END: PromotionalDualBanners --}}

{{-- 7. BEGIN: NewArrivalsSection --}}
<section class="py-14 bg-white border-y border-pulse-border" data-purpose="new-arrivals" id="new-arrivals">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold text-pulse-teal uppercase tracking-wider block">Fresh Inventory</span>
                <h2 class="text-2xl sm:text-3xl font-black text-pulse-navy tracking-tight mt-0.5">New Arrivals</h2>
            </div>
            <a class="text-xs sm:text-sm font-bold text-pulse-orange hover:text-pulse-orange-dark flex items-center space-x-1.5 transition-colors" href="{{ route('products.index', ['sort' => 'newest']) }}">
                <span>View All New</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            @forelse($newArrivals->take(4) as $nProduct)
                <x-product-card :product="$nProduct" badge="New" />
            @empty
                <p class="col-span-full text-center text-xs text-slate-400 py-6">New arrivals inventory arriving soon...</p>
            @endforelse
        </div>
    </div>
</section>
{{-- END: NewArrivalsSection --}}

{{-- 8. BEGIN: DealsAndSpecialOffers --}}
<section class="py-14 bg-pulse-bg" data-purpose="deals-special-offers">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-xs font-bold text-pulse-orange uppercase tracking-wider block">Direct Markdowns</span>
                <h2 class="text-2xl sm:text-3xl font-black text-pulse-navy tracking-tight mt-0.5">Deals &amp; Special Offers</h2>
            </div>
            <a class="text-xs sm:text-sm font-bold text-pulse-orange hover:text-pulse-orange-dark flex items-center space-x-1.5 transition-colors" href="{{ route('products.index', ['sort' => 'sale']) }}">
                <span>View All Deals</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($deals->take(4) as $dItem)
                @php
                    $dDiscount = $dItem->discount_percentage > 0
                        ? $dItem->discount_percentage
                        : (int) round((($dItem->regular_price - $dItem->sale_price) / $dItem->regular_price) * 100);
                @endphp
                <div class="bg-white rounded-2xl border border-pulse-border p-4 shadow-subtle flex flex-col justify-between hover:shadow-card transition-shadow">
                    <div>
                        <div class="h-44 bg-amber-50/60 rounded-xl relative flex items-center justify-center mb-3 overflow-hidden p-3">
                            @if($dDiscount > 0)
                                <span class="absolute top-2 left-2 z-10 bg-pulse-orange text-white text-[10px] font-black px-2 py-0.5 rounded shadow-xs">
                                    Save {{ $dDiscount }}%
                                </span>
                            @endif
                            @if($dItem->primary_image_url && !str_contains($dItem->primary_image_url, 'placeholder'))
                                <img src="{{ $dItem->primary_image_url }}" alt="{{ $dItem->name }}" class="w-full h-full object-contain mix-blend-multiply" loading="lazy">
                            @else
                                <i class="fa-solid fa-tags text-5xl text-pulse-navy/80"></i>
                            @endif
                        </div>
                        <span class="text-[10px] font-bold uppercase text-slate-400 block truncate">
                            {{ $dItem->category?->name ?? 'Direct Deal' }}
                        </span>
                        <h4 class="text-xs font-bold text-pulse-navy mt-0.5 line-clamp-1 hover:text-pulse-orange transition-colors">
                            <a href="{{ route('products.show', $dItem->slug ?? $dItem->id) }}">
                                {{ $dItem->name }}
                            </a>
                        </h4>
                        <div class="mt-2 flex items-baseline">
                            <span class="text-lg font-black text-pulse-orange">
                                Rs. {{ number_format($dItem->sale_price ?? $dItem->regular_price) }}
                            </span>
                            @if($dItem->sale_price)
                                <span class="text-xs text-slate-400 line-through ml-2">
                                    Rs. {{ number_format($dItem->regular_price) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('cart.add') }}" method="POST" class="ajax-add-to-cart mt-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $dItem->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full bg-pulse-orange hover:bg-pulse-orange-dark text-white font-bold py-2 rounded-xl text-xs transition-colors flex items-center justify-center space-x-1.5 active:scale-98 shadow-sm">
                            <i class="fa-solid fa-bag-shopping text-xs"></i>
                            <span>Order Deal</span>
                        </button>
                    </form>
                </div>
            @empty
                <p class="col-span-full text-center text-xs text-slate-400 py-6">Flash deals updating from central warehouse...</p>
            @endforelse
        </div>
    </div>
</section>
{{-- END: DealsAndSpecialOffers --}}

{{-- 9. BEGIN: DirectRetailPhilosophy --}}
<section class="py-16 bg-pulse-navy text-white relative overflow-hidden" data-purpose="why-direct-retail" id="why-shop">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Title --}}
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-pulse-teal font-extrabold text-xs uppercase tracking-widest block mb-1">Direct Retail Philosophy</span>
            <h2 class="text-3xl sm:text-4xl font-black tracking-tight">Why Shop Directly with ShopPulss?</h2>
            <p class="text-slate-300 text-sm mt-3">We operate as an exclusive direct-to-consumer store. We source, inspect, package, and dispatch 100% of our products ourselves to guarantee trust and eliminate counterfeit dropshipping.</p>
        </div>
        {{-- 4 Core Pillars --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-pulse-navy-surface/80 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <h3 class="text-base font-bold mb-2">100% Brand Authentic</h3>
                <p class="text-xs text-slate-300 leading-relaxed">Direct relationships with official brand distributors. Zero random third-party vendors.</p>
            </div>
            <div class="bg-pulse-navy-surface/80 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
                <h3 class="text-base font-bold mb-2">Cash on Delivery</h3>
                <p class="text-xs text-slate-300 leading-relaxed">Inspect your sealed shipment and pay comfortably at your doorstep anywhere in Pakistan.</p>
            </div>
            <div class="bg-pulse-navy-surface/80 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-repeat"></i>
                </div>
                <h3 class="text-base font-bold mb-2">7-Day Easy Returns</h3>
                <p class="text-xs text-slate-300 leading-relaxed">Full refund or replacement managed directly by our Karachi customer support team.</p>
            </div>
            <div class="bg-pulse-navy-surface/80 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                <div class="w-12 h-12 rounded-xl bg-pulse-orange/20 text-pulse-orange flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <h3 class="text-base font-bold mb-2">Express Dispatch</h3>
                <p class="text-xs text-slate-300 leading-relaxed">Parcels shipped same-day via TCS, Leopards, and Swyft with real-time tracking.</p>
            </div>
        </div>
    </div>
</section>
{{-- END: DirectRetailPhilosophy --}}

{{-- 10. BEGIN: NewsletterAndAssistance --}}
<section class="py-12 bg-white" data-purpose="newsletter-assistance">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Newsletter Card --}}
        <div class="bg-slate-50 border border-pulse-border rounded-3xl p-6 sm:p-10 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <div class="lg:col-span-6 space-y-1">
                    <h3 class="text-xl sm:text-2xl font-black text-pulse-navy flex items-center space-x-2">
                        <span>Get Exclusive Deals First!</span>
                        <span>🎉</span>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500">Subscribe to receive instant warehouse alerts and secret flash markdown coupons.</p>
                </div>
                <div class="lg:col-span-6">
                    <form onsubmit="handleNewsletter(event, this)" class="flex flex-col sm:flex-row gap-2">
                        <input class="flex-1 bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:border-pulse-orange focus:ring-1 focus:ring-pulse-orange" placeholder="Enter your email address..." type="email" required>
                        <button class="bg-pulse-navy hover:bg-pulse-navy-dark text-white font-bold text-xs px-6 py-2.5 rounded-xl transition-colors whitespace-nowrap" type="submit">
                            Subscribe →
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Live Order Tracking & Direct WhatsApp Bar --}}
        @php
            $rawWhatsapp = \App\Models\Setting::get('whatsapp_number', '+923000000000');
            $cleanWhatsapp = preg_replace('/[^0-9]/', '', $rawWhatsapp);
            $helplinePhone = \App\Models\Setting::get('whatsapp_helpline', '+92 300 000-0000');
        @endphp
        <div class="bg-pulse-bg border border-pulse-border rounded-2xl p-4 sm:p-5 flex flex-wrap items-center justify-between gap-4" id="order-tracking">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <div>
                    <div class="text-xs font-bold text-pulse-navy">Need help or advice before ordering?</div>
                    <div class="text-[11px] text-slate-500">Chat directly with our verified customer operations desk on WhatsApp: <a class="font-semibold text-emerald-600" href="tel:{{ preg_replace('/[^0-9+]/', '', $helplinePhone) }}">{{ $helplinePhone }}</a></div>
                </div>
            </div>
            <div class="flex items-center space-x-2.5 w-full sm:w-auto">
                <a class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-colors flex items-center space-x-1.5 shrink-0" href="https://wa.me/{{ $cleanWhatsapp }}" target="_blank" rel="noopener">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span>WhatsApp Helpline</span>
                </a>
                <form action="{{ route('orders.track') }}" method="GET" class="flex items-center space-x-1 flex-1 sm:flex-initial">
                    <input class="w-32 sm:w-40 bg-white border border-slate-300 rounded-xl px-3 py-1.5 text-xs placeholder:text-slate-400 focus:border-pulse-navy focus:ring-0" placeholder="Enter Order ID..." type="text" name="order_number" required>
                    <button class="bg-pulse-navy hover:bg-pulse-navy-dark text-white text-xs font-bold px-3 py-1.5 rounded-xl transition-colors" type="submit">
                        Track
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
{{-- END: NewsletterAndAssistance --}}

@endsection

@push('scripts')
<script>
    // Live countdown timer updater for Deal of the Day
    document.addEventListener('DOMContentLoaded', () => {
        const hoursEl = document.getElementById('deal-hours');
        const minsEl = document.getElementById('deal-minutes');
        const secsEl = document.getElementById('deal-seconds');

        if (!hoursEl || !minsEl || !secsEl) return;

        // Target: Midnight tonight or remaining seconds
        const now = new Date();
        const endOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);

        function updateCountdown() {
            const currentTime = new Date();
            const diff = Math.max(0, Math.floor((endOfDay - currentTime) / 1000));

            const hours = Math.floor(diff / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = diff % 60;

            hoursEl.textContent = String(hours).padStart(2, '0');
            minsEl.textContent = String(minutes).padStart(2, '0');
            secsEl.textContent = String(seconds).padStart(2, '0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
</script>
@endpush
