@extends('layouts.admin')

@section('title', 'Product Details: ' . $product->name)
@section('header', 'Product Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Breadcrumb & Quick Action Buttons --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.products.index') }}" class="hover:text-teal-600 font-medium">Products</a>
                <span>/</span>
                <span class="text-gray-800 font-semibold">{{ $product->name }}</span>
            </div>
            <h1 class="text-xl md:text-2xl font-black text-gray-900 flex items-center gap-2" style="color: #0F1B4D;">
                {{ $product->name }}
                @if($product->status === 'published')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Published</span>
                @elseif($product->status === 'draft')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">Draft</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600">Archived</span>
                @endif
                @if($product->is_featured)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">★ Featured</span>
                @endif
                @if($product->is_new)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">New</span>
                @endif
            </h1>
            <p class="text-xs text-gray-400 mt-0.5">SKU: <span class="font-mono font-bold text-gray-700">{{ $product->sku }}</span> • Created on {{ $product->created_at->format('M d, Y - h:i A') }}</p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
            <a
                href="{{ route('products.show', $product->slug) }}"
                target="_blank"
                class="px-3.5 py-2 rounded-xl border border-gray-200 text-xs font-semibold text-gray-700 hover:border-teal-500 hover:text-teal-600 bg-white shadow-sm transition-all"
            >
                View on Storefront ↗
            </a>
            <a
                href="{{ route('admin.products.edit', $product->id) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold text-white shadow-sm hover:opacity-95 transition-all flex items-center gap-1.5"
                style="background-color: #00A8B8;"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Product
            </a>
            <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Are you sure you want to delete this product? Historical orders will be safely protected.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 bg-white text-xs font-semibold transition-all" title="Delete Product">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Performance KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Sold</span>
            <div class="text-2xl font-black text-gray-900 mt-1" style="color: #0F1B4D;">{{ number_format($totalSold) }} units</div>
            <span class="text-[11px] text-gray-400 mt-1 block">In orders placed</span>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Revenue Generated</span>
            <div class="text-2xl font-black text-teal-600 mt-1">Rs. {{ number_format($totalRevenue) }}</div>
            <span class="text-[11px] text-gray-400 mt-1 block">Gross item revenue</span>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Current Stock</span>
            <div class="text-2xl font-black {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-amber-600' : 'text-gray-900' }} mt-1">
                {{ number_format($product->stock_quantity) }}
            </div>
            <span class="text-[11px] {{ $product->stock_quantity <= $product->low_stock_threshold ? 'text-amber-600 font-semibold' : 'text-gray-400' }} mt-1 block">
                Threshold: {{ $product->low_stock_threshold }}
            </span>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Active Unit Price</span>
            <div class="text-2xl font-black text-gray-900 mt-1" style="color: #0F1B4D;">
                Rs. {{ number_format($product->sale_price ?? $product->regular_price) }}
            </div>
            @if($product->sale_price)
                <span class="text-[11px] text-red-500 font-semibold mt-1 block line-through">
                    Regular: Rs. {{ number_format($product->regular_price) }}
                </span>
            @else
                <span class="text-[11px] text-emerald-600 font-semibold mt-1 block">Standard Regular</span>
            @endif
        </div>
    </div>

    {{-- Main Grid: 2 Cols Left, 1 Col Right --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Columns: Media Gallery, Descriptions, Attributes, Orders --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Media Gallery --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider" style="color: #0F1B4D;">Product Images ({{ $product->images->count() }})</h2>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">+ Add / Manage Images</a>
                </div>

                @if($product->images->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($product->images as $img)
                            <div class="relative group rounded-xl overflow-hidden border border-gray-200 aspect-square bg-gray-50 flex items-center justify-center">
                                <img src="{{ $img->image_url }}" alt="{{ $img->alt_text ?? $product->name }}" class="w-full h-full object-cover">
                                @if($img->is_featured)
                                    <span class="absolute top-2 left-2 bg-teal-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">Primary</span>
                                @endif
                                <a href="{{ $img->image_url }}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold">
                                    View Full ↗
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-gray-400 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs font-semibold">No images uploaded for this product yet.</p>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-block mt-2 text-xs font-bold text-teal-600 hover:underline">Upload Images in Edit Mode →</a>
                    </div>
                @endif
            </div>

            {{-- Descriptions & Overview --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100" style="color: #0F1B4D;">
                    Description & Overview
                </h2>

                @if($product->short_description)
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Short Summary</span>
                        <p class="text-xs font-semibold text-gray-800 bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed">{{ $product->short_description }}</p>
                    </div>
                @endif

                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Full Detailed Description</span>
                    <div class="text-xs text-gray-700 leading-relaxed bg-white border border-gray-100 p-4 rounded-xl prose max-w-none">
                        {!! nl2br(e($product->description ?? 'No detailed description provided.')) !!}
                    </div>
                </div>
            </div>

            {{-- Category Dynamic Attributes --}}
            @if($product->category && $product->category->attributes->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-3">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100" style="color: #0F1B4D;">
                        Specifications & Category Attributes
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($product->category->attributes as $attr)
                            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between text-xs">
                                <span class="font-bold text-gray-700">{{ $attr->name }}</span>
                                <span class="text-gray-500 font-medium">
                                    {{ $attr->values->pluck('value')->join(', ') ?: 'Configurable' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Recent Customer Orders Referencing this Product --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider" style="color: #0F1B4D;">Recent Orders with this Item</h2>
                    <span class="text-[11px] text-gray-400">Showing last 10 entries</span>
                </div>

                @if($orderItems->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-gray-600">
                            <thead class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                <tr>
                                    <th class="py-3 px-4">Order #</th>
                                    <th class="py-3 px-4">Customer</th>
                                    <th class="py-3 px-4">Qty</th>
                                    <th class="py-3 px-4">Charged</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 font-medium">
                                @foreach($orderItems as $item)
                                    <tr class="hover:bg-gray-50/60 transition-colors">
                                        <td class="py-3 px-4 font-mono font-bold text-teal-600">
                                            @if($item->order)
                                                <a href="{{ route('admin.orders.show', $item->order->id) }}" class="hover:underline">
                                                    {{ $item->order->order_number }}
                                                </a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 font-semibold text-gray-900">
                                            {{ $item->order?->customer_name ?? $item->order?->email ?? 'Guest' }}
                                        </td>
                                        <td class="py-3 px-4 font-bold text-gray-800">×{{ $item->quantity }}</td>
                                        <td class="py-3 px-4 font-bold text-gray-900">Rs. {{ number_format($item->total_price) }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700">
                                                {{ $item->order?->status ?? 'pending' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-right text-gray-400">
                                            {{ $item->created_at ? $item->created_at->format('M d, Y') : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-xs text-gray-400">
                        No orders recorded for this product yet.
                    </div>
                @endif
            </div>

        </div>

        {{-- Right 1 Column: Category, Inventory, Variants, Metadata --}}
        <div class="space-y-6">

            {{-- Category Hierarchy Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4 text-xs">
                <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 uppercase tracking-wider" style="color: #0F1B4D;">
                    Category Hierarchy
                </h2>

                <div>
                    <span class="text-gray-400 block mb-0.5">Parent Category:</span>
                    @if($product->category && $product->category->parent)
                        <span class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                            {{ $product->category->parent->name }}
                        </span>
                    @elseif($product->category)
                        <span class="font-bold text-gray-900 text-sm flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                            {{ $product->category->name }} (Main Parent)
                        </span>
                    @else
                        <span class="text-gray-400 italic">Uncategorized</span>
                    @endif
                </div>

                @if($product->category && $product->category->parent)
                    <div>
                        <span class="text-gray-400 block mb-0.5">Subcategory:</span>
                        <span class="font-bold text-gray-800 text-sm flex items-center gap-1.5">
                            <span class="text-teal-600 font-black">↳</span>
                            {{ $product->category->name }}
                        </span>
                    </div>
                @endif

                @if($product->category)
                    <div class="pt-2">
                        <a href="{{ route('admin.categories.index', ['parent_id' => $product->category->parent_id ?? $product->category->id]) }}" class="text-teal-600 font-bold hover:underline flex items-center gap-1">
                            Browse this category in Admin →
                        </a>
                    </div>
                @endif
            </div>

            {{-- Product Variants --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4 text-xs">
                <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 uppercase tracking-wider" style="color: #0F1B4D;">
                    Product Variants ({{ $product->variants->count() }})
                </h2>

                <div class="space-y-2">
                    @forelse($product->variants as $variant)
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 space-y-1">
                            <div class="flex items-center justify-between font-bold text-gray-900">
                                <span>{{ $variant->name }}</span>
                                <span class="text-teal-600">Rs. {{ number_format($variant->sale_price ?? $variant->price) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-gray-400 font-mono">
                                <span>{{ $variant->sku }}</span>
                                <span class="{{ $variant->stock_quantity > 0 ? 'text-gray-700' : 'text-red-500 font-bold' }}">
                                    Stock: {{ $variant->stock_quantity }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-xs italic">No variants created. Standard SKU applies.</p>
                    @endforelse
                </div>
            </div>

            {{-- Metadata & System Details --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-3 text-xs">
                <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100 uppercase tracking-wider" style="color: #0F1B4D;">
                    Catalog Metadata
                </h2>

                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-400">Product ID:</span>
                    <span class="font-mono font-bold text-gray-800">#{{ $product->id }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-400">Slug:</span>
                    <span class="font-mono text-gray-700 text-[11px] truncate max-w-[160px]">{{ $product->slug }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-400">Product Type:</span>
                    <span class="font-semibold text-gray-800 capitalize">{{ $product->type }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-400">Created:</span>
                    <span class="text-gray-800 font-medium">{{ $product->created_at->format('M d, Y - h:i A') }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-400">Last Modified:</span>
                    <span class="text-gray-800 font-medium">{{ $product->updated_at->format('M d, Y - h:i A') }}</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
