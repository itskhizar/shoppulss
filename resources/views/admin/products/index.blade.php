@extends('layouts.admin')

@section('title', 'Manage Products')
@section('header', 'Products Catalog')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- Top Action & Filter Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-gray-900" style="color: #0F1B4D;">Products ({{ $products->total() }})</h1>
            <p class="text-xs text-gray-500">Manage catalog inventory, pricing, and visibility</p>
        </div>

        <a
            href="{{ route('admin.products.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white font-semibold text-xs shadow-sm hover:opacity-95 transition-all self-start sm:self-auto"
            style="background-color: #00A8B8;"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add New Product
        </a>
    </div>

    {{-- Filter & Search Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-3">
            {{-- Search input with properly centered icon --}}
            <div class="relative flex-1 min-w-[240px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    id="productSearchInput"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search product name, SKU..."
                    class="w-full h-10 pl-10 pr-4 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs text-gray-800 placeholder-gray-400 transition-colors focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8]"
                    oninput="filterProductsTable(this.value)"
                >
            </div>

            {{-- Category Filter --}}
            <select name="category_id" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[170px] cursor-pointer" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $parent)
                    <option value="{{ $parent->id }}" {{ request('category_id') == $parent->id ? 'selected' : '' }} class="font-bold">
                        {{ $parent->name }}
                    </option>
                    @if($parent->children->isNotEmpty())
                        @foreach($parent->children as $sub)
                            <option value="{{ $sub->id }}" {{ request('category_id') == $sub->id ? 'selected' : '' }}>
                                &nbsp;&nbsp;↳ {{ $sub->name }}
                            </option>
                        @endforeach
                    @endif
                @endforeach
            </select>

            {{-- Status Filter --}}
            <select name="status" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[130px] cursor-pointer" onchange="this.form.submit()">
                <option value="">Status: All</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>

            {{-- Stock Filter --}}
            <select name="stock" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[130px] cursor-pointer" onchange="this.form.submit()">
                <option value="">Stock: All</option>
                <option value="in" {{ request('stock') === 'in' ? 'selected' : '' }}>In Stock</option>
                <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Low Stock</option>
                <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Out of Stock</option>
            </select>

            {{-- Sort Filter --}}
            <select name="sort" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[130px] cursor-pointer" onchange="this.form.submit()">
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price High</option>
                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price Low</option>
                <option value="stock_low" {{ request('sort') === 'stock_low' ? 'selected' : '' }}>Stock Low</option>
            </select>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">
                <button type="submit" class="h-10 px-4 rounded-xl text-white font-semibold text-xs shadow-xs hover:opacity-95 transition-opacity inline-flex items-center gap-1.5" style="background-color: #00A8B8;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['q', 'status', 'category_id', 'stock', 'sort']))
                    <a href="{{ route('admin.products.index') }}" class="h-10 px-3.5 flex items-center justify-center rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:text-red-600 hover:border-red-200 hover:bg-red-50/30 transition-colors whitespace-nowrap" title="Reset Filters">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Products Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="py-3.5 px-4">Product</th>
                        <th class="py-3.5 px-4">SKU</th>
                        <th class="py-3.5 px-4">Category</th>
                        <th class="py-3.5 px-4">Price</th>
                        <th class="py-3.5 px-4">Stock</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($products as $prod)
                        <tr class="product-row hover:bg-gray-50/60 transition-colors" data-search="{{ strtolower($prod->name . ' ' . $prod->sku . ' ' . ($prod->category?->name ?? '')) }}">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-50 border border-gray-100 p-1 flex items-center justify-center flex-shrink-0">
                                        <img src="{{ $prod->images->first()?->image_url ?? 'https://placehold.co/40x40' }}" alt="" class="w-full h-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-gray-900 truncate max-w-xs">{{ $prod->name }}</div>
                                        <div class="mt-0.5">
                                            <span class="text-[10px] px-1.5 py-0.5 rounded font-semibold {{ $prod->type === 'variant' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($prod->type ?? 'simple') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-[11px] text-gray-500">
                                {{ $prod->sku ?? ('SKU-' . $prod->id) }}
                            </td>
                            <td class="py-3 px-4 text-gray-700">
                                {{ $prod->category?->name ?? 'General' }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-gray-900">Rs. {{ number_format($prod->sale_price ?? $prod->regular_price) }}</span>
                                @if($prod->sale_price)
                                    <span class="text-[10px] text-gray-400 line-through block">Rs. {{ number_format($prod->regular_price) }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $prod->stock_quantity <= 5 ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $prod->stock_quantity }} units
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $prod->status === 'published' ? 'bg-teal-100 text-teal-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $prod->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.show', $prod->id) }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900 px-2 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                                        View
                                    </a>
                                    <a href="{{ route('admin.products.edit', $prod->id) }}" class="text-xs font-semibold text-teal-600 hover:text-teal-800 px-2 py-1 rounded-lg bg-teal-50 hover:bg-teal-100 transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product permanently?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 px-2 py-1 rounded-lg bg-red-50 hover:bg-red-100 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-xs text-gray-400">No products match your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>

</div>

<script>
function filterProductsTable(query) {
    const q = (query || '').toLowerCase().trim();
    const rows = document.querySelectorAll('.product-row');
    rows.forEach(row => {
        const text = row.getAttribute('data-search') || '';
        if (!q || text.includes(q)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endsection
