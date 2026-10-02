@extends('layouts.app')

@section('title', $category->name . ' - ShopPulss Direct Retail')
@section('description', $category->description ?? "Explore {$category->name} with 100% authentic inventory and Cash on Delivery nationwide.")

@section('content')
<div class="bg-white border-b border-[#E6E8F2] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        {{-- Breadcrumbs --}}
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
            <a href="{{ route('home') }}" class="hover:text-[#FF5A1F] transition-colors">Home</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-[#FF5A1F] transition-colors">Categories</a>
            @if($category->parent)
                <span class="text-gray-300">/</span>
                <a href="{{ route('categories.show', $category->parent->slug) }}" class="hover:text-[#FF5A1F] transition-colors">{{ $category->parent->name }}</a>
            @endif
            <span class="text-gray-300">/</span>
            <span class="text-[#0F1654] font-bold">{{ $category->name }}</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-[#0F1654]">
                    {{ $category->name }}
                </h1>
                @if($category->description)
                    <p class="text-xs sm:text-sm text-gray-500 mt-1 max-w-2xl leading-relaxed">{{ $category->description }}</p>
                @endif
            </div>

            {{-- Sorting --}}
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2 self-start md:self-auto">
                <label for="sort-select" class="text-xs font-bold text-gray-600 whitespace-nowrap">Sort By:</label>
                <select
                    id="sort-select"
                    name="sort"
                    onchange="this.form.submit()"
                    class="h-10 px-3.5 text-xs font-semibold text-[#161616] bg-[#F6F7FB] border border-[#E6E8F2] rounded-xl focus:outline-none focus:border-[#FF5A1F] cursor-pointer"
                >
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Popular</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>
        </div>

        {{-- Subcategory Quick Pills --}}
        @if($category->children->isNotEmpty())
            <div class="flex items-center gap-2 overflow-x-auto pt-5 pb-1 scrollbar-none">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-1">Subcategories:</span>
                @foreach($category->children as $child)
                    <a
                        href="{{ route('categories.show', $child->slug) }}"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#F6F7FB] border border-[#E6E8F2] text-gray-700 hover:border-[#FF5A1F] hover:text-[#FF5A1F] hover:bg-white whitespace-nowrap transition-all shadow-2xs"
                    >
                        {{ $child->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
    @if($products->isEmpty())
        <div class="bg-white rounded-3xl border border-[#E6E8F2] p-12 text-center shadow-sp-card max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-full bg-[#FFF1EA] text-[#FF5A1F] flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-1">No products in this category yet</h3>
            <p class="text-xs text-gray-500 mb-6">We are currently restocking authenticated items for this department directly from suppliers.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-2.5 rounded-full text-xs font-bold text-white bg-gradient-to-r from-[#FF6B1F] to-[#FF4A0A] shadow-sp-orange">
                Browse Full Catalog
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($products as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
