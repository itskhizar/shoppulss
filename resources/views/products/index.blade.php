@extends('layouts.app')

@section('title', ($search ? "Search: '{$search}'" : 'Shop All Direct Essentials')
@section('description', 'Browse our complete collection of 100% authentic products. Electronics, fashion, lifestyle essentials. Cash on Delivery available nationwide.')
@section('keywords', 'online shopping Pakistan, Cash on Delivery, ShopPulss, direct retail') . ' - ShopPulss')

@section('content')
{{-- Catalog Top Bar --}}
<div class="bg-white border-b border-[#E6E8F2] py-7">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
            <a href="{{ route('home') }}" class="hover:text-[#FF5A1F] transition-colors">Home</a>
            <span class="text-gray-300">/</span>
            <span class="text-[#0F1654] font-bold">Catalog</span>
            @if($search)
                <span class="text-gray-300">/</span>
                <span class="text-[#FF5A1F] font-semibold">Search: "{{ $search }}"</span>
            @endif
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-[#0F1654]">
                    @if($search)
                        Search Results for <span class="text-[#FF5A1F]">"{{ $search }}"</span>
                    @else
                        Direct Authentic Products
                    @endif
                </h1>
                <p class="text-xs text-gray-500 mt-1">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} items verified and shipped from central facility</p>
            </div>

            {{-- Sorting Selector --}}
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2.5 self-start md:self-auto">
                @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif

                <label for="sort-select" class="text-xs font-bold text-gray-600 whitespace-nowrap">Sort By:</label>
                <select
                    id="sort-select"
                    name="sort"
                    onchange="this.form.submit()"
                    class="h-10 px-3.5 text-xs font-semibold text-[#161616] bg-[#F6F7FB] border border-[#E6E8F2] rounded-xl focus:outline-none focus:border-[#FF5A1F] cursor-pointer"
                >
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                    <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Popular</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="sale" {{ $sort === 'sale' ? 'selected' : '' }}>ðŸ”¥ Biggest Discount</option>
                </select>
            </form>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        {{-- Filters Sidebar --}}
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 shadow-sp-card space-y-6 sticky top-28">
                <div class="flex items-center justify-between pb-3.5 border-b border-gray-100">
                    <h2 class="font-black text-sm text-[#0F1654] uppercase tracking-wider">Filters</h2>
                    @if(request()->hasAny(['category', 'min_price', 'max_price', 'q']))
                        <a href="{{ route('products.index') }}" class="text-xs font-bold text-[#FF5A1F] hover:underline">Reset All</a>
                    @endif
                </div>

                {{-- Categories Filter --}}
                <div>
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Department</h3>
                    <div class="space-y-1 max-h-60 overflow-y-auto pr-1">
                        @foreach($categories as $cat)
                            <a
                                href="{{ request()->fullUrlWithQuery(['category' => $cat->slug]) }}"
                                class="flex items-center justify-between text-xs py-2 px-3 rounded-xl transition-all {{ request('category') === $cat->slug ? 'bg-[#FFF1EA] font-bold text-[#FF5A1F]' : 'text-gray-600 hover:bg-[#F6F7FB]' }}"
                            >
                                <span class="truncate">{{ $cat->name }}</span>
                                <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $cat->products_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Price Filter --}}
                <div class="pt-2 border-t border-gray-100">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Price Range (PKR)</h3>
                    <form method="GET" action="{{ url()->current() }}" class="space-y-3">
                        @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                        @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                        @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

                        <div class="grid grid-cols-2 gap-2">
                            <input
                                type="number"
                                name="min_price"
                                value="{{ request('min_price') }}"
                                placeholder="Min"
                                class="h-9 px-3 text-xs bg-[#F6F7FB] border border-[#E6E8F2] rounded-xl focus:outline-none focus:border-[#FF5A1F]"
                            >
                            <input
                                type="number"
                                name="max_price"
                                value="{{ request('max_price') }}"
                                placeholder="Max"
                                class="h-9 px-3 text-xs bg-[#F6F7FB] border border-[#E6E8F2] rounded-xl focus:outline-none focus:border-[#FF5A1F]"
                            >
                        </div>
                        <button
                            type="submit"
                            class="w-full py-2.5 rounded-xl bg-[#0F1654] hover:bg-[#16206E] text-white text-xs font-bold transition-all shadow-xs"
                        >
                            Apply Filter
                        </button>
                    </form>
                </div>

                {{-- Trust mini badge --}}
                <div class="pt-3 border-t border-gray-100 text-[11px] text-gray-500 space-y-1.5">
                    <div class="flex items-center gap-1.5 font-medium text-[#0F1654]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                        <span>Direct Single Store Sourcing</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-medium text-[#0F1654]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#FF5A1F]"></span>
                        <span>Free Shipping Over Rs. 2,500</span>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Products Grid --}}
        <div class="lg:col-span-3">
            @if($products->isEmpty())
                <div class="bg-white rounded-3xl border border-[#E6E8F2] p-12 text-center shadow-sp-card">
                    <div class="w-16 h-16 rounded-full bg-[#FFF1EA] text-[#FF5A1F] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">No products found</h3>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto mb-6">We couldn't find any products matching your active criteria. Try broadening your search or resetting filters.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-2.5 rounded-full text-xs font-bold text-white bg-gradient-to-r from-[#FF6B1F] to-[#FF4A0A] shadow-sp-orange">
                        View All Products
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
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
@endsection
