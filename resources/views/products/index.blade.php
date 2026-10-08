@extends('layouts.app')

@php
    $currentCategory = request('category') ? \App\Models\Category::where('slug', request('category'))->first() : null;
    $hasActiveFilters = request()->hasAny(['category', 'min_price', 'max_price', 'q', 'sort']) && (request('sort') !== 'newest' || request('category') || request('min_price') || request('max_price') || request('q'));
@endphp


@section('content')
{{-- Catalog Top Bar --}}
<div class="bg-white border-b border-pulse-border py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-3" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-pulse-orange transition-colors">Home</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('products.index') }}" class="{{ $currentCategory || $search ? 'hover:text-pulse-orange' : 'text-pulse-navy font-bold' }} transition-colors">Shop Catalog</a>
            @if($currentCategory)
                @if($currentCategory->parent)
                    <span class="text-slate-300">/</span>
                    <a href="{{ route('products.index', ['category' => $currentCategory->parent->slug]) }}" class="hover:text-pulse-orange transition-colors">{{ $currentCategory->parent->name }}</a>
                @endif
                <span class="text-slate-300">/</span>
                <span class="text-pulse-navy font-bold">{{ $currentCategory->name }}</span>
            @elseif($search)
                <span class="text-slate-300">/</span>
                <span class="text-pulse-orange font-semibold">Search: "{{ $search }}"</span>
            @endif
        </nav>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-pulse-navy tracking-tight">
                    @if($currentCategory)
                        {{ $currentCategory->name }}
                    @elseif($search)
                        Search Results for <span class="text-pulse-orange">"{{ $search }}"</span>
                    @else
                        Explore Complete Catalog
                    @endif
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    Showing <strong class="text-slate-700">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</strong> of <strong class="text-slate-700">{{ $products->total() }}</strong> authentic direct retail items
                </p>
            </div>

            {{-- Controls: Mobile Filter Toggle + Sort Form --}}
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    onclick="toggleFilterSidebar()"
                    class="lg:hidden px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 hover:bg-slate-100 flex items-center gap-2"
                >
                    <i class="fa-solid fa-sliders text-pulse-orange"></i>
                    <span>Filters</span>
                </button>

                <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
                    @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                    @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif

                    <label for="sort-select" class="text-xs font-bold text-slate-600 hidden sm:inline-block">Sort:</label>
                    <select
                        id="sort-select"
                        name="sort"
                        onchange="this.form.submit()"
                        class="h-10 px-3.5 text-xs font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-pulse-orange focus:bg-white cursor-pointer transition-colors"
                    >
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                        <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="sale" {{ $sort === 'sale' ? 'selected' : '' }}>⚡ Biggest Discount</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Active Filters Strip --}}
        @if($hasActiveFilters)
            <div class="flex flex-wrap items-center gap-2 pt-4 mt-4 border-t border-slate-100">
                <span class="text-xs font-bold text-slate-400">Active Filters:</span>

                @if($currentCategory)
                    <a href="{{ request()->fullUrlWithoutQuery(['category', 'page']) }}" class="inline-flex items-center gap-1.5 bg-pulse-orange-light text-pulse-orange text-xs font-bold px-3 py-1 rounded-full border border-pulse-orange/20 hover:bg-orange-100 transition-colors">
                        <span>Category: {{ $currentCategory->name }}</span>
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                    </a>
                @endif

                @if($search)
                    <a href="{{ request()->fullUrlWithoutQuery(['q', 'page']) }}" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-200 hover:bg-blue-100 transition-colors">
                        <span>Search: "{{ $search }}"</span>
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                    </a>
                @endif

                @if(request('min_price') || request('max_price'))
                    <a href="{{ request()->fullUrlWithoutQuery(['min_price', 'max_price', 'page']) }}" class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200 hover:bg-emerald-100 transition-colors">
                        <span>Price: Rs. {{ number_format(request('min_price', 0)) }} - {{ request('max_price') ? 'Rs. '.number_format(request('max_price')) : 'Any' }}</span>
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                    </a>
                @endif

                @if(request('sort') && request('sort') !== 'newest')
                    <a href="{{ request()->fullUrlWithoutQuery(['sort', 'page']) }}" class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 text-xs font-bold px-3 py-1 rounded-full border border-slate-200 hover:bg-slate-200 transition-colors">
                        <span>Sort: {{ ucfirst(str_replace('_', ' ', request('sort'))) }}</span>
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                    </a>
                @endif

                <a href="{{ route('products.index') }}" class="text-xs font-bold text-rose-600 hover:underline ml-2">
                    Clear All Filters
                </a>
            </div>
        @endif
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        {{-- Filters Sidebar (Desktop sticky & Mobile toggleable) --}}
        <aside id="catalog-filter-sidebar" class="hidden lg:block lg:col-span-1">
            <div class="bg-white rounded-3xl border border-pulse-border p-6 shadow-subtle space-y-6 sticky top-28">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                    <h2 class="font-black text-xs text-pulse-navy uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-pulse-orange"></i>
                        <span>Catalog Filters</span>
                    </h2>
                    @if($hasActiveFilters)
                        <a href="{{ route('products.index') }}" class="text-xs font-bold text-pulse-orange hover:underline">Reset</a>
                    @endif
                </div>

                {{-- Categories & Subcategories Filter --}}
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2.5">Departments</h3>
                    <div class="space-y-1 max-h-72 overflow-y-auto pr-1">
                        <a
                            href="{{ request()->fullUrlWithoutQuery(['category', 'page']) }}"
                            class="flex items-center justify-between text-xs py-2 px-3 rounded-xl transition-all {{ !request('category') ? 'bg-pulse-navy text-white font-bold' : 'text-slate-600 hover:bg-slate-50' }}"
                        >
                            <span>All Products</span>
                            <span class="text-[10px] font-bold {{ !request('category') ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }} px-2 py-0.5 rounded-full">{{ \App\Models\Product::published()->count() }}</span>
                        </a>

                        @foreach($categories as $cat)
                            @php
                                $isCatActive = request('category') === $cat->slug;
                                $subcats = $cat->children;
                                $hasActiveSubcat = $subcats->contains(fn($sub) => $sub->slug === request('category'));
                            @endphp
                            <div class="space-y-1">
                                <a
                                    href="{{ request()->fullUrlWithQuery(['category' => $cat->slug, 'page' => null]) }}"
                                    class="flex items-center justify-between text-xs py-2 px-3 rounded-xl transition-all {{ ($isCatActive || $hasActiveSubcat) ? 'bg-pulse-orange-light text-pulse-orange font-bold' : 'text-slate-600 hover:bg-slate-50' }}"
                                >
                                    <span class="truncate">{{ $cat->name }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{{ $cat->products_count }}</span>
                                </a>

                                {{-- Nested Subcategories if available --}}
                                @if($subcats->isNotEmpty() && ($isCatActive || $hasActiveSubcat))
                                    <div class="pl-4 pr-1 space-y-1 py-1 border-l-2 border-pulse-orange/20 ml-2">
                                        @foreach($subcats as $subcat)
                                            <a
                                                href="{{ request()->fullUrlWithQuery(['category' => $subcat->slug, 'page' => null]) }}"
                                                class="flex items-center justify-between text-[11px] py-1 px-2.5 rounded-lg transition-colors {{ request('category') === $subcat->slug ? 'bg-pulse-orange text-white font-bold' : 'text-slate-500 hover:text-pulse-orange hover:bg-slate-50' }}"
                                            >
                                                <span class="truncate">{{ $subcat->name }}</span>
                                                <span class="text-[9px] font-mono opacity-80">{{ $subcat->products()->count() }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Price Filter --}}
                <div class="pt-3 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2.5">Price Range (PKR)</h3>
                    <form method="GET" action="{{ url()->current() }}" class="space-y-3">
                        @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="grid grid-cols-2 gap-2">
                            <input
                                type="number"
                                name="min_price"
                                value="{{ request('min_price') }}"
                                placeholder="Min (Rs.)"
                                class="h-9 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-pulse-orange focus:bg-white"
                            >
                            <input
                                type="number"
                                name="max_price"
                                value="{{ request('max_price') }}"
                                placeholder="Max (Rs.)"
                                class="h-9 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-pulse-orange focus:bg-white"
                            >
                        </div>
                        <button
                            type="submit"
                            class="w-full py-2.5 rounded-xl bg-pulse-navy hover:bg-pulse-navy-dark text-white text-xs font-bold transition-all shadow-xs"
                        >
                            Apply Price
                        </button>
                    </form>

                    {{-- Quick Price Pills --}}
                    <div class="flex flex-wrap gap-1.5 pt-3">
                        <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => 2000, 'page' => null]) }}" class="text-[10px] font-semibold px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:border-pulse-orange hover:text-pulse-orange bg-slate-50 transition-colors">
                            Under Rs. 2k
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['min_price' => 2000, 'max_price' => 5000, 'page' => null]) }}" class="text-[10px] font-semibold px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:border-pulse-orange hover:text-pulse-orange bg-slate-50 transition-colors">
                            Rs. 2k - 5k
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['min_price' => 5000, 'max_price' => null, 'page' => null]) }}" class="text-[10px] font-semibold px-2.5 py-1 rounded-lg border border-slate-200 text-slate-600 hover:border-pulse-orange hover:text-pulse-orange bg-slate-50 transition-colors">
                            Above Rs. 5k
                        </a>
                    </div>
                </div>

                {{-- Direct Trust Assurance --}}
                <div class="pt-4 border-t border-slate-100 text-[11px] text-slate-600 space-y-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-shield-check text-emerald-500 text-xs"></i>
                        <span>100% Brand Authentic</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-money-bill-wave text-pulse-orange text-xs"></i>
                        <span>Cash on Delivery (COD)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-arrow-rotate-left text-pulse-teal text-xs"></i>
                        <span>7-Day Easy Returns</span>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Products Grid --}}
        <div class="lg:col-span-3">
            @if($products->isEmpty())
                <div class="bg-white rounded-3xl border border-pulse-border p-12 text-center shadow-card">
                    <div class="w-16 h-16 rounded-2xl bg-pulse-orange-light text-pulse-orange flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                    <h3 class="text-base font-extrabold text-pulse-navy mb-1">No products found</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto mb-6 leading-relaxed">
                        We couldn't find any products matching your active criteria. Try broadening your search or resetting filters.
                    </p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-pulse-orange hover:bg-pulse-orange-dark shadow-orange-glow transition-all">
                        <span>View All Products</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </div>
</div>

<script>
function toggleFilterSidebar() {
    const sidebar = document.getElementById('catalog-filter-sidebar');
    if (sidebar) {
        sidebar.classList.toggle('hidden');
    }
}
</script>
@endsection
