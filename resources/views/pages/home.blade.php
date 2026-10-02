@extends('layouts.app')

@section('title', 'ShopPulss - Premium Essentials. Direct to You. Zero Middlemen.')
@section('description', 'Shop authentic products at fair prices. Handpicked from 100+ trusted brands. Shipped directly from our central Karachi fulfilment facility.')

@section('content')

{{-- HERO SECTION --}}
<section class="py-8 px-4 overflow-hidden" style="background: linear-gradient(135deg, #F5F7FA 0%, #eef2f7 100%);">
    <div class="max-w-screen-xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

            {{-- Hero Text --}}
            <div class="order-2 lg:order-1">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-4" style="background-color: rgba(0,168,184,0.1); color: #00A8B8;">
                    <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background-color: #00A8B8;"></span>
                    Pakistan's Verified Direct Retail Store
                </div>
                <h1 class="text-4xl md:text-5xl font-black leading-tight mb-3" style="color: #0F1B4D;">
                    Premium Essentials.<br>Direct to You.<br>
                    <span style="color: #00A8B8;">Zero Middlemen.</span>
                </h1>
                <p class="text-gray-500 text-base leading-relaxed mb-6 max-w-md">
                    Authentic products at fair prices. Handpicked from 100+ trusted brands shipped directly from our central Karachi fulfilment facility.
                </p>

                {{-- Trust Icons Row --}}
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-5 h-5" style="color: #00A8B8;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span><strong>100% Authentic</strong><br><small class="text-gray-400">Direct from Brands</small></span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-5 h-5" style="color: #00A8B8;" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H11a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h4l2 4v4h-6V7z"/></svg>
                        <span><strong>Nationwide Delivery</strong><br><small class="text-gray-400">All Pakistan</small></span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-5 h-5" style="color: #00A8B8;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>
                        <span><strong>Easy Returns</strong><br><small class="text-gray-400">7-Day Guarantee</small></span>
                    </div>
                </div>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap gap-3 mb-5">
                    <a href="#trending" id="hero-shop-now" class="inline-flex items-center gap-2 px-7 py-3 rounded-lg text-white font-bold text-sm transition-transform hover:scale-105 shadow-lg" style="background-color: #0F1B4D;">
                        Explore Now
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="#categories" id="hero-categories" class="inline-flex items-center gap-2 px-7 py-3 rounded-lg font-bold text-sm transition-colors hover:bg-gray-100 border border-gray-200" style="color: #0F1B4D;">
                        View Categories
                    </a>
                </div>

                <p class="text-xs text-gray-400">
                    ✔ Over 25,000+ fulfilled direct orders across 150+ Pakistani cities
                </p>
            </div>

            {{-- Hero Product Grid (4 images) --}}
            <div class="order-1 lg:order-2 grid grid-cols-2 gap-3">
                @foreach($heroProducts->take(4) as $index => $product)
                <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 border border-gray-100" id="hero-product-{{ $product->id }}">
                    @if($product->sale_price)
                    <span class="absolute top-2 left-2 z-10 px-2 py-0.5 rounded-full text-white text-xs font-bold" style="background-color: #FF6B35;">
                        SALE
                    </span>
                    @endif
                    @if($product->is_new)
                    <span class="absolute top-2 right-2 z-10 px-2 py-0.5 rounded-full text-white text-xs font-bold" style="background-color: #00A8B8;">
                        New
                    </span>
                    @elseif($index === 1)
                    <span class="absolute top-2 right-2 z-10 px-2 py-0.5 rounded-full text-white text-xs font-bold" style="background-color: #FF6B35;">
                        Popular
                    </span>
                    @endif
                    <div class="aspect-square bg-gray-50 overflow-hidden">
                        @if($product->images->isNotEmpty())
                        <img src="{{ $product->images->first()->image_url }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy">
                        @else
                        <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #e8ecf0 0%, #f5f7fa 100%);">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="text-xs text-gray-400 mb-0.5">{{ $product->category?->name ?? 'Product' }}</p>
                        <p class="text-xs font-semibold text-gray-800 line-clamp-2 leading-snug">{{ $product->name }}</p>
                        <div class="flex items-center gap-1.5 mt-1.5">
                            <span class="text-sm font-black" style="color: #0F1B4D;">
                                Rs. {{ number_format($product->sale_price ?? $product->regular_price, 0) }}
                            </span>
                            @if($product->sale_price)
                            <span class="text-xs text-gray-400 line-through">Rs. {{ number_format($product->regular_price, 0) }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach

                {{-- Fallback if no hero products --}}
                @for($i = $heroProducts->count(); $i < 4; $i++)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div class="p-3">
                        <div class="h-3 bg-gray-100 rounded mb-1.5"></div>
                        <div class="h-4 bg-gray-100 rounded w-3/4"></div>
                    </div>
                </div>
                @endfor
            </div>

        </div>
    </div>
</section>

{{-- FEATURED CATEGORIES --}}
<section id="categories" class="py-12 px-4 bg-white">
    <div class="max-w-screen-xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color: #00A8B8;">DIRECT DEPARTMENT DIRECTORY</p>
                <h2 class="text-2xl md:text-3xl font-black" style="color: #0F1B4D;">Featured Categories</h2>
            </div>
            <p class="hidden md:block text-sm text-gray-400 text-right max-w-xs">Carefully imported inventory stored and dispatched directly from our central Karachi fulfilment facility.</p>
        </div>

        <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
            @php
            $categoryIcons = [
                'Electronics' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                'Fashion' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>',
                'Beauty' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>',
                'Home' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                'Accessories' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
                'Sports' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'Kitchen' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>',
            ];
            $catCounts = ['140+', '90+', '80+', '65+', '120+', '45+'];
            @endphp

            @foreach($featuredCategories->take(6) as $index => $category)
            @php
            $iconKey = 'Electronics';
            foreach(array_keys($categoryIcons) as $key) {
                if (str_contains($category->name, $key)) { $iconKey = $key; break; }
            }
            @endphp
            <a href="{{ route('categories.show', $category->slug) }}" id="cat-{{ $category->id }}" class="group flex flex-col items-center p-4 rounded-2xl border border-gray-100 hover:border-teal-200 hover:shadow-md transition-all duration-300 hover:-translate-y-1 text-center cursor-pointer">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3 transition-colors group-hover:scale-110 duration-300" style="background: linear-gradient(135deg, rgba(0,168,184,0.1) 0%, rgba(15,27,77,0.05) 100%); color: #00A8B8;">
                    {!! $categoryIcons[$iconKey] !!}
                </div>
                <p class="font-bold text-xs text-center leading-tight" style="color: #0F1B4D;">{{ $category->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $catCounts[$index] ?? '50+' }} items</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- TRENDING PRODUCTS --}}
<section id="trending" class="py-12 px-4" style="background-color: #F5F7FA;">
    <div class="max-w-screen-xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color: #FF6B35;">🔥 CURATE TOP SELLERS</p>
                <h2 class="text-2xl md:text-3xl font-black" style="color: #0F1B4D;">Trending Products</h2>
            </div>
            <a href="{{ route('products.index') }}" id="view-all-trending" class="flex items-center gap-1 text-sm font-semibold hover:gap-2 transition-all" style="color: #0F1B4D;">
                View All Products
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($trendingProducts->take(8) as $product)
            @include('components.product-card', ['product' => $product, 'badge' => 'Hot Deal'])
            @endforeach
        </div>
    </div>
</section>

{{-- NEW ARRIVALS --}}
<section id="new-arrivals" class="py-12 px-4 bg-white">
    <div class="max-w-screen-xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color: #00A8B8;">🆕 FRESH INVENTORY STOCK</p>
                <h2 class="text-2xl md:text-3xl font-black" style="color: #0F1B4D;">New Arrivals</h2>
                <p class="text-sm text-gray-400 mt-1">Unboxed and thoroughly verified for authenticity before being listed for nationwide delivery.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($newArrivals->take(4) as $product)
            @include('components.product-card', ['product' => $product, 'badge' => 'New'])
            @endforeach

            @if($newArrivals->count() === 0)
            @foreach($trendingProducts->take(4) as $product)
            @include('components.product-card', ['product' => $product, 'badge' => 'New'])
            @endforeach
            @endif
        </div>
    </div>
</section>

{{-- DEALS & SPECIAL OFFERS --}}
<section id="deals" class="py-12 px-4" style="background-color: #F5F7FA;">
    <div class="max-w-screen-xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color: #FF6B35;">💥 DIRECT MARKDOWNS</p>
                <h2 class="text-2xl md:text-3xl font-black" style="color: #0F1B4D;">Deals & Special Offers</h2>
            </div>
            {{-- Countdown Timer --}}
            <div class="flex items-center gap-2 text-xs font-bold text-gray-500">
                <span>Ends In:</span>
                <div id="deal-countdown" class="flex items-center gap-1">
                    <span class="bg-white border border-gray-200 rounded px-2 py-1 font-black text-sm" id="cd-hours" style="color: #0F1B4D;">14</span>
                    <span style="color: #FF6B35;">h</span>
                    <span class="bg-white border border-gray-200 rounded px-2 py-1 font-black text-sm" id="cd-mins" style="color: #0F1B4D;">32</span>
                    <span style="color: #FF6B35;">m</span>
                    <span class="bg-white border border-gray-200 rounded px-2 py-1 font-black text-sm" id="cd-secs" style="color: #0F1B4D;">45</span>
                    <span style="color: #FF6B35;">s</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($deals->take(4) as $product)
            @php
            $discountPct = $product->regular_price > 0 && $product->sale_price
                ? round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100)
                : 0;
            @endphp
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 group" id="deal-product-{{ $product->id }}">
                <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="block relative aspect-square bg-gray-50 overflow-hidden">
                    @if($discountPct > 0)
                    <span class="absolute top-2 left-2 z-10 px-2 py-1 rounded-lg text-white text-xs font-black" style="background-color: #FF6B35;">
                        Save {{ $discountPct }}%
                    </span>
                    @endif
                    @if($product->images->isNotEmpty())
                    <img src="{{ $product->images->first()->image_url }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-50">
                        <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    @endif
                </a>
                <div class="p-3">
                    <p class="text-xs text-gray-400 mb-0.5">{{ $product->category?->name }}</p>
                    <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="text-sm font-semibold text-gray-800 line-clamp-2 leading-snug mb-2 hover:text-teal-600 transition-colors block">
                        {{ $product->name }}
                    </a>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-base font-black" style="color: #0F1B4D;">Rs. {{ number_format($product->sale_price ?? $product->regular_price, 0) }}</span>
                        @if($product->sale_price)
                        <span class="text-xs text-gray-400 line-through">Rs. {{ number_format($product->regular_price, 0) }}</span>
                        @endif
                    </div>
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full py-2 rounded-lg text-sm font-semibold text-white transition-all hover:opacity-90 flex items-center justify-center gap-2 shadow-sm" style="background-color: #FF6B35;" id="deal-order-{{ $product->id }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Order Deal
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            @if($deals->count() === 0)
                <div class="col-span-2 md:col-span-4 bg-white rounded-2xl border border-gray-100 p-8 text-center shadow-sm">
                    <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm">Flash Promotions Coming Soon</h3>
                    <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">New markdown events are added weekly. Check back shortly or browse our trending catalog.</p>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- WHY SHOP WITH SHOPPULSS --}}
<section id="why-us" class="py-16 px-4 text-white" style="background-color: #0F1B4D;">
    <div class="max-w-screen-xl mx-auto text-center">
        <p class="text-xs font-bold uppercase tracking-widest mb-2" style="color: #00A8B8;">THE DIRECT RETAIL COMPANY</p>
        <h2 class="text-2xl md:text-3xl font-black mb-2">Why Shop Directly with ShopPulss?</h2>
        <p class="text-blue-200 text-sm mb-10">We operate as an exclusive single store, managing our own supply chain to eliminate marketplace unpredictability.</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex flex-col items-center p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background-color: rgba(0,168,184,0.15);">
                    <svg class="w-7 h-7" style="color: #00A8B8;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="font-bold text-sm mb-2">100% Authentic & Direct</h3>
                <p class="text-blue-200 text-xs leading-relaxed">We source, inspect, and list every single product ourselves. No third-party sellers or counterfeit dropshippers.</p>
            </div>

            <div class="flex flex-col items-center p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background-color: rgba(0,168,184,0.15);">
                    <svg class="w-7 h-7" style="color: #00A8B8;" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="font-bold text-sm mb-2">Cash on Delivery Available</h3>
                <p class="text-blue-200 text-xs leading-relaxed">Pay at your doorstep in any city, town, or village across Pakistan with complete peace of mind.</p>
            </div>

            <div class="flex flex-col items-center p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background-color: rgba(0,168,184,0.15);">
                    <svg class="w-7 h-7" style="color: #00A8B8;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="font-bold text-sm mb-2">7-Day Easy Returns</h3>
                <p class="text-blue-200 text-xs leading-relaxed">We are on 100% satisfaction with your order. Direct Return support by our Karachi team.</p>
            </div>

            <div class="flex flex-col items-center p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background-color: rgba(0,168,184,0.15);">
                    <svg class="w-7 h-7" style="color: #00A8B8;" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H11a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h4l2 4v4h-6V7z"/></svg>
                </div>
                <h3 class="font-bold text-sm mb-2">Nationwide Fast Delivery</h3>
                <p class="text-blue-200 text-xs leading-relaxed">Express shipments dispatched via TCS, Day, & Leopards with real-time tracking to all 150+ Pakistani cities.</p>
            </div>
        </div>
    </div>
</section>

{{-- NEWSLETTER SECTION --}}
<section id="newsletter" class="py-12 px-4 bg-white">
    <div class="max-w-screen-xl mx-auto">
        <div class="rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-6" style="background: linear-gradient(135deg, rgba(0,168,184,0.08) 0%, rgba(15,27,77,0.05) 100%); border: 2px solid rgba(0,168,184,0.15);">
            <div>
                <h2 class="text-2xl font-black mb-2" style="color: #0F1B4D;">Get Exclusive Deals First! 🎉</h2>
                <p class="text-gray-500 text-sm">Subscribe to our newsletter and never miss a flash sale or new arrival.</p>
            </div>
            <form class="flex gap-3 w-full md:w-auto" id="hero-newsletter-form">
                <input type="email" name="email" placeholder="Your email address..." class="flex-1 md:w-72 px-4 py-3 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-teal-400 focus:ring-2" style="--tw-ring-color: rgba(0,168,184,0.15);">
                <button type="submit" class="px-6 py-3 rounded-xl text-white text-sm font-bold hover:opacity-90 transition-opacity flex-shrink-0" style="background-color: #0F1B4D;" id="newsletter-cta-btn">
                    Subscribe →
                </button>
            </form>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Countdown Timer for deals
function startCountdown() {
    const target = new Date();
    target.setHours(target.getHours() + 14, target.getMinutes() + 32, target.getSeconds() + 45);

    function update() {
        const now = new Date();
        const diff = Math.max(0, target - now);
        const h = Math.floor(diff / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);

        const hEl = document.getElementById('cd-hours');
        const mEl = document.getElementById('cd-mins');
        const sEl = document.getElementById('cd-secs');

        if (hEl) hEl.textContent = String(h).padStart(2, '0');
        if (mEl) mEl.textContent = String(m).padStart(2, '0');
        if (sEl) sEl.textContent = String(s).padStart(2, '0');

        if (diff > 0) setTimeout(update, 1000);
    }
    update();
}
startCountdown();

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href.length > 1) {
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });
});

// Add to Cart animation
document.querySelectorAll('[id^="add-to-cart-"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const original = this.innerHTML;
        this.innerHTML = '<svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Added!';
        this.style.backgroundColor = '#10b981';
        setTimeout(() => {
            this.innerHTML = original;
            this.style.backgroundColor = '';
        }, 1500);
    });
});
</script>
@endpush
