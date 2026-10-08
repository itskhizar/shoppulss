<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - ShopPulss Admin</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F6F7FB] font-sans antialiased text-[#161616]" style="font-family: 'Inter', system-ui, sans-serif;">

<div class="h-screen flex overflow-hidden bg-[#F6F7FB]">

    {{-- Desktop Sidebar (Fixed height to screen, independently scrollable) --}}
    <aside class="w-64 bg-[#0F1654] text-white flex-shrink-0 flex-col justify-between hidden lg:flex h-screen border-r border-white/10 z-40 select-none">
        <div class="flex-1 flex flex-col min-h-0">
            {{-- Brand Logo Header --}}
            <div class="h-16 flex items-center px-5 border-b border-white/10 gap-2.5 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 group" aria-label="ShopPulss Admin">
                    <img
                        src="{{ asset('images/shoppulss-logo-white.png') }}"
                        alt="ShopPulss"
                        width="150"
                        height="32"
                        class="h-8 w-auto max-w-[150px] object-contain"
                        style="height: 32px; max-height: 34px; width: auto; max-width: 150px; object-fit: contain;"
                    >
                    <div class="hidden items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#FF6B1F] to-[#FF4A0A] flex items-center justify-center text-white text-xs font-black shadow-sm">S</div>
                        <div class="flex flex-col">
                            <span class="text-sm font-black tracking-tight leading-tight">Shop<span class="text-[#FF5A1F]">Pulss</span></span>
                        </div>
                    </div>
                </a>
                <span class="ml-auto text-[9px] font-black uppercase tracking-wider bg-orange-grad text-white px-2 py-0.5 rounded-full shadow-xs">ADMIN</span>
            </div>

            {{-- Navigation links (Role-Gated & Grouped) --}}
            <nav class="flex-1 px-3 py-4 space-y-4 overflow-y-auto scrollbar-thin">
                {{-- Group: Overview --}}
                <div class="space-y-1">
                    <div class="px-3 text-[10px] font-black uppercase tracking-wider text-blue-200/40">Core</div>
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-orange-grad text-white shadow-sp-orange font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}"
                    >
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                {{-- Group: Catalog --}}
                @if(Auth::user()->canManageCatalog())
                    <div class="space-y-1">
                        <div class="px-3 pt-2 text-[10px] font-black uppercase tracking-wider text-blue-200/40">Catalog</div>
                        <a
                            href="{{ route('admin.products.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.products*') ? 'bg-orange-grad text-white shadow-sp-orange font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}"
                        >
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>Products Catalog</span>
                        </a>

                        <a
                            href="{{ route('admin.categories.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.categories*') ? 'bg-orange-grad text-white shadow-sp-orange font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}"
                        >
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <span>Categories & Hierarchy</span>
                        </a>
                    </div>
                @endif

                {{-- Group: Operations --}}
                @if(Auth::user()->canManageOrders() || Auth::user()->canManageCustomers())
                    <div class="space-y-1">
                        <div class="px-3 pt-2 text-[10px] font-black uppercase tracking-wider text-blue-200/40">Operations</div>
                        @if(Auth::user()->canManageOrders())
                            <a
                                href="{{ route('admin.orders.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.orders*') ? 'bg-orange-grad text-white shadow-sp-orange font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}"
                            >
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>Orders</span>
                            </a>

                            <a
                                href="{{ route('admin.shipments.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.shipments*') ? 'bg-orange-grad text-white shadow-sp-orange font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}"
                            >
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                <span>Shipments & Logistics</span>
                            </a>

                            <a
                                href="{{ route('admin.payments.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.payments*') ? 'bg-orange-grad text-white shadow-sp-orange font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}"
                            >
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span>Payments & Verification</span>
                            </a>
                        @endif

                        @if(Auth::user()->canManageCustomers())
                            <a
                                href="{{ route('admin.customers.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.customers*') ? 'bg-orange-grad text-white shadow-sp-orange font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}"
                            >
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span>Retail Customers</span>
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Group: Administration --}}
                @if(Auth::user()->canManageStaff())
                    <div class="space-y-1">
                        <div class="px-3 pt-2 text-[10px] font-black uppercase tracking-wider text-blue-200/40">Administration</div>
                        <a
                            href="{{ route('admin.staff.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.staff*') ? 'bg-orange-grad text-white shadow-sp-orange font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}"
                        >
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            <span>Staff & Permissions</span>
                        </a>
                    </div>
                @endif
            </nav>
        </div>

        {{-- Bottom section: Storefront link & User info --}}
        <div class="p-3.5 border-t border-white/10 space-y-2.5 flex-shrink-0 bg-[#0A0F38]">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-[#0AA6B7] hover:bg-white/10 transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Storefront
                </span>
                <span class="text-[10px]">↗</span>
            </a>

            <div class="flex items-center justify-between px-2 pt-2 border-t border-white/5">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#FF6B1F] to-[#FF4A0A] text-white flex items-center justify-center font-black text-xs flex-shrink-0 shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="text-xs truncate">
                        <div class="font-bold text-white truncate max-w-[120px]">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-[#0AA6B7] font-semibold truncate">{{ Auth::user()->role_title }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-blue-200/70 hover:text-red-400 hover:bg-white/10 rounded-lg transition-colors" title="Logout">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Mobile Sidebar Drawer --}}
    <div id="mobileSidebarBackdrop" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 hidden transition-opacity lg:hidden" onclick="toggleMobileSidebar()"></div>
    <aside id="mobileSidebar" class="fixed inset-y-0 left-0 w-72 bg-[#0F1654] text-white z-50 flex flex-col justify-between transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
        <div class="flex-1 flex flex-col min-h-0">
            <div class="h-16 flex items-center justify-between px-5 border-b border-white/10 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2" aria-label="ShopPulss Admin">
                    <img
                        src="{{ asset('images/shoppulss-logo-white.png') }}"
                        alt="ShopPulss"
                        width="140"
                        height="28"
                        class="h-7 w-auto max-w-[140px] object-contain"
                        style="height: 28px; max-height: 30px; width: auto; max-width: 140px; object-fit: contain;"
                    >
                </a>
                <button type="button" onclick="toggleMobileSidebar()" class="p-2 text-blue-200 hover:text-white rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-4 overflow-y-auto scrollbar-thin">
                <div class="space-y-1">
                    <div class="px-3 text-[10px] font-black uppercase tracking-wider text-blue-200/40">Core</div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-orange-grad text-white font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                @if(Auth::user()->canManageCatalog())
                    <div class="space-y-1">
                        <div class="px-3 pt-2 text-[10px] font-black uppercase tracking-wider text-blue-200/40">Catalog</div>
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.products*') ? 'bg-orange-grad text-white font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>Products Catalog</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.categories*') ? 'bg-orange-grad text-white font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <span>Categories & Hierarchy</span>
                        </a>
                    </div>
                @endif

                @if(Auth::user()->canManageOrders() || Auth::user()->canManageCustomers())
                    <div class="space-y-1">
                        <div class="px-3 pt-2 text-[10px] font-black uppercase tracking-wider text-blue-200/40">Operations</div>
                        @if(Auth::user()->canManageOrders())
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.orders*') ? 'bg-orange-grad text-white font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>Orders</span>
                            </a>
                            <a href="{{ route('admin.shipments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.shipments*') ? 'bg-orange-grad text-white font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                <span>Shipments & Logistics</span>
                            </a>
                            <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.payments*') ? 'bg-orange-grad text-white font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                <span>Payments & Verification</span>
                            </a>
                        @endif
                        @if(Auth::user()->canManageCustomers())
                            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.customers*') ? 'bg-orange-grad text-white font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <span>Retail Customers</span>
                            </a>
                        @endif
                    </div>
                @endif

                @if(Auth::user()->canManageStaff())
                    <div class="space-y-1">
                        <div class="px-3 pt-2 text-[10px] font-black uppercase tracking-wider text-blue-200/40">Administration</div>
                        <a href="{{ route('admin.staff.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs transition-all {{ request()->routeIs('admin.staff*') ? 'bg-orange-grad text-white font-bold' : 'text-blue-100/80 hover:text-white hover:bg-white/10 font-semibold' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            <span>Staff & Permissions</span>
                        </a>
                    </div>
                @endif
            </nav>
        </div>

        <div class="p-3.5 border-t border-white/10 space-y-2 flex-shrink-0 bg-[#0A0F38]">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-[#0AA6B7] hover:bg-white/10 transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Storefront
                </span>
                <span>↗</span>
            </a>
            <div class="flex items-center justify-between px-2 pt-2 border-t border-white/5">
                <div class="text-xs truncate">
                    <div class="font-bold text-white truncate max-w-[130px]">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] text-[#0AA6B7] font-semibold truncate">{{ Auth::user()->role_title }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-blue-200/70 hover:text-red-400 hover:bg-white/10 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Admin Content Area (Scrolls independently while sidebar stays static) --}}
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-y-auto">

        {{-- Top Admin Bar --}}
        <header class="h-16 bg-white border-b border-[#E6E8F2] flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shadow-xs flex-shrink-0">
            <div class="flex items-center gap-3">
                {{-- Mobile hamburger trigger --}}
                <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-xl border border-[#E6E8F2] text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors" title="Toggle Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <span class="font-black text-base sm:text-lg text-[#0F1654]">
                    @yield('header', 'Admin Console')
                </span>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                <span class="text-xs text-gray-400 hidden sm:inline">Role: <strong class="text-[#0AA6B7]">{{ Auth::user()->role_title }}</strong></span>
                <a href="{{ route('home') }}" target="_blank" class="text-xs font-bold text-gray-700 hover:text-[#FF5A1F] px-3.5 py-1.5 rounded-full border border-[#E6E8F2] hover:border-[#FF5A1F] transition-all inline-flex items-center gap-1.5 bg-white shadow-xs">
                    <span>Storefront</span>
                    <span class="text-[10px]">↗</span>
                </a>
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#0F1654] to-[#16206E] text-white flex items-center justify-center font-black text-xs shadow-xs">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success m-4 mb-0 flex-shrink-0">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error m-4 mb-0 flex-shrink-0">
                <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Main Page Body --}}
        <main class="flex-1 p-4 sm:p-6 md:p-8">
            @yield('content')
        </main>
    </div>

</div>

<script>
function toggleMobileSidebar() {
    const sidebar = document.getElementById('mobileSidebar');
    const backdrop = document.getElementById('mobileSidebarBackdrop');
    if (!sidebar || !backdrop) return;
    
    const isClosed = sidebar.classList.contains('-translate-x-full');
    if (isClosed) {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
    }
}
</script>

@stack('scripts')
</body>
</html>
