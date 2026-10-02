@extends('layouts.app')

@section('title', $category->name . ' - ShopPulss')

@section('content')
<div class="bg-gray-50 py-6 border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
            <a href="{{ route('home') }}" class="hover:text-teal-600">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-teal-600">Categories</a>
            @if($category->parent)
                <span>/</span>
                <a href="{{ route('categories.show', $category->parent->slug) }}" class="hover:text-teal-600">{{ $category->parent->name }}</a>
            @endif
            <span>/</span>
            <span class="text-gray-800 font-semibold">{{ $category->name }}</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-navy-900" style="color: #0F1B4D;">
                    {{ $category->name }}
                </h1>
                @if($category->description)
                    <p class="text-xs text-gray-500 mt-1 max-w-xl">{{ $category->description }}</p>
                @endif
            </div>

            {{-- Sorting --}}
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
                <label for="sort-select" class="text-xs font-semibold text-gray-600 whitespace-nowrap">Sort By:</label>
                <select
                    id="sort-select"
                    name="sort"
                    onchange="this.form.submit()"
                    class="h-9 px-3 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-lg focus:outline-none focus:border-teal-500"
                >
                    <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Popular</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </form>
        </div>

        {{-- Subcategories Pills --}}
        @if($category->children->isNotEmpty())
            <div class="flex items-center gap-2 overflow-x-auto pt-4 pb-1">
                @foreach($category->children as $child)
                    <a
                        href="{{ route('categories.show', $child->slug) }}"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-700 hover:border-teal-500 hover:text-teal-600 whitespace-nowrap shadow-sm transition-colors"
                    >
                        {{ $child->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="max-w-screen-xl mx-auto px-4 py-8">
    @if($products->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
            <h3 class="text-base font-bold text-gray-800 mb-1">No products in this category yet</h3>
            <p class="text-xs text-gray-500 mb-4">We are restocking this section shortly directly from brands.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold text-white" style="background-color: #00A8B8;">
                Browse All Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
