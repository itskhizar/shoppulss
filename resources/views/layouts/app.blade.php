<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ShopPulss - Premium Essentials. Direct to You.')</title>
    <meta name="description" content="@yield('description', 'Shop authentic products at fair prices. Handpicked from 100+ trusted brands. Free delivery over Rs 2,500.')">

    <!-- Canonical -->
    @hasSection('canonical')<link rel="canonical" href="@yield('canonical')">@endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased" style="font-family: 'Inter', sans-serif;">

    {{-- Announcement Bar --}}
    <div id="announcement-bar" class="text-white text-xs py-2 px-4 text-center relative" style="background-color: #0F1B4D;">
        <div class="max-w-screen-xl mx-auto flex items-center justify-between flex-wrap gap-1">
            <div class="hidden md:flex items-center gap-1 text-blue-200 text-xs">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/></svg>
                Pakistan's Verified Direct Retail Store
            </div>
            <p class="flex-1 text-center text-white text-xs font-medium">
                {!! $announcementText ?? '🚀 Free delivery over Rs 2,500 &nbsp;&nbsp;|&nbsp;&nbsp; 💰 Cash on Delivery (COD) available nationwide' !!}
            </p>
            <div class="hidden md:flex items-center gap-3 text-blue-200 text-xs">
                <a href="tel:+923000000000" class="flex items-center gap-1 hover:text-white transition-colors">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    Helpline: +92 300 0000000
                </a>
                <a href="{{ route('orders.track') }}" class="flex items-center gap-1 hover:text-white transition-colors">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H11a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h4l2 4v4h-6V7z"/></svg>
                    Track Order
                </a>
            </div>
        </div>
    </div>

    {{-- Main Navbar --}}
    <header id="main-header" class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-100 transition-all duration-300">
        <div class="max-w-screen-xl mx-auto px-4">
            <div class="flex items-center h-16 gap-4">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2" id="site-logo">
                    <div class="flex items-center gap-1.5">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-black" style="background-color: #00A8B8;">S</div>
                        <span class="text-xl font-black tracking-tight" style="color: #0F1B4D;">Shop<span style="color: #00A8B8;">Pulss</span></span>
                    </div>
                </a>

                {{-- Search --}}
                <div class="flex-1 max-w-2xl mx-2 md:mx-4">
                    <form action="{{ route('search') }}" method="GET" class="relative" id="search-form">
                        <div class="relative flex items-center">
                            <input
                                id="search-input"
                                type="search"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Search direct authentic products, gadgets, essentials..."
                                class="w-full h-10 pl-4 pr-12 rounded-lg border border-gray-200 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-100 transition-all"
                                style="--tw-ring-color: rgba(0,168,184,0.15);"
                            >
                            <button type="submit" class="absolute right-0 top-0 h-10 w-10 flex items-center justify-center rounded-r-lg text-white transition-colors" style="background-color: #00A8B8;" id="search-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Right Actions --}}
                @php
                    $cartCount = app(\App\Services\CartService::class)->getCount();
                @endphp
                <div class="flex items-center gap-1 md:gap-3 flex-shrink-0">
                    {{-- Shop / Catalog Link --}}
                    <a href="{{ route('products.index') }}" class="hidden lg:flex items-center gap-1 text-xs font-semibold text-gray-600 hover:text-teal-600 px-2 py-1.5 rounded-lg transition-colors">
                        Catalog
                    </a>

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}" id="cart-btn" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-50 transition-colors group relative" title="Cart">
                        <div class="relative">
                            <svg class="w-6 h-6 text-gray-700 group-hover:text-teal-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span class="absolute -top-1.5 -right-2 px-1.5 py-0.5 min-w-4 text-center text-white text-xs font-bold rounded-full" style="background-color: #FF6B35; font-size: 10px;">{{ $cartCount }}</span>
                        </div>
                        <div class="hidden md:block text-left">
                            <div class="text-[11px] text-gray-400 font-medium leading-none">Cart</div>
                            <div class="text-xs font-bold leading-tight mt-0.5" style="color: #0F1B4D;">{{ $cartCount > 0 ? $cartCount . ' items' : 'Empty' }}</div>
                        </div>
                    </a>

                    {{-- Auth Dropdown / Button --}}
                    @auth
                        <div class="relative group" x-data="{ open: false }">
                            <button type="button" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-50 transition-colors text-left" id="user-menu-btn">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background-color: #0F1B4D;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="hidden md:block">
                                    <div class="text-xs font-bold text-gray-800 leading-none truncate max-w-[100px]">{{ Auth::user()->name }}</div>
                                    <div class="text-[10px] text-teal-600 font-semibold leading-none mt-0.5">{{ Auth::user()->isAdmin() ? 'Admin' : 'Customer' }}</div>
                                </div>
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div class="hidden group-hover:block hover:block absolute right-0 top-full pt-1 w-48 z-50">
                                <div class="bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 text-xs text-gray-700">
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 font-semibold text-teal-600 hover:bg-teal-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                            Admin Dashboard
                                        </a>
                                        <div class="border-t border-gray-100 my-1"></div>
                                    @endif
                                    <a href="{{ route('account.dashboard') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        My Profile
                                    </a>
                                    <a href="{{ route('account.orders') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        My Orders
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 text-left">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" id="auth-btn" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:border-teal-500 hover:text-teal-600 transition-colors" title="Account">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Sign In</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Category Navigation Bar --}}
        <div class="border-t border-gray-100" style="background-color: #0F1B4D;">
            <div class="max-w-screen-xl mx-auto px-4">
                <nav class="flex items-center gap-0 overflow-x-auto scrollbar-none">
                    <a href="{{ route('products.index') }}" id="all-categories-btn" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-white whitespace-nowrap transition-colors hover:bg-white/10 border-r border-white/10 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        All Categories
                    </a>
                    @foreach(\App\Models\Category::active()->parents()->orderBy('display_order')->limit(8)->get() as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="px-4 py-2.5 text-xs font-medium text-blue-100 whitespace-nowrap transition-colors hover:text-white hover:bg-white/10 flex-shrink-0">{{ $cat->name }}</a>
                    @endforeach
                    <a href="{{ route('products.index', ['sort' => 'sale']) }}" class="px-4 py-2.5 text-xs font-bold whitespace-nowrap flex-shrink-0 ml-auto hover:opacity-90" style="color: #FF6B35;">🔥 Hot Deals</a>
                </nav>
            </div>
        </div>
    </header>

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="bg-emerald-50 border-b border-emerald-200 py-3 px-4 text-emerald-800 text-sm">
        <div class="max-w-screen-xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2 font-medium">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-b border-red-200 py-3 px-4 text-red-800 text-sm">
        <div class="max-w-screen-xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2 font-medium">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        </div>
    </div>
    @endif

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- WhatsApp Support Banner --}}
    <section class="py-5 px-4" style="background: linear-gradient(135deg, #f0fafb 0%, #e6f7f9 100%); border-top: 3px solid #00A8B8;">
        <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: #00A8B8;">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-sm" style="color: #0F1B4D;">Need assistance before ordering?</p>
                    <p class="text-xs text-gray-500 mt-0.5">Chat directly with our Karachi customer support team on WhatsApp: <strong>{{ $whatsappNumber ?? '+92 300 000-0000' }}</strong>. We'll answer product questions, confirm availability, and help you get your first ShopPulss order from door to door before it arrives.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="https://wa.me/923000000000" id="whatsapp-support-btn" class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-white text-sm font-semibold transition-transform hover:scale-105" style="background-color: #25D366;">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp Helpline
                </a>
                <form action="{{ route('orders.track') }}" method="GET" class="flex items-center gap-1" id="track-order-form">
                    <input type="text" name="order_number" placeholder="Enter Order ID..." class="border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-teal-400 w-36">
                    <button type="submit" class="px-3 py-2 rounded-lg text-xs text-white font-semibold" style="background-color: #0F1B4D;">Track</button>
                </form>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer style="background-color: #0F1B4D;" class="text-white">
        <div class="max-w-screen-xl mx-auto px-4 pt-12 pb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">

                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white text-xs font-black" style="background-color: #00A8B8;">S</div>
                        <span class="text-xl font-black">Shop<span style="color: #00A8B8;">Pulss</span></span>
                    </div>
                    <p class="text-blue-200 text-sm leading-relaxed mb-4">Pakistan's premier direct-to-consumer retail store for authentic electronics, fashion, home essentials, and personal care. 100% genuine products shipped directly from our warehouse.</p>
                    <div class="flex items-center gap-3">
                        <a href="#" id="footer-facebook" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" id="footer-instagram" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" id="footer-whatsapp" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Categories (Dynamic from DB) --}}
                <div>
                    <h3 class="font-bold text-sm mb-4 uppercase tracking-wider" style="color: #00A8B8;">Shop Categories</h3>
                    <ul class="space-y-2 text-sm text-blue-200">
                        @foreach(\App\Models\Category::active()->parents()->orderBy('display_order')->limit(6)->get() as $fCat)
                            <li><a href="{{ route('categories.show', $fCat->slug) }}" class="hover:text-white transition-colors">{{ $fCat->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('products.index', ['sort' => 'sale']) }}" class="hover:text-white transition-colors" style="color: #FF6B35;">🔥 Daily Deals</a></li>
                    </ul>
                </div>

                {{-- Customer Care --}}
                <div>
                    <h3 class="font-bold text-sm mb-4 uppercase tracking-wider" style="color: #00A8B8;">Customer Care</h3>
                    <ul class="space-y-2 text-sm text-blue-200">
                        <li><a href="{{ route('orders.track') }}" class="hover:text-white transition-colors flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span> Track My Order</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Complete Catalog</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition-colors">Shopping Cart</a></li>
                        @auth
                            <li><a href="{{ route('account.orders') }}" class="hover:text-white transition-colors">Order History</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Sign In / Register</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div>
                    <h3 class="font-bold text-sm mb-4 uppercase tracking-wider" style="color: #00A8B8;">Stay Updated</h3>
                    <p class="text-blue-200 text-sm mb-3">Get early access to exclusive client opportunities and direct relationships.</p>
                    <form class="flex gap-2" id="newsletter-form">
                        <input type="email" name="email" placeholder="Your email address" class="flex-1 px-3 py-2 rounded-lg text-sm text-gray-800 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 min-w-0" style="--tw-ring-color: rgba(0,168,184,0.3);">
                        <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white flex-shrink-0 hover:opacity-90 transition-opacity" style="background-color: #FF6B35;" id="newsletter-submit">Subscribe</button>
                    </form>
                    <p class="text-blue-200 text-xs mt-2">📲 Also join our WhatsApp channel:</p>
                    <a href="#" class="text-teal-300 text-xs hover:text-white transition-colors">Join WhatsApp Group →</a>
                </div>

            </div>

            {{-- Trust badges --}}
            <div class="border-t border-white/10 pt-6 flex flex-wrap items-center justify-center gap-6 mb-4">
                <div class="flex items-center gap-2 text-blue-200 text-xs">
                    <svg class="w-4 h-4" style="color: #00A8B8;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1l2.928 5.928L19 8l-4.5 4.388.063 6.348-5.563-2.924L3.437 18.736l.063-6.348L-1 8l6.072-1.072z" clip-rule="evenodd"/></svg>
                    Nationwide Delivery
                </div>
                <div class="flex items-center gap-2 text-blue-200 text-xs">
                    <svg class="w-4 h-4" style="color: #00A8B8;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    100% Authentic
                </div>
                <div class="flex items-center gap-2 text-blue-200 text-xs">
                    <svg class="w-4 h-4" style="color: #00A8B8;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>
                    7-Day Easy Returns
                </div>
                <div class="flex items-center gap-2 text-blue-200 text-xs">
                    <svg class="w-4 h-4" style="color: #00A8B8;" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.028 2.353 1.118V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.028-2.354-1.118V5z" clip-rule="evenodd"/></svg>
                    Cash on Delivery & Pay
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="border-t border-white/10 pt-4 flex flex-col md:flex-row items-center justify-between gap-2 text-blue-200 text-xs">
                <p>© 2025 ShopPulss. All Rights Reserved. Single-store logistics resulting in verifiable trust.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms & Conditions</a>
                    <a href="#" class="hover:text-white transition-colors">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Sticky Cart Fab for Mobile --}}
    <a href="{{ route('cart.index') }}" id="mobile-cart-fab" class="fixed bottom-6 right-4 md:hidden w-14 h-14 rounded-full text-white shadow-lg flex items-center justify-center z-50 transition-transform hover:scale-110" style="background-color: #00A8B8;" title="View Shopping Cart">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
    </a>

    <script>
        // Sticky header enhancement on scroll
        const header = document.getElementById('main-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 80) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
