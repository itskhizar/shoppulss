@extends('layouts.app')

@section('title', '404 - Page Not Found | ShopPulss')
@section('description', 'The requested page could not be found on ShopPulss. Explore our multi-category direct retail catalog in Pakistan.')
@section('robots', 'noindex, follow')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16 bg-slate-50">
    <div class="max-w-xl w-full text-center bg-white rounded-3xl border border-pulse-border p-8 sm:p-12 shadow-card">
        {{-- 404 Badge Icon --}}
        <div class="w-20 h-20 rounded-3xl bg-pulse-orange/10 text-pulse-orange flex items-center justify-center mx-auto mb-6 text-3xl font-black shadow-2xs">
            <i class="fa-solid fa-compass-slash"></i>
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-pulse-navy mb-3">
            Page Not Found (404)
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mb-8 leading-relaxed max-w-md mx-auto">
            The page you are looking for may have been moved, renamed, or is no longer available. You can search our catalog or explore our popular categories below.
        </p>

        {{-- Direct Search Form --}}
        <form action="{{ route('search') }}" method="GET" class="max-w-md mx-auto mb-8">
            <div class="flex items-center rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden focus-within:bg-white focus-within:border-pulse-orange focus-within:ring-2 focus-within:ring-pulse-orange/20 transition-all shadow-2xs">
                <input
                    type="search"
                    name="q"
                    placeholder="Search gadgets, mobile accessories, essentials..."
                    class="flex-1 px-4 py-3 text-xs text-slate-800 placeholder:text-slate-400 bg-transparent border-0 focus:outline-none focus:ring-0"
                    required
                >
                <button type="submit" class="px-5 py-3 bg-pulse-orange hover:bg-pulse-orange-dark text-white font-bold text-xs uppercase tracking-wider transition-colors shrink-0">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Search
                </button>
            </div>
        </form>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8">
            <a href="{{ route('home') }}" class="px-6 py-2.5 rounded-xl bg-pulse-navy hover:bg-pulse-navy-dark text-white font-bold text-xs transition-colors flex items-center justify-center space-x-1.5 shadow-2xs">
                <i class="fa-solid fa-house text-xs"></i>
                <span>Return to Homepage</span>
            </a>
            <a href="{{ route('products.index') }}" class="px-6 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-bag-shopping text-xs"></i>
                <span>Browse All Products</span>
            </a>
        </div>

        {{-- Quick Category Links --}}
        @php
            $quickCats = \App\Models\Category::active()->parents()->take(5)->get();
        @endphp
        @if($quickCats->isNotEmpty())
            <div class="pt-6 border-t border-slate-100">
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Or jump directly to a category:</span>
                <div class="flex flex-wrap justify-center gap-2">
                    @foreach($quickCats as $qCat)
                        <a href="{{ route('categories.show', $qCat->slug) }}" class="px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-pulse-orange/10 hover:text-pulse-orange border border-slate-200 text-[11px] font-semibold text-slate-600 transition-colors">
                            {{ $qCat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
