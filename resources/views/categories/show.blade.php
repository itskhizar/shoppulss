@extends('layouts.app')

@section('content')
<div class="bg-white border-b border-pulse-border py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-3" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-pulse-orange transition-colors">Home</a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-pulse-orange transition-colors">Shop</a>
            @if($category->parent)
                <span class="text-slate-300">/</span>
                <a href="{{ route('categories.show', $category->parent->slug) }}" class="hover:text-pulse-orange transition-colors">{{ $category->parent->name }}</a>
            @endif
            <span class="text-slate-300">/</span>
            <span class="text-pulse-navy font-bold">{{ $category->name }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-pulse-navy tracking-tight">
                    {{ $category->name }}
                </h1>
                @if($category->description)
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl leading-relaxed">{{ $category->description }}</p>
                @endif
            </div>

            {{-- Sorting --}}
            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2 self-start md:self-auto">
                <label for="sort-select" class="text-xs font-bold text-slate-600 whitespace-nowrap">Sort By:</label>
                <select
                    id="sort-select"
                    name="sort"
                    onchange="this.form.submit()"
                    class="h-10 px-3.5 text-xs font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-pulse-orange focus:bg-white cursor-pointer transition-colors"
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
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1 shrink-0">Subcategories:</span>
                @foreach($category->children as $child)
                    <a
                        href="{{ route('categories.show', $child->slug) }}"
                        class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-50 border border-slate-200 text-slate-700 hover:border-pulse-orange hover:text-pulse-orange hover:bg-white whitespace-nowrap transition-all shadow-2xs"
                    >
                        {{ $child->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    @if($products->isEmpty())
        <div class="bg-white rounded-3xl border border-pulse-border p-12 text-center shadow-card max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-pulse-orange-light text-pulse-orange flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h3 class="text-base font-extrabold text-pulse-navy mb-1">No products in this category yet</h3>
            <p class="text-xs text-slate-500 mb-6 leading-relaxed">We are currently restocking authenticated items for this department directly from suppliers.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-pulse-orange hover:bg-pulse-orange-dark shadow-orange-glow transition-all">
                <span>Browse Full Catalog</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
