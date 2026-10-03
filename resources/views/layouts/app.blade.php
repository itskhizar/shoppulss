<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ShopPulss - Direct Retail Store Pakistan | 100% Authentic')</title>
    <meta name="description" content="@yield('description', 'Pakistan\'s premier direct-to-consumer store. 100% genuine products, Cash on Delivery nationwide, 7-day easy returns.')">

    <!-- Favicon Set -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Canonical -->
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @endif

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-[#F6F7FB] text-[#161616] font-sans antialiased min-h-screen flex flex-col selection:bg-[#FF5A1F] selection:text-white">

    {{-- 1. Top Announcement Bar --}}
    <div id="announcement-bar" class="bg-[#0F1654] text-white text-[11px] py-2 px-4 border-b border-white/10 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-4 text-blue-100/90 font-medium">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#FF5A1F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    <span>Free Delivery over Rs. 2,500</span>
                </span>
                <span class="opacity-30 hidden sm:inline">|</span>
                <span class="hidden sm:flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#FF5A1F]" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                    <span>Cash on Delivery Available Nationwide</span>
                </span>
            </div>
            <div class="hidden md:flex items-center gap-4 text-blue-100/90 font-medium">
                <a href="{{ route('orders.track') }}" class="hover:text-white transition-colors">Track Order</a>
                <span class="opacity-30">|</span>
                <a href="tel:08007857300" class="hover:text-white transition-colors">Helpline: 0800-PULSE</a>
            </div>
        </div>
    </div>

    {{-- 2. Main Sticky Header: 2-Row Navbar --}}
    <header id="main-header" class="sticky top-0 z-40 bg-white shadow-sm transition-all duration-300">

        {{-- Row A: Logo + Search Bar + Icons --}}
        <div class="bg-white border-b border-[#E6E8F2]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex items-center gap-4 h-[68px]">

                    {{-- Mobile Menu Button --}}
                    <button type="button" onclick="toggleMobileMenu()" class="lg:hidden p-2 rounded-xl text-gray-700 hover:bg-gray-100 focus:outline-none flex-shrink-0" aria-label="Toggle menu" id="mobile-menu-btn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    {{-- Brand Logo --}}
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center group" id="site-logo">
                        <img src="{{ asset('images/shoppulss-logo.svg') }}" alt="ShopPulss" class="h-10 w-auto transition-transform duration-200 group-hover:scale-[1.02]">
                    </a>

                    {{-- Centered Search Bar --}}
                    <form action="{{ route('search') }}" method="GET" class="flex-1 hidden md:flex items-center relative mx-2" id="search-form">
                        <div class="relative w-full flex items-center">
                            <input
                                id="search-input"
                                type="search"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Search products, brands and categories..."
                                class="w-full h-10 pl-4 pr-28 rounded-full bg-[#F6F7FB] border border-[#E6E8F2] text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#FF5A1F] focus:ring-2 focus:ring-[#FF5A1F]/15 transition-all"
                            >
                            <button type="submit" class="absolute right-1 h-8 px-5 rounded-full bg-[#FF5A1F] text-white text-xs font-bold hover:bg-[#FF4A0A] transition-colors" id="search-btn">Search</button>
                        </div>
                    </form>

                    {{-- Right Action Icons --}}
                    @php
                        $cartCount = app(\App\Services\CartService::class)->getCount();
                    @endphp
                    <div class="flex items-center gap-1 flex-shrink-0 ml-auto md:ml-0">

                        {{-- Mobile Search Toggle --}}
                        <button type="button" onclick="toggleMobileSearch()" class="md:hidden p-2 rounded-xl text-gray-700 hover:bg-gray-100 transition-colors" title="Search">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>

                        {{-- Wishlist Icon --}}
                        <a href="{{ route('cart.index') }}" class="relative p-2 rounded-full text-gray-500 hover:text-[#FF5A1F] hover:bg-[#FFF1EA] transition-all" title="Wishlist" aria-label="Wishlist">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </a>

                        {{-- Cart Icon with Count --}}
                        <a href="{{ route('cart.index') }}" id="cart-btn" class="relative p-2 rounded-full text-gray-500 hover:text-[#FF5A1F] hover:bg-[#FFF1EA] transition-all" title="Cart" aria-label="Shopping Cart">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            @if($cartCount > 0)
                                <span class="absolute top-0.5 right-0.5 w-4 h-4 bg-[#FF5A1F] text-white text-[10px] font-black rounded-full flex items-center justify-center leading-none">{{ $cartCount }}</span>
                            @endif
                        </a>

                        {{-- User Account --}}
                        @auth
                            <div class="relative" id="user-menu-wrapper">
                                <button type="button" onclick="toggleUserDropdown()" class="flex items-center gap-1.5 p-1 rounded-full hover:bg-gray-100 transition-colors focus:outline-none" id="user-menu-btn">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-black shadow-sm bg-gradient-to-br from-[#0F1654] to-[#1A237E]">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                </button>
                                <div id="user-dropdown-menu" class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-2xl border border-[#E6E8F2] py-2 z-50">
                                    <div class="px-4 py-2.5 border-b border-gray-100">
                                        <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-[11px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-[#0AA6B7] hover:bg-teal-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            Admin Dashboard
                                        </a>
                                        <div class="border-t border-gray-100 my-1"></div>
                                    @endif
                                    <a href="{{ route('account.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        My Profile
                                    </a>
                                    <a href="{{ route('account.orders') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        My Orders
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs text-red-600 hover:bg-red-50 text-left transition-colors font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" id="auth-btn" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-[#E6E8F2] text-xs font-bold text-[#161616] hover:border-[#FF5A1F] hover:text-[#FF5A1F] transition-all bg-white" title="Sign In">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="hidden sm:inline">Sign In</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Mobile Search Panel --}}
            <div id="mobile-search-panel" class="hidden md:hidden border-t border-gray-100 px-4 py-3 bg-white">
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search products..."
                        class="w-full h-11 pl-4 pr-12 rounded-xl bg-[#F6F7FB] border border-[#E6E8F2] text-sm placeholder-gray-400 focus:outline-none focus:border-[#FF5A1F]">
                    <button type="submit" class="absolute right-0 top-0 h-11 w-12 flex items-center justify-center text-[#FF5A1F]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Row B: Nav Links Bar (Deep Navy) --}}
        <div class="bg-[#0F1654] hidden md:block">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between h-11">
                    <nav class="flex items-center gap-0.5 h-full overflow-x-auto scrollbar-none" id="main-nav">
                        <a href="{{ route('home') }}" class="px-3.5 py-2 text-sm font-semibold whitespace-nowrap transition-colors {{ request()->routeIs('home') ? 'text-[#FF5A1F]' : 'text-blue-100 hover:text-white' }}">Home</a>
                        <a href="{{ route('products.index') }}" class="px-3.5 py-2 text-sm font-semibold whitespace-nowrap transition-colors {{ request()->routeIs('products.index') && !request('sort') ? 'text-[#FF5A1F]' : 'text-blue-100 hover:text-white' }}">Shop</a>
                        <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="px-3.5 py-2 text-sm font-semibold text-blue-100 hover:text-white whitespace-nowrap transition-colors">New Arrivals</a>
                        <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="px-3.5 py-2 text-sm font-semibold text-blue-100 hover:text-white whitespace-nowrap transition-colors">Best Sellers</a>
                        <a href="{{ route('products.index', ['sort' => 'sale']) }}" class="px-3.5 py-2 text-sm font-bold text-[#FF5A1F] hover:text-orange-300 whitespace-nowrap transition-colors">Deals &amp; Flash Sale</a>
                        <a href="{{ route('products.index') }}#categories" class="px-3.5 py-2 text-sm font-semibold text-blue-100 hover:text-white whitespace-nowrap transition-colors">Categories</a>
                    </nav>
                    <a href="{{ route('products.index', ['sort' => 'sale']) }}" class="hidden lg:flex items-center gap-1.5 text-xs font-bold text-[#FF5A1F] hover:text-orange-300 whitespace-nowrap ml-4 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>
                        Mega Clearance Live
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- Mobile Drawer Navigation --}}
    <div id="mobile-menu-drawer" class="fixed inset-0 z-50 bg-black/60 hidden lg:hidden backdrop-blur-xs transition-opacity" onclick="toggleMobileMenu()">
        <div class="w-80 max-w-[85vw] h-full bg-white shadow-2xl flex flex-col" onclick="event.stopPropagation()">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <img src="{{ asset('images/shoppulss-logo.svg') }}" alt="ShopPulss" class="h-8 w-auto">
                <button type="button" onclick="toggleMobileMenu()" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-5 space-y-5">
                <div class="space-y-1 text-sm font-semibold text-gray-800">
                    <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-xl hover:bg-gray-50">Home</a>
                    <a href="{{ route('products.index') }}" class="block px-3 py-2.5 rounded-xl hover:bg-gray-50">Shop All Catalog</a>
                    <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="block px-3 py-2.5 rounded-xl hover:bg-gray-50">New Arrivals</a>
                    <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="block px-3 py-2.5 rounded-xl hover:bg-gray-50">Best Sellers</a>
                    <a href="{{ route('products.index', ['sort' => 'sale']) }}" class="block px-3 py-2.5 rounded-xl text-[#FF5A1F] font-bold">ðŸ”¥ Deals & Flash Sale</a>
                    <a href="{{ route('orders.track') }}" class="block px-3 py-2.5 rounded-xl hover:bg-gray-50">Track My Order</a>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 px-3">Top Departments</div>
                    <div class="space-y-1 text-xs font-medium text-gray-700">
                        @foreach(\App\Models\Category::active()->parents()->orderBy('display_order')->limit(8)->get() as $mCat)
                            <a href="{{ route('categories.show', $mCat->slug) }}" class="block px-3 py-2 rounded-lg hover:bg-gray-50">{{ $mCat->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50">
                @auth
                    <div class="flex items-center justify-between">
                        <div class="text-xs font-bold text-gray-800">{{ Auth::user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-red-600">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="w-full py-2.5 rounded-xl bg-[#0F1654] text-white text-xs font-bold flex items-center justify-center gap-2">
                        Sign In / Register
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="bg-emerald-50 border-b border-emerald-200 py-3.5 px-4 text-emerald-900 text-sm animate-in slide-in-from-top duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2.5 font-medium">
                <span class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs">âœ“</span>
                {{ session('success') }}
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-b border-red-200 py-3.5 px-4 text-red-900 text-sm animate-in slide-in-from-top duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2.5 font-medium">
                <span class="w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center text-xs">!</span>
                {{ session('error') }}
            </div>
        </div>
    </div>
    @endif

    {{-- Main Page Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- WhatsApp Support Strip --}}
    <section class="py-6 px-4 bg-gradient-to-r from-[#FFF1EA] via-white to-[#F6F7FB] border-t border-[#E6E8F2]">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-5">
            <div class="flex items-center gap-4 text-center md:text-left">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white flex-shrink-0 shadow-md bg-[#25D366]">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-[#0F1654]">Need help or advice before ordering?</h3>
                    <p class="text-xs text-gray-500 mt-0.5 max-w-xl">Chat directly with our verified customer operations desk on WhatsApp: <strong class="text-gray-800">{{ $whatsappNumber ?? '+92 300 000-0000' }}</strong></p>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-center">
                <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $whatsappNumber ?? '923000000000') }}" id="whatsapp-support-btn" target="_blank" rel="noopener" class="flex items-center gap-2 px-5 py-2.5 rounded-full text-white text-xs font-bold bg-[#25D366] hover:bg-[#20BA5A] shadow-sm transition-all hover:scale-105">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.945.003-6.556 5.338-11.892 11.893-11.892 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/></svg>
                    WhatsApp Helpline
                </a>
                <form action="{{ route('orders.track') }}" method="GET" class="flex items-center gap-1.5" id="track-order-form">
                    <input type="text" name="order_number" placeholder="Enter Order ID..." class="bg-white border border-[#E6E8F2] rounded-full px-3.5 py-2 text-xs focus:outline-none focus:border-[#0AA6B7] w-36">
                    <button type="submit" class="px-4 py-2 rounded-full text-xs text-white font-bold bg-[#0F1654] hover:bg-[#16206E] transition-colors shadow-2xs">Track</button>
                </form>
            </div>
        </div>
    </section>

    {{-- 4. Master Footer (Deep Navy #0F1654) --}}
    <footer class="bg-[#0F1654] text-white pt-14 pb-8 mt-auto border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">

                {{-- Col 1 & 2: Brand Story --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/shoppulss-logo-white.svg') }}" alt="ShopPulss" class="h-9 w-auto">
                    </div>
                    <p class="text-blue-100/70 text-xs leading-relaxed max-w-sm">
                        Pakistan's premier direct-to-consumer store. We inspect, authenticate, and fulfill every item directly from our central Karachi fulfilment warehouse with zero marketplace middlemen.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="https://facebook.com/shoppulss" id="footer-facebook" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#FF5A1F] transition-colors" title="Facebook">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://instagram.com/shoppulss" id="footer-instagram" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#FF5A1F] transition-colors" title="Instagram">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $whatsappNumber ?? '923000000000') }}" id="footer-whatsapp" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#25D366] transition-colors" title="WhatsApp">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413A11.815 11.815 0 0012.05 0z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Col 3: Shop Categories (Dynamic from DB) --}}
                <div>
                    <h4 class="font-bold text-xs uppercase tracking-wider text-[#0AA6B7] mb-4">Shop Categories</h4>
                    <ul class="space-y-2.5 text-xs text-blue-100/80">
                        @foreach(\App\Models\Category::active()->parents()->orderBy('display_order')->limit(6)->get() as $fCat)
                            <li>
                                <a href="{{ route('categories.show', $fCat->slug) }}" class="hover:text-white transition-colors flex items-center gap-1.5">
                                    <span class="w-1 h-1 rounded-full bg-[#FF5A1F]"></span>
                                    {{ $fCat->name }}
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('products.index', ['sort' => 'sale']) }}" class="text-[#FF5A1F] font-bold hover:underline flex items-center gap-1">
                                ðŸ”¥ Daily Deals
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Col 4: Customer Care --}}
                <div>
                    <h4 class="font-bold text-xs uppercase tracking-wider text-[#0AA6B7] mb-4">Customer Care</h4>
                    <ul class="space-y-2.5 text-xs text-blue-100/80">
                        <li><a href="{{ route('orders.track') }}" class="hover:text-white transition-colors">Track My Order</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Complete Catalog</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition-colors">Shopping Cart</a></li>
                        @auth
                            <li><a href="{{ route('account.orders') }}" class="hover:text-white transition-colors">Order History</a></li>
                            <li><a href="{{ route('account.profile') }}" class="hover:text-white transition-colors">My Profile</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Sign In / Register</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Col 5: Newsletter & Updates --}}
                <div>
                    <h4 class="font-bold text-xs uppercase tracking-wider text-[#0AA6B7] mb-4">Stay Updated</h4>
                    <p class="text-xs text-blue-100/70 mb-3 leading-relaxed">
                        Get direct alerts for warehouse flash sales and authentic product drops.
                    </p>
                    <form class="space-y-2" id="newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing to ShopPulss VIP updates!');">
                        <input
                            type="email"
                            name="email"
                            placeholder="Enter your email"
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl bg-white/10 border border-white/15 text-xs text-white placeholder-blue-200/50 focus:outline-none focus:bg-white/15 focus:border-[#FF5A1F]"
                        >
                        <button
                            type="submit"
                            id="newsletter-submit"
                            class="w-full py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-[#FF6B1F] to-[#FF4A0A] hover:opacity-95 shadow-sp-orange transition-all"
                        >
                            Subscribe Now
                        </button>
                    </form>
                    <div class="pt-3">
                        <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $whatsappNumber ?? '923000000000') }}" class="text-[11px] text-[#0AA6B7] hover:text-white flex items-center gap-1">
                            <span>ðŸ“² Join WhatsApp VIP Channel</span>
                            <span>â†’</span>
                        </a>
                    </div>
                </div>

            </div>

            {{-- 4 Trust Badges Horizontal Strip --}}
            <div class="border-t border-white/10 pt-6 pb-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="flex items-center justify-center gap-2 text-blue-100/80 text-xs">
                    <span class="w-2 h-2 rounded-full bg-[#0AA6B7]"></span>
                    <span>100% Brand Authentic</span>
                </div>
                <div class="flex items-center justify-center gap-2 text-blue-100/80 text-xs">
                    <span class="w-2 h-2 rounded-full bg-[#FF5A1F]"></span>
                    <span>Cash on Delivery (COD)</span>
                </div>
                <div class="flex items-center justify-center gap-2 text-blue-100/80 text-xs">
                    <span class="w-2 h-2 rounded-full bg-[#0AA6B7]"></span>
                    <span>7-Day Easy Returns</span>
                </div>
                <div class="flex items-center justify-center gap-2 text-blue-100/80 text-xs">
                    <span class="w-2 h-2 rounded-full bg-[#FF5A1F]"></span>
                    <span>Nationwide Express Logistics</span>
                </div>
            </div>

            {{-- Bottom Copyright & Legal --}}
            <div class="border-t border-white/10 pt-6 flex flex-col md:flex-row items-center justify-between gap-3 text-[11px] text-blue-100/60">
                <p>Â© {{ date('Y') }} ShopPulss. All Rights Reserved. Single-store direct logistics.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-white transition-colors">Return Policy</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Sticky Mobile Cart FAB --}}
    <a href="{{ route('cart.index') }}" id="mobile-cart-fab" class="fixed bottom-6 right-5 md:hidden w-14 h-14 rounded-full text-white shadow-2xl flex items-center justify-center z-40 transition-transform hover:scale-105 active:scale-95 bg-gradient-to-r from-[#FF6B1F] to-[#FF4A0A]" title="View Shopping Cart">
        <div class="relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span class="absolute -top-1.5 -right-2 px-1.5 py-0.2 bg-[#0F1654] text-white text-[10px] font-black rounded-full">{{ $cartCount }}</span>
        </div>
    </a>

    {{-- Interactive UI Scripts --}}
    <script>
        function toggleMobileMenu() {
            const drawer = document.getElementById('mobile-menu-drawer');
            if (drawer) {
                drawer.classList.toggle('hidden');
            }
        }

        function toggleMobileSearch() {
            const panel = document.getElementById('mobile-search-panel');
            if (panel) {
                panel.classList.toggle('hidden');
            }
        }

        function toggleUserDropdown() {
            const menu = document.getElementById('user-dropdown-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        // Close user dropdown if clicked outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('user-menu-wrapper');
            const menu = document.getElementById('user-dropdown-menu');
            if (wrapper && menu && !wrapper.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        // Sticky header elevation shadow on scroll
        const mainHeader = document.getElementById('main-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 40) {
                mainHeader.classList.add('shadow-md');
            } else {
                mainHeader.classList.remove('shadow-md');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
