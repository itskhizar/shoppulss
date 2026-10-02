@extends('layouts.app')

@section('title', ($search ? "Search results for '{$search}'" : 'All Products') . ' - ShopPulss')

@section('content')
<div class="bg-gray-50 py-6 border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
            <a href="{{ route('home') }}" class="hover:text-teal-600">Home</a>
            <span>/</span>
            <span class="text-gray-800 font-semibold">Catalog</span>
            @if($search)
                <span>/</span>
                <span class="text-teal-600 font-medium">Search: "{{ $search }}"</span>
            @endif
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-navy-900" style="color: #0F1B4D;">
                    @if($search)
                        Search Results for <span style="color: #00A8B8;">"{{ $search }}"</span>
                    @else
                        Direct Authentic Products
                    @endif
                </h1>
                <p class="text-xs text-gray-500 mt-1">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} items</p>
            </div>

            {{-- Sorting --}}
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2 self-start md:self-auto">
                @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif

                <label for="sort-select" class="text-xs font-semibold text-gray-600 whitespace-nowrap">Sort By:</label>
                <select
                    id="sort-select"
                    name="sort"
                    onchange="this.form.submit()"
                    class="h-9 px-3 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-teal-500"
                >
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                    <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Popular</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="sale" {{ $sort === 'sale' ? 'selected' : '' }}>Biggest Discount</option>
                </select>
            </form>
        </div>
    </div>
</div>

<div class="max-w-screen-xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        {{-- Filters Sidebar --}}
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-6 sticky top-24">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h2 class="font-bold text-sm text-navy-900" style="color: #0F1B4D;">Filters</h2>
                    @if(request()->hasAny(['category', 'min_price', 'max_price', 'q']))
                        <a href="{{ route('products.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">Clear All</a>
                    @endif
                </div>

                {{-- Categories Filter --}}
                <div>
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-2.5">Categories</h3>
                    <div class="space-y-1 max-h-56 overflow-y-auto pr-1">
                        @foreach($categories as $cat)
                            <a
                                href="{{ request()->fullUrlWithQuery(['category' => $cat->slug]) }}"
                                class="flex items-center justify-between text-xs py-1.5 px-2 rounded-lg transition-colors {{ request('category') === $cat->slug ? 'bg-teal-50 font-bold text-teal-800' : 'text-gray-600 hover:bg-gray-50' }}"
                            >
                                <span class="truncate">{{ $cat->name }}</span>
                                <span class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">{{ $cat->products_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Price Filter --}}
                <div>
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-2.5">Price Range (PKR)</h3>
                    <form method="GET" action="{{ url()->current() }}" class="space-y-2">
                        @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="grid grid-cols-2 gap-2">
                            <input
                                type="number"
                                name="min_price"
                                value="{{ request('min_price') }}"
                                placeholder="Min"
                                class="h-8 px-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-teal-500"
                            >
                            <input
                                type="number"
                                name="max_price"
                                value="{{ request('max_price') }}"
                                placeholder="Max"
                                class="h-8 px-2 text-xs border border-gray-200 rounded-lg focus:outline-none focus:border-teal-500"
                            >
                        </div>
                        <button
                            type="submit"
                            class="w-full h-8 text-xs font-semibold rounded-lg text-white"
                            style="background-color: #0F1B4D;"
                        >
                            Apply Filter
                        </button>
                    </form>
                </div>

                {{-- Guarantee Banner --}}
                <div class="p-3 bg-teal-50/60 rounded-xl border border-teal-100 text-xs text-teal-900 space-y-1">
                    <div class="font-bold flex items-center gap-1 text-teal-800">
                        <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        ShopPulss Guarantee
                    </div>
                    <p class="text-[11px] text-teal-700 leading-tight">Every item is verified genuine with 7-day hassle-free return.</p>
                </div>
            </div>
        </aside>

        {{-- Products Grid --}}
        <main class="lg:col-span-3">
            @if($products->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 mb-1">No products found</h3>
                    <p class="text-xs text-gray-500 mb-4">Try checking your search spelling or clearing some filters.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold text-white" style="background-color: #00A8B8;">
                        View All Products
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
