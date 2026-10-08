<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ShopPulss | Direct Retail Store - 100% Authentic Products & Nationwide COD')</title>
    <meta name="description" content="@yield('description', 'Pakistan\'s premier direct-to-consumer store. 100% genuine products, Cash on Delivery nationwide, 7-day easy returns.')">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Vite Assets (Tailwind v4) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-pulse-bg text-slate-800 font-sans antialiased min-h-screen flex flex-col selection:bg-pulse-orange selection:text-white">

    @php
        $settingsHelper = [
            'tagline' => \App\Models\Setting::get('store_tagline', 'Pakistan\'s Verified Direct Retail Hub'),
            'phone' => \App\Models\Setting::get('store_phone', '+923328912706'),
            'email' => \App\Models\Setting::get('store_email', 'devwordspace3300@gmail.com'),
            'whatsapp' => \App\Models\Setting::get('whatsapp_number', '+923328912706'),
            'whatsapp_helpline' => \App\Models\Setting::get('whatsapp_helpline', '+923328912706'),
            'facebook' => \App\Models\Setting::get('facebook_url', 'https://facebook.com/shoppulss'),
            'instagram' => \App\Models\Setting::get('instagram_url', 'https://instagram.com/shoppulss'),
        ];
        $cleanWhatsapp = preg_replace('/[^0-9]/', '', $settingsHelper['whatsapp']);

        // Database-driven cart values
        $cartService = app(\App\Services\CartService::class);
        $activeCart = $cartService->getCart();
        $cartTotals = $cartService->getTotals($activeCart);
        $cartCount = (int) ($cartTotals['item_count'] ?? 0);
        $cartTotal = (float) ($cartTotals['subtotal'] ?? 0.0);

        // Database categories for dropdowns
        $navCategories = \App\Models\Category::active()->parents()->orderBy('display_order')->get();

        // Valid published product IDs for wishlist verification
        $validProductIds = \App\Models\Product::where('status', 'published')->pluck('id')->toArray();
    @endphp

    {{-- BEGIN: Top Utility Bar (Navy) --}}
    <aside class="bg-pulse-navy-dark text-white text-xs py-2 border-b border-white/10" data-purpose="top-utility-bar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap items-center justify-between gap-y-1">
            <div class="flex items-center space-x-6">
                <span class="flex items-center space-x-1.5 font-medium text-slate-300">
                    <i class="fa-solid fa-shield-halved text-pulse-teal"></i>
                    <span>{{ $settingsHelper['tagline'] ?: "Pakistan's Verified Direct Retail Hub" }}</span>
                </span>
                <span class="hidden md:inline-block text-slate-600">|</span>
                <span class="flex items-center space-x-1.5 text-emerald-400 font-medium">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <span>Cash on Delivery (COD) Nationwide</span>
                </span>
            </div>
            <div class="flex items-center space-x-5 text-slate-300">
                <a class="hover:text-white flex items-center space-x-1 transition-colors" href="{{ route('orders.track') }}">
                    <i class="fa-solid fa-location-crosshairs text-slate-400 text-xs"></i>
                    <span>Track Order</span>
                </a>
                <span class="text-slate-600">|</span>
                <a class="hover:text-pulse-orange flex items-center space-x-1 font-semibold text-white transition-colors" href="tel:{{ preg_replace('/[^0-9+]/', '', $settingsHelper['phone']) }}">
                    <i class="fa-solid fa-headset text-pulse-orange"></i>
                    <span>{{ $settingsHelper['phone'] }}</span>
                </a>
            </div>
        </div>
    </aside>
    {{-- END: Top Utility Bar --}}

    {{-- BEGIN: Main Sticky Header --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-pulse-border shadow-sm transition-all duration-200" id="site-header" data-purpose="site-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 gap-4 lg:gap-8">

                {{-- Mobile Drawer Toggle --}}
                <button type="button" onclick="toggleMobileDrawer()" class="lg:hidden p-2 rounded-xl text-slate-700 hover:bg-slate-100 transition-colors focus:outline-none" aria-label="Toggle navigation">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                {{-- Brand Logo --}}
                <a class="flex items-center space-x-2.5 shrink-0 group" href="{{ route('home') }}">
                    <img src="{{ asset('images/shoppulss-logo.png') }}" alt="ShopPulss" class="h-10 sm:h-11 w-auto object-contain transition-transform group-hover:scale-102">
                </a>

                {{-- Center Search Bar --}}
                <div class="hidden md:flex flex-1 max-w-2xl">
                    <form action="{{ route('search') }}" method="GET" class="w-full relative flex items-center" id="header-search-form">
                        <div class="relative w-full flex items-center">
                            {{-- Category selector inside search --}}
                            <div class="absolute left-1.5 z-30" id="search-cat-container">
                                <select
                                    name="category"
                                    id="navbar-category-select"
                                    onchange="handleNavbarCategoryChange(this)"
                                    class="h-[38px] text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 pl-3 pr-7 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-pulse-orange/30 cursor-pointer transition-colors max-w-[145px] truncate"
                                    title="Filter by category"
                                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2210%22%20height%3D%2210%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%2364748b%22%20stroke-width%3D%222.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-position: right 0.5rem center; background-repeat: no-repeat; -webkit-appearance: none; -moz-appearance: none; appearance: none;"
                                >
                                    <option value="" class="font-bold text-slate-800">All Categories</option>
                                    @foreach($navCategories as $cat)
                                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }} class="font-medium text-slate-700">
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <input
                                class="w-full py-2.5 pl-[156px] pr-28 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:bg-white focus:border-pulse-orange focus:ring-2 focus:ring-pulse-orange/20 transition-all placeholder:text-slate-400"
                                placeholder="Search genuine gadgets, smartwatches, home decor, lifestyle..."
                                type="search"
                                name="q"
                                value="{{ request('q') }}"
                                autocomplete="off"
                            >

                            {{-- Search CTA Button --}}
                            <button class="absolute right-1.5 bg-pulse-orange hover:bg-pulse-orange-dark text-white px-5 py-2 rounded-lg text-xs font-bold tracking-wide transition-all shadow-sm flex items-center space-x-1.5 cursor-pointer" type="submit">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                <span>Search</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Header Right Utilities --}}
                <div class="flex items-center space-x-3 sm:space-x-5">
                    {{-- WhatsApp Hotline Quick Link --}}
                    <a class="hidden xl:flex items-center space-x-2 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-200 hover:bg-emerald-100 transition-colors" href="https://wa.me/{{ $cleanWhatsapp }}" target="_blank" rel="noopener">
                        <i class="fa-brands fa-whatsapp text-emerald-600 text-base"></i>
                        <span>WhatsApp Help</span>
                    </a>

                    {{-- User Account --}}
                    @auth
                        <div class="relative" id="user-menu-container">
                            <button type="button" onclick="toggleUserMenu()" class="flex items-center space-x-2 text-slate-700 hover:text-pulse-navy p-1.5 sm:p-2 rounded-lg hover:bg-slate-100 transition-colors focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-pulse-navy text-white flex items-center justify-center text-xs font-black shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="hidden lg:block text-left">
                                    <span class="block text-[11px] text-slate-400 leading-none">Logged In</span>
                                    <span class="block text-xs font-bold text-slate-800 leading-tight truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                                </div>
                            </button>
                            <div id="user-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-card border border-pulse-border py-2 z-50">
                                <div class="px-4 py-2.5 border-b border-slate-100">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-pulse-teal hover:bg-teal-50 transition-colors">
                                        <i class="fa-solid fa-chart-line text-sm"></i>
                                        <span>Admin Dashboard</span>
                                    </a>
                                    <div class="border-t border-slate-100 my-1"></div>
                                @endif
                                <a href="{{ route('account.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 transition-colors">
                                    <i class="fa-regular fa-user text-slate-400 text-sm"></i>
                                    <span>My Profile</span>
                                </a>
                                <a href="{{ route('account.orders') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 transition-colors">
                                    <i class="fa-solid fa-box text-slate-400 text-sm"></i>
                                    <span>My Orders</span>
                                </a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 text-left transition-colors font-semibold">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a class="flex items-center space-x-2 text-slate-700 hover:text-pulse-navy p-2 rounded-lg hover:bg-slate-100 transition-colors" href="{{ route('login') }}">
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center border border-slate-200">
                                <i class="fa-regular fa-user text-sm"></i>
                            </div>
                            <div class="hidden lg:block text-left">
                                <span class="block text-[11px] text-slate-400 leading-none">Welcome</span>
                                <span class="block text-xs font-bold text-slate-800 leading-tight">Account</span>
                            </div>
                        </a>
                    @endauth

                    {{-- Wishlist (Local state toggle) --}}
                    <a class="relative p-2 text-slate-700 hover:text-pulse-navy rounded-lg hover:bg-slate-100 transition-colors" href="{{ route('cart.index') }}" title="Wishlist">
                        <i class="fa-regular fa-heart text-xl"></i>
                        <span id="wishlist-badge" class="absolute top-1 right-1 w-4 h-4 bg-pulse-orange text-white text-[10px] font-bold rounded-full items-center justify-center hidden">0</span>
                    </a>

                    {{-- Cart Counter & Total --}}
                    <a class="relative flex items-center space-x-2.5 bg-pulse-navy hover:bg-pulse-navy-dark text-white px-3.5 py-2 rounded-xl transition-all shadow-sm" href="{{ route('cart.index') }}" id="nav-cart-btn">
                        <div class="relative">
                            <i class="fa-solid fa-bag-shopping text-base"></i>
                            @if($cartCount > 0)
                                <span id="nav-cart-count" class="absolute -top-1.5 -right-2 w-4 h-4 bg-pulse-orange text-white text-[10px] font-extrabold rounded-full flex items-center justify-center border-2 border-pulse-navy">{{ $cartCount }}</span>
                            @else
                                <span id="nav-cart-count" class="absolute -top-1.5 -right-2 w-4 h-4 bg-pulse-orange text-white text-[10px] font-extrabold rounded-full hidden items-center justify-center border-2 border-pulse-navy">0</span>
                            @endif
                        </div>
                        <div class="hidden sm:block text-left text-xs leading-none">
                            <span id="nav-cart-label" class="text-[10px] text-slate-300 block font-normal">{{ $cartCount > 0 ? 'Cart Total' : 'Cart' }}</span>
                            <span id="nav-cart-total" class="font-bold text-white text-xs mt-0.5 block transition-colors">{{ $cartCount > 0 ? 'Rs. ' . number_format($cartTotals['subtotal']) : 'Rs. 0' }}</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Mobile Search Dropdown Bar --}}
            <div class="md:hidden pb-3">
                <form action="{{ route('search') }}" method="GET" class="relative w-full">
                    <input
                        class="w-full py-2 pl-3.5 pr-20 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:bg-white focus:border-pulse-orange focus:ring-1 focus:ring-pulse-orange"
                        placeholder="Search genuine products..."
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                    >
                    <button class="absolute right-1 top-1 bg-pulse-orange text-white px-3 py-1 rounded-lg text-xs font-bold" type="submit">
                        Search
                    </button>
                </form>
            </div>

            {{-- Secondary Navigation Bar --}}
            <div class="border-t border-slate-100 py-2.5 flex items-center justify-between text-xs font-semibold relative" data-purpose="primary-navigation">
                <div class="flex items-center space-x-4 lg:space-x-6 w-full">
                    {{-- All Categories Dropdown Menu (Outside overflow-x-auto to prevent clipping) --}}
                    <div class="relative shrink-0" id="nav-cat-dropdown-container">
                        <button type="button" onclick="toggleNavCategoriesMenu(event)" id="nav-cat-btn" class="flex items-center space-x-2 text-pulse-navy font-black bg-slate-100 hover:bg-pulse-orange hover:text-white px-3.5 py-1.5 rounded-xl transition-all shadow-2xs group cursor-pointer border border-slate-200/60">
                            <i class="fa-solid fa-bars-staggered text-xs text-pulse-orange group-hover:text-white transition-colors"></i>
                            <span>All Categories</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-white transition-transform" id="nav-cat-chevron"></i>
                        </button>
                        <div id="nav-cat-dropdown-menu" class="hidden absolute left-0 top-full mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-pulse-border py-2 z-[100]">
                            <div class="px-4 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 flex items-center justify-between">
                                <span>Shop by Department</span>
                                <span class="text-pulse-teal font-bold">{{ $navCategories->count() }} Categories</span>
                            </div>
                            <div class="max-h-80 overflow-y-auto py-1">
                                <a href="{{ route('products.index') }}" class="flex items-center justify-between px-4 py-2.5 text-xs font-bold text-pulse-navy hover:bg-pulse-orange-light hover:text-pulse-orange transition-colors">
                                    <span class="flex items-center gap-2">
                                        <i class="fa-solid fa-border-all text-xs text-pulse-orange"></i>
                                        <span>All Products / Catalog</span>
                                    </span>
                                    <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                                </a>
                                @foreach($navCategories as $cat)
                                    <a href="{{ route('categories.show', $cat->slug) }}" class="flex items-center justify-between px-4 py-2 text-xs text-slate-700 hover:bg-slate-50 hover:text-pulse-orange font-medium transition-colors group">
                                        <span class="truncate">{{ $cat->name }}</span>
                                        <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 group-hover:text-pulse-orange group-hover:translate-x-0.5 transition-all"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Horizontal Nav Links --}}
                    <nav class="flex items-center space-x-5 lg:space-x-7 overflow-x-auto scrollbar-none py-0.5">
                        <a class="{{ request()->routeIs('home') && !request()->has('q') ? 'text-pulse-orange font-bold' : 'text-slate-700 hover:text-pulse-orange' }} flex items-center space-x-1.5 transition-colors whitespace-nowrap" href="{{ route('home') }}">
                            <span>Home</span>
                        </a>
                        <a class="{{ request()->routeIs('products.index') && !request()->has('q') ? 'text-pulse-orange font-bold' : 'text-slate-700 hover:text-pulse-orange' }} flex items-center space-x-1 transition-colors whitespace-nowrap" href="{{ route('products.index') }}">
                            <i class="fa-solid fa-store text-xs text-pulse-orange"></i>
                            <span>Shop</span>
                        </a>
                        <a class="text-slate-700 hover:text-pulse-orange transition-colors flex items-center space-x-1 whitespace-nowrap" href="{{ route('home') }}#trending">
                            <span>Trending Products</span>
                        </a>
                        <a class="text-slate-700 hover:text-pulse-orange transition-colors whitespace-nowrap" href="{{ route('home') }}#new-arrivals">New Arrivals</a>
                        <a class="text-slate-700 hover:text-pulse-orange transition-colors flex items-center space-x-1 whitespace-nowrap" href="{{ route('home') }}#deals">
                            <span class="w-2 h-2 rounded-full bg-pulse-orange pulse-dot"></span>
                            <span>Flash Deals</span>
                        </a>
                        <a class="text-slate-700 hover:text-pulse-orange transition-colors whitespace-nowrap" href="{{ route('home') }}#why-shop">Why Direct Retail?</a>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    {{-- END: Main Sticky Header --}}

    {{-- Mobile Navigation Drawer --}}
    <div id="mobile-drawer-backdrop" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden transition-opacity lg:hidden" onclick="toggleMobileDrawer()">
        <div class="w-80 max-w-[85vw] h-full bg-white shadow-card-hover flex flex-col" onclick="event.stopPropagation()">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <img src="{{ asset('images/shoppulss-logo.png') }}" alt="ShopPulss" class="h-8 w-auto">
                <button type="button" onclick="toggleMobileDrawer()" class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-5 space-y-4">
                <div class="space-y-1 text-sm font-semibold text-slate-700">
                    <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-pulse-orange bg-pulse-orange-light/50 font-bold">Home</a>
                    <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-xl hover:bg-slate-50 font-bold text-pulse-navy flex items-center gap-2">
                        <i class="fa-solid fa-store text-xs text-pulse-orange"></i>
                        <span>Shop Catalog</span>
                    </a>
                    <a href="{{ route('home') }}#trending" onclick="toggleMobileDrawer()" class="block px-3 py-2 rounded-xl hover:bg-slate-50">Trending Products</a>
                    <a href="{{ route('home') }}#new-arrivals" onclick="toggleMobileDrawer()" class="block px-3 py-2 rounded-xl hover:bg-slate-50">New Arrivals</a>
                    <a href="{{ route('home') }}#categories" onclick="toggleMobileDrawer()" class="block px-3 py-2 rounded-xl hover:bg-slate-50">Categories</a>
                    <a href="{{ route('home') }}#deals" onclick="toggleMobileDrawer()" class="block px-3 py-2 rounded-xl text-pulse-orange hover:bg-orange-50 font-bold">🔥 Flash Deals</a>
                    <a href="{{ route('home') }}#why-shop" onclick="toggleMobileDrawer()" class="block px-3 py-2 rounded-xl hover:bg-slate-50">Why Direct Retail?</a>
                    <a href="{{ route('orders.track') }}" class="block px-3 py-2 rounded-xl hover:bg-slate-50">Track My Order</a>
                </div>
                <div class="pt-3 border-t border-slate-100">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 block mb-2">Shop Categories</span>
                    <div class="space-y-1 text-xs text-slate-600">
                        @foreach($navCategories as $mCat)
                            <a href="{{ route('categories.show', $mCat->slug) }}" class="block px-3 py-1.5 rounded-lg hover:bg-slate-50 hover:text-pulse-orange font-medium">{{ $mCat->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                @auth
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-rose-600 hover:underline">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="w-full py-2.5 rounded-xl bg-pulse-navy text-white text-xs font-bold flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-regular fa-user"></i>
                        <span>Sign In / Register</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- BEGIN: Global Footer (Navy) --}}
    <footer class="bg-pulse-navy-dark text-slate-400 text-xs pt-16 pb-8 border-t border-white/5 mt-auto" data-purpose="site-footer">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 pb-12 border-b border-white/10">
                {{-- Brand Info --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('home') }}" class="inline-block">
                            <img src="{{ asset('images/shoppulss-logo.png') }}" alt="ShopPulss" class="h-9 sm:h-10 w-auto object-contain">
                        </a>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-sm">
                        Pakistan's premier direct-to-consumer store. We inspect, authenticate, and fulfill every item directly from our central Karachi fulfillment warehouse with zero marketplace middlemen.
                    </p>
                    <div class="space-y-1.5 pt-1 text-xs">
                        <div class="flex items-center space-x-2 text-slate-300">
                            <i class="fa-solid fa-phone text-pulse-orange w-4"></i>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $settingsHelper['phone']) }}" class="hover:text-white font-medium transition-colors">{{ $settingsHelper['phone'] }}</a>
                        </div>
                        <div class="flex items-center space-x-2 text-slate-300">
                            <i class="fa-solid fa-envelope text-pulse-teal w-4"></i>
                            <a href="mailto:{{ $settingsHelper['email'] }}" class="hover:text-white font-medium transition-colors">{{ $settingsHelper['email'] }}</a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 text-white pt-1">
                        @if($settingsHelper['facebook'])
                            <a class="w-8 h-8 rounded-lg bg-white/10 hover:bg-pulse-orange flex items-center justify-center transition-colors" href="{{ $settingsHelper['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook">
                                <i class="fa-brands fa-facebook-f text-xs"></i>
                            </a>
                        @endif
                        @if($settingsHelper['instagram'])
                            <a class="w-8 h-8 rounded-lg bg-white/10 hover:bg-pulse-orange flex items-center justify-center transition-colors" href="{{ $settingsHelper['instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram">
                                <i class="fa-brands fa-instagram text-xs"></i>
                            </a>
                        @endif
                        <a class="w-8 h-8 rounded-lg bg-white/10 hover:bg-pulse-orange flex items-center justify-center transition-colors" href="https://tiktok.com/@shoppulss" target="_blank" rel="noopener" aria-label="TikTok">
                            <i class="fa-brands fa-tiktok text-xs"></i>
                        </a>
                    </div>
                </div>

                {{-- Shop Categories (From DB) --}}
                <div>
                    <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Shop Categories</h4>
                    <ul class="space-y-2">
                        @foreach($navCategories->take(6) as $fCat)
                            <li>
                                <a class="hover:text-white transition-colors" href="{{ route('categories.show', $fCat->slug) }}">
                                    {{ $fCat->name }}
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <a class="text-pulse-orange font-semibold hover:underline" href="{{ route('products.index', ['sort' => 'sale']) }}">
                                ⚡ Daily Deals
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Customer Care --}}
                <div>
                    <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Customer Care</h4>
                    <ul class="space-y-2">
                        <li><a class="hover:text-white transition-colors" href="{{ route('orders.track') }}">Track My Order</a></li>
                        <li><a class="hover:text-white transition-colors" href="{{ route('products.index') }}">Complete Catalog</a></li>
                        <li><a class="hover:text-white transition-colors" href="{{ route('cart.index') }}">Shopping Cart</a></li>
                        @auth
                            <li><a class="hover:text-white transition-colors" href="{{ route('account.orders') }}">Order History</a></li>
                            <li><a class="hover:text-white transition-colors" href="{{ route('account.profile') }}">My Profile</a></li>
                        @else
                            <li><a class="hover:text-white transition-colors" href="{{ route('login') }}">Sign In / Register</a></li>
                        @endauth
                        <li><a class="hover:text-white transition-colors" href="#why-shop">Authenticity Guarantee</a></li>
                    </ul>
                </div>

                {{-- Stay Updated --}}
                <div>
                    <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-3">Stay Updated</h4>
                    <p class="text-[11px] text-slate-400 mb-3">Get direct alerts for warehouse flash sales and authentic product drops.</p>
                    <form class="space-y-2" id="footer-newsletter-form" onsubmit="handleNewsletter(event, this)">
                        <input class="w-full bg-white/5 border border-white/15 rounded-lg px-3 py-2 text-xs text-white placeholder:text-slate-500 focus:outline-none focus:border-pulse-orange" placeholder="Enter your email" type="email" required>
                        <button class="w-full bg-pulse-orange hover:bg-pulse-orange-dark text-white font-extrabold py-2.5 rounded-lg text-xs uppercase tracking-wider transition-colors" type="submit">
                            SUBSCRIBE NOW
                        </button>
                    </form>
                    <span class="block text-[10px] text-slate-400 text-center mt-2.5">
                        <a href="https://wa.me/{{ $cleanWhatsapp }}" target="_blank" rel="noopener" class="text-pulse-teal hover:underline flex items-center justify-center gap-1.5">
                            <i class="fa-brands fa-whatsapp"></i>
                            <span>WhatsApp Support ({{ $settingsHelper['whatsapp'] }})</span>
                        </a>
                    </span>
                </div>
            </div>

            {{-- Trust Badges & Guarantee Micro Strip (Free Delivery Removed Per Prompt) --}}
            <div class="py-6 flex flex-wrap items-center justify-between text-slate-400 text-[11px] gap-4 border-b border-white/5">
                <div class="flex items-center space-x-6 flex-wrap gap-y-2">
                    <span class="flex items-center space-x-1.5"><i class="fa-solid fa-check text-emerald-400"></i><span>100% Brand Authentic</span></span>
                    <span class="flex items-center space-x-1.5"><i class="fa-solid fa-check text-pulse-orange"></i><span>Cash on Delivery (COD)</span></span>
                    <span class="flex items-center space-x-1.5"><i class="fa-solid fa-check text-pulse-teal"></i><span>7-Day Easy Returns</span></span>
                    <span class="flex items-center space-x-1.5"><i class="fa-solid fa-check text-blue-400"></i><span>Nationwide Express Logistics</span></span>
                </div>
                <div class="text-slate-500 text-[10px]">
                    Fulfillment: ShopPulss Direct Logistics Hub
                </div>
            </div>

            {{-- Bottom Credits and Legal --}}
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-500 gap-2">
                <p>© {{ date('Y') }} ShopPulss. All Rights Reserved. Single-store direct logistics.</p>
                <div class="flex items-center space-x-4">
                    <a class="hover:text-slate-300 transition-colors" href="#">Privacy Policy</a>
                    <a class="hover:text-slate-300 transition-colors" href="#">Terms of Service</a>
                    <a class="hover:text-slate-300 transition-colors" href="#">Return Policy</a>
                </div>
            </div>
        </div>
    </footer>
    {{-- END: Global Footer --}}

    {{-- Toast Notification Container --}}
    <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>

    {{-- Floating WhatsApp Action Button --}}
    <a href="https://wa.me/{{ $cleanWhatsapp }}" target="_blank" rel="noopener" class="fixed bottom-6 left-6 z-40 bg-[#25D366] hover:bg-[#20ba59] text-white rounded-full p-3.5 shadow-2xl flex items-center justify-center transition-all duration-300 hover:scale-110 group focus:outline-none focus:ring-4 focus:ring-emerald-300 cursor-pointer" aria-label="Contact us on WhatsApp" title="WhatsApp: {{ $settingsHelper['whatsapp'] }}">
        <i class="fa-brands fa-whatsapp text-2xl"></i>
        <span class="max-w-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs group-hover:ml-2 text-xs font-bold transition-all duration-300 ease-in-out">WhatsApp: {{ $settingsHelper['whatsapp'] }}</span>
    </a>

    {{-- Global Interactive Scripts --}}
    <script>
        // Mobile Drawer toggle
        function toggleMobileDrawer() {
            const drawer = document.getElementById('mobile-drawer-backdrop');
            if (drawer) drawer.classList.toggle('hidden');
        }

        // User dropdown menu toggle
        function toggleUserMenu() {
            const dropdown = document.getElementById('user-menu-dropdown');
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        // Nav categories dropdown toggle in secondary nav
        function toggleNavCategoriesMenu(e) {
            if (e) {
                e.stopPropagation();
            }
            const menu = document.getElementById('nav-cat-dropdown-menu');
            const chevron = document.getElementById('nav-cat-chevron');
            if (menu) menu.classList.toggle('hidden');
            if (chevron) chevron.classList.toggle('rotate-180');
        }

        // Header Category Select Change Handler
        function handleNavbarCategoryChange(select) {
            const qInput = document.querySelector('#header-search-form input[name="q"]');
            const q = qInput ? qInput.value.trim() : '';
            if (q) {
                document.getElementById('header-search-form').submit();
            } else if (select.value) {
                window.location.href = "{{ url('/categories') }}/" + encodeURIComponent(select.value);
            } else {
                window.location.href = "{{ route('products.index') }}";
            }
        }

        // Close dropdowns on outer click
        document.addEventListener('click', (e) => {
            const userContainer = document.getElementById('user-menu-container');
            const userDropdown = document.getElementById('user-menu-dropdown');
            if (userContainer && userDropdown && !userContainer.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }

            const navCatContainer = document.getElementById('nav-cat-dropdown-container');
            const navCatMenu = document.getElementById('nav-cat-dropdown-menu');
            const navCatChevron = document.getElementById('nav-cat-chevron');
            if (navCatContainer && navCatMenu && !navCatContainer.contains(e.target)) {
                navCatMenu.classList.add('hidden');
                if (navCatChevron) navCatChevron.classList.remove('rotate-180');
            }
        });

        // Toast utility
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-pulse-navy' : (type === 'error' ? 'bg-rose-700' : 'bg-slate-800');
            const icon = type === 'success' ? '<i class="fa-solid fa-check text-emerald-400"></i>' : '<i class="fa-solid fa-triangle-exclamation text-amber-400"></i>';

            toast.className = `${bgColor} text-white px-5 py-3 rounded-2xl shadow-card flex items-center space-x-3 text-xs font-bold pointer-events-auto transform translate-y-4 opacity-0 transition-all duration-300`;
            toast.innerHTML = `${icon}<span>${message}</span>`;
            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('translate-y-4', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        // Newsletter subscription handler
        function handleNewsletter(e, form) {
            e.preventDefault();
            const input = form.querySelector('input[type="email"]');
            if (!input || !input.value || !input.validity.valid) {
                showToast('Please enter a valid email address.', 'error');
                return;
            }
            const email = input.value;
            try {
                const subs = JSON.parse(localStorage.getItem('shoppulss_newsletter') || '[]');
                if (!subs.includes(email)) subs.push(email);
                localStorage.setItem('shoppulss_newsletter', JSON.stringify(subs));
            } catch (err) {}

            input.value = '';
            showToast('🎉 Thank you for subscribing to ShopPulss VIP updates!', 'success');
        }

        // Valid product IDs passed from backend
        const validProductIds = @json($validProductIds);

        // Wishlist client-side management
        function toggleWishlist(productId, btn) {
            try {
                const pId = parseInt(productId);
                if (!validProductIds.includes(pId)) {
                    showToast('This product is currently unavailable', 'error');
                    return;
                }
                let wishlist = JSON.parse(localStorage.getItem('shoppulss_wishlist') || '[]');
                if (!Array.isArray(wishlist)) wishlist = [];
                wishlist = wishlist.map(x => parseInt(x)).filter(id => validProductIds.includes(id));

                const idx = wishlist.indexOf(pId);
                const heart = btn.querySelector('i');
                if (idx > -1) {
                    wishlist.splice(idx, 1);
                    if (heart) {
                        heart.className = 'fa-regular fa-heart';
                        heart.classList.remove('text-rose-500');
                    }
                    showToast('Item removed from wishlist');
                } else {
                    wishlist.push(pId);
                    if (heart) {
                        heart.className = 'fa-solid fa-heart text-rose-500';
                    }
                    showToast('Added to your wishlist ❤️');
                }
                localStorage.setItem('shoppulss_wishlist', JSON.stringify(wishlist));
                updateWishlistBadge();
            } catch (err) {}
        }

        function updateWishlistBadge() {
            try {
                let wishlist = JSON.parse(localStorage.getItem('shoppulss_wishlist') || '[]');
                if (Array.isArray(wishlist)) {
                    // Filter out any IDs that no longer exist or are not published
                    wishlist = wishlist.map(x => parseInt(x)).filter(id => validProductIds.includes(id));
                    localStorage.setItem('shoppulss_wishlist', JSON.stringify(wishlist));
                } else {
                    wishlist = [];
                    localStorage.setItem('shoppulss_wishlist', '[]');
                }

                const badge = document.getElementById('wishlist-badge');
                if (badge) {
                    if (wishlist.length > 0) {
                        badge.textContent = wishlist.length;
                        badge.classList.remove('hidden');
                        badge.classList.add('flex');
                    } else {
                        badge.textContent = '0';
                        badge.classList.add('hidden');
                        badge.classList.remove('flex');
                    }
                }
            } catch (err) {}
        }

        // Global AJAX Add-To-Cart listener
        document.addEventListener('submit', async (e) => {
            const form = e.target.closest('.ajax-add-to-cart');
            if (!form) return;

            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalHtml = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> <span>Adding...</span>';
            }

            try {
                const formData = new FormData(form);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();
                if (data.success) {
                    showToast(data.message || 'Item added to cart!');
                    // Update cart badge and total price
                    const countEl = document.getElementById('nav-cart-count');
                    const totalEl = document.getElementById('nav-cart-total');
                    const labelEl = document.getElementById('nav-cart-label');

                    if (countEl && data.cart_count !== undefined) {
                        countEl.textContent = data.cart_count;
                        if (data.cart_count > 0) {
                            countEl.classList.remove('hidden');
                            countEl.classList.add('flex');
                        }
                        countEl.classList.add('scale-125');
                        setTimeout(() => countEl.classList.remove('scale-125'), 300);
                    }

                    if (totalEl && data.cart_total_formatted !== undefined) {
                        totalEl.textContent = data.cart_total_formatted;
                        totalEl.classList.add('text-pulse-orange');
                        setTimeout(() => totalEl.classList.remove('text-pulse-orange'), 400);
                    }

                    if (labelEl && data.cart_count !== undefined) {
                        labelEl.textContent = data.cart_count > 0 ? 'Cart Total' : 'Cart';
                    }
                } else {
                    showToast(data.message || 'Could not add item to cart', 'error');
                }
            } catch (err) {
                // Fallback to normal form submit if fetch fails
                form.submit();
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;
                }
            }
        });

        // Initialize state
        document.addEventListener('DOMContentLoaded', () => {
            updateWishlistBadge();

            // Sync hearts on loaded products
            try {
                const wishlist = JSON.parse(localStorage.getItem('shoppulss_wishlist') || '[]');
                document.querySelectorAll('[data-wishlist-id]').forEach(btn => {
                    const id = parseInt(btn.getAttribute('data-wishlist-id'));
                    if (wishlist.includes(id)) {
                        const heart = btn.querySelector('i');
                        if (heart) heart.className = 'fa-solid fa-heart text-rose-500';
                    }
                });
            } catch (err) {}
        });
    </script>

    @stack('scripts')
</body>
</html>
