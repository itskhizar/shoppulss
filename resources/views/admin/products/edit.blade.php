@extends('layouts.admin')

@section('title', 'Edit Product - ' . $product->name)
@section('header', 'Edit Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-gray-900" style="color: #0F1B4D;">Edit: {{ $product->name }}</h1>
            <p class="text-xs text-gray-500">Update product information and catalog state</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="text-xs font-semibold text-teal-600 hover:text-teal-700">
                View on Storefront ↗
            </a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900">
                ← Back
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
            <p class="font-bold mb-1">Please fix the following issues:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Information --}}
        <div>
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Product Title *</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $product->name) }}"
                        required
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >
                </div>

                <div>
                    <label for="sku" class="block text-xs font-semibold text-gray-700 mb-1">SKU / Identifier *</label>
                    <input
                        id="sku"
                        type="text"
                        name="sku"
                        value="{{ old('sku', $product->sku) }}"
                        required
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm font-mono focus:outline-none focus:border-teal-500"
                    >
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-semibold text-gray-700 mb-1">Category / Sub-category *</label>
                    <select
                        id="category_id"
                        name="category_id"
                        required
                        onchange="loadCategoryAttributes(this.value)"
                        class="w-full h-11 px-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >
                        @foreach($categories as $parent)
                            <optgroup label="📁 {{ $parent->name }}">
                                <option value="{{ $parent->id }}" {{ old('category_id', $product->category_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }} (Main Category)
                                </option>
                                @foreach($parent->children as $child)
                                    <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;↳ {{ $child->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-xs font-semibold text-gray-700 mb-1">Product Type *</label>
                    <select
                        id="type"
                        name="type"
                        required
                        class="w-full h-11 px-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >
                        <option value="simple" {{ old('type', $product->type ?? 'simple') === 'simple' ? 'selected' : '' }}>Simple Product</option>
                        <option value="variant" {{ old('type', $product->type) === 'variant' ? 'selected' : '' }}>Variant Product (Multiple Options)</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-700 mb-1">Publish Status *</label>
                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full h-11 px-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >
                        <option value="published" {{ old('status', $product->status) === 'published' ? 'selected' : '' }}>Published (Active)</option>
                        <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ old('status', $product->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Dynamic Category Attributes display --}}
        <div id="category-attributes-container" class="p-4 rounded-xl bg-blue-50/50 border border-blue-100">
            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-2">Category Attributes:</h3>
            <div id="category-attributes-list" class="flex flex-wrap gap-2 text-xs">
                @if($product->category && $product->category->attributes->isNotEmpty())
                    @foreach($product->category->attributes as $attr)
                        <span class="px-2.5 py-1 bg-white rounded-lg border border-blue-200 text-blue-900 font-semibold shadow-xs">
                            {{ $attr->name }}
                            @if($attr->values->isNotEmpty())
                                <span class="text-gray-400 font-normal">({{ $attr->values->pluck('value')->join(', ') }})</span>
                            @endif
                        </span>
                    @endforeach
                @else
                    <span class="text-gray-400">No specific attributes linked to this category yet.</span>
                @endif
            </div>
        </div>

        {{-- Pricing & Stock --}}
        <div>
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Pricing & Inventory (PKR)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="regular_price" class="block text-xs font-semibold text-gray-700 mb-1">Regular Price *</label>
                    <input
                        id="regular_price"
                        type="number"
                        step="0.01"
                        name="regular_price"
                        value="{{ old('regular_price', $product->regular_price) }}"
                        required
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >
                </div>

                <div>
                    <label for="sale_price" class="block text-xs font-semibold text-gray-700 mb-1">Sale / Discount Price</label>
                    <input
                        id="sale_price"
                        type="number"
                        step="0.01"
                        name="sale_price"
                        value="{{ old('sale_price', $product->sale_price) }}"
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >
                </div>

                <div>
                    <label for="stock_quantity" class="block text-xs font-semibold text-gray-700 mb-1">Stock Count *</label>
                    <input
                        id="stock_quantity"
                        type="number"
                        name="stock_quantity"
                        value="{{ old('stock_quantity', $product->stock_quantity) }}"
                        required
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >
                </div>
            </div>
        </div>

        {{-- Media --}}
        <div>
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Product Media</h2>

            @if($product->images->isNotEmpty())
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Current Product Images</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                        @foreach($product->images as $img)
                            <div class="relative group rounded-xl border border-gray-200 p-2 bg-gray-50 flex flex-col items-center">
                                <img src="{{ $img->image_url }}" alt="{{ $img->alt_text }}" class="w-full h-24 object-contain rounded-lg">
                                <div class="mt-2 flex items-center justify-between w-full text-[10px]">
                                    @if($img->is_featured)
                                        <span class="px-1.5 py-0.5 rounded bg-teal-100 text-teal-800 font-bold">Featured</span>
                                    @else
                                        <span class="text-gray-400">Order: {{ $img->display_order }}</span>
                                    @endif
                                    <label class="inline-flex items-center gap-1 text-red-500 hover:text-red-700 cursor-pointer font-semibold" title="Mark to remove">
                                        <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="w-3.5 h-3.5 rounded text-red-600 focus:ring-red-500">
                                        <span>Del</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">Check "Del" on any image you want to remove upon saving.</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="images" class="block text-xs font-semibold text-gray-700 mb-1">Upload Additional Images</label>
                    <input
                        id="images"
                        type="file"
                        name="images[]"
                        multiple
                        accept="image/jpeg,image/png,image/jpg,image/webp,image/avif"
                        class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-200 rounded-lg p-1.5 focus:outline-none focus:border-teal-500"
                    >
                    <p class="text-[11px] text-gray-400 mt-1">Add new photos (JPEG, PNG, WebP, AVIF up to 20MB each).</p>
                </div>

                <div>
                    <label for="image_url" class="block text-xs font-semibold text-gray-700 mb-1">Or Add/Update Primary Image URL</label>
                    <input
                        id="image_url"
                        type="url"
                        name="image_url"
                        value="{{ old('image_url') }}"
                        placeholder="https://images.unsplash.com/photo-..."
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm font-mono focus:outline-none focus:border-teal-500"
                    >
                    <p class="text-[11px] text-gray-400 mt-1">Optional direct image URL.</p>
                </div>
            </div>
        </div>

        {{-- Descriptions --}}
        <div>
            <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Descriptions & Flags</h2>
            <div class="space-y-4">
                <div>
                    <label for="short_description" class="block text-xs font-semibold text-gray-700 mb-1">Short Summary</label>
                    <input
                        id="short_description"
                        type="text"
                        name="short_description"
                        value="{{ old('short_description', $product->short_description) }}"
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold text-gray-700 mb-1">Full Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        class="w-full p-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="flex items-center gap-6 pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500">
                        <span class="text-xs font-semibold text-gray-700">Feature on Homepage</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500">
                        <span class="text-xs font-semibold text-gray-700">Mark as New Arrival</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
            <button
                type="button"
                onclick="if(confirm('Are you sure you want to delete this product?')) { document.getElementById('delete-form').submit(); }"
                class="text-xs text-red-500 hover:text-red-700 font-semibold"
            >
                Delete Product
            </button>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="px-6 py-2.5 rounded-xl text-white font-semibold text-xs shadow-md transition-all hover:opacity-95"
                    style="background-color: #00A8B8;"
                >
                    Save Changes
                </button>
            </div>
        </div>
    </form>

    <form id="delete-form" method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
function loadCategoryAttributes(catId) {
    const list = document.getElementById('category-attributes-list');
    if (!catId) return;
    fetch(`/admin/products/category-attributes/${catId}`)
        .then(res => res.json())
        .then(attrs => {
            if (attrs && attrs.length > 0) {
                list.innerHTML = attrs.map(a => {
                    const vals = a.values ? a.values.map(v => v.value).join(', ') : '';
                    return `<span class="px-2.5 py-1 bg-white rounded-lg border border-blue-200 text-blue-900 font-semibold shadow-xs">
                        ${a.name} ${vals ? '<span class="text-gray-400 font-normal">(' + vals + ')</span>' : ''}
                    </span>`;
                }).join('');
            } else {
                list.innerHTML = '<span class="text-gray-400">No specific attributes linked to this category yet.</span>';
            }
        });
}
</script>
@endsection
