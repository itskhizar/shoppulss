@extends('layouts.admin')

@section('title', 'Add New Product')
@section('header', 'Create Product')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-gray-900" style="color: #0F1B4D;">Add New Product</h1>
            <p class="text-xs text-gray-500">Create a simple or variant catalog product</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900">
            ← Back to Products
        </a>
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

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 shadow-sm space-y-6">
        @csrf

        {{-- Basic Information --}}
        <div>
            <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Product Title *</label>
                    <input
                        id="name" type="text" name="name"
                        value="{{ old('name') }}" required
                        placeholder="e.g. Wireless ANC Earbuds Pro"
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >
                </div>

                <div>
                    <label for="sku" class="block text-xs font-semibold text-gray-700 mb-1">SKU / Identifier *</label>
                    <input
                        id="sku" type="text" name="sku"
                        value="{{ old('sku') }}" required
                        placeholder="e.g. SP-AUDIO-001"
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm font-mono focus:outline-none focus:border-teal-500"
                    >
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-semibold text-gray-700 mb-1">Category / Sub-category *</label>
                    <select
                        id="category_id" name="category_id" required
                        onchange="loadCategoryAttributes(this.value)"
                        class="w-full h-11 px-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                    >
                        <option value="">Select Category / Sub-category</option>
                        @foreach($categories as $parent)
                            <optgroup label="📁 {{ $parent->name }}">
                                <option value="{{ $parent->id }}" {{ old('category_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }} (Main Category)
                                </option>
                                @foreach($parent->children as $child)
                                    <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;↳ {{ $child->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-xs font-semibold text-gray-700 mb-1">Product Type *</label>
                    <select id="type" name="type" required class="w-full h-11 px-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500">
                        <option value="simple" {{ old('type', 'simple') === 'simple' ? 'selected' : '' }}>Simple Product</option>
                        <option value="variant" {{ old('type') === 'variant' ? 'selected' : '' }}>Variant Product (Multiple Options)</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-gray-700 mb-1">Publish Status *</label>
                    <select id="status" name="status" required class="w-full h-11 px-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500">
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published (Active)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Dynamic Category Attributes — EDITABLE --}}
        <div id="category-attributes-container" class="hidden">
            <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 pb-2 border-b border-gray-100">
                Available Attributes For This Category
                <span class="normal-case font-normal text-gray-400 ml-1">— click × to remove a value, or type to add new ones</span>
            </h2>
            <div id="category-attributes-list" class="space-y-4"></div>
        </div>

        {{-- Pricing & Stock --}}
        <div>
            <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Pricing & Inventory (PKR)</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="regular_price" class="block text-xs font-semibold text-gray-700 mb-1">Regular Price *</label>
                    <input id="regular_price" type="number" step="0.01" name="regular_price"
                        value="{{ old('regular_price') }}" required placeholder="e.g. 4500"
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500">
                </div>

                <div>
                    <label for="sale_price" class="block text-xs font-semibold text-gray-700 mb-1">Sale / Discount Price</label>
                    <input id="sale_price" type="number" step="0.01" name="sale_price"
                        value="{{ old('sale_price') }}" placeholder="Optional (e.g. 3800)"
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500">
                </div>

                <div>
                    <label for="stock_quantity" class="block text-xs font-semibold text-gray-700 mb-1">Initial Stock Count *</label>
                    <input id="stock_quantity" type="number" name="stock_quantity"
                        value="{{ old('stock_quantity', 25) }}" required
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500">
                </div>
            </div>
        </div>

        {{-- Product Media --}}
        <div>
            <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Product Media</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="images" class="block text-xs font-semibold text-gray-700 mb-1">Upload Product Images (Multiple)</label>
                    <input
                        id="images" type="file" name="images[]" multiple
                        accept="image/jpeg,image/png,image/jpg,image/webp,image/avif"
                        class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-200 rounded-lg p-1.5 focus:outline-none focus:border-teal-500"
                    >
                    <p class="text-[11px] text-gray-400 mt-1">JPEG, PNG, WebP, AVIF up to <strong>20MB</strong> each. First image is featured.</p>
                    {{-- Image previews --}}
                    <div id="image-preview-row" class="flex flex-wrap gap-2 mt-2"></div>
                </div>

                <div>
                    <label for="image_url" class="block text-xs font-semibold text-gray-700 mb-1">Or Primary Image URL (CDN / Web)</label>
                    <input
                        id="image_url" type="url" name="image_url"
                        value="{{ old('image_url') }}"
                        placeholder="https://images.unsplash.com/photo-..."
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm font-mono focus:outline-none focus:border-teal-500"
                    >
                    <p class="text-[11px] text-gray-400 mt-1">Optional: direct link if not uploading local files.</p>
                </div>
            </div>
        </div>

        {{-- Descriptions & Flags --}}
        <div>
            <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Descriptions & Flags</h2>
            <div class="space-y-4">
                <div>
                    <label for="short_description" class="block text-xs font-semibold text-gray-700 mb-1">Short Summary (1–2 sentences)</label>
                    <input id="short_description" type="text" name="short_description"
                        value="{{ old('short_description') }}"
                        placeholder="High performance product with manufacturer warranty"
                        class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500">
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold text-gray-700 mb-1">Full Product Description</label>
                    <textarea id="description" name="description" rows="5"
                        class="w-full p-3.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:border-teal-500"
                        placeholder="Detailed specifications, features, warranty, and box contents...">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-6 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500">
                        <span class="text-xs font-semibold text-gray-700">Feature on Homepage</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_new" value="1" {{ old('is_new', true) ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500">
                        <span class="text-xs font-semibold text-gray-700">Mark as New Arrival</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-white font-semibold text-xs shadow-md transition-all hover:opacity-95" style="background-color: #00A8B8;">
                Save & Publish Product
            </button>
        </div>
    </form>
</div>

<style>
.attr-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #fff;
    border: 1px solid #99d8df;
    border-radius: 6px;
    padding: 3px 8px;
    font-size: 11px;
    font-weight: 600;
    color: #0e7490;
    cursor: default;
}
.attr-chip .rm {
    cursor: pointer;
    color: #94a3b8;
    font-size: 13px;
    line-height: 1;
    margin-left: 2px;
}
.attr-chip .rm:hover { color: #ef4444; }
.chip-input {
    border: none;
    outline: none;
    font-size: 11px;
    min-width: 80px;
    background: transparent;
    color: #1e293b;
    padding: 2px 4px;
}
.attr-block {
    background: #f0fdfc;
    border: 1px solid #99f6e4;
    border-radius: 12px;
    padding: 12px 14px;
}
.attr-block-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #0f766e;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.chips-wrap {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    min-height: 36px;
    background: #fff;
    border: 1px solid #d1fae5;
    border-radius: 8px;
    padding: 6px 8px;
    cursor: text;
}
</style>

<script>
// Image preview
document.getElementById('images').addEventListener('change', function () {
    const preview = document.getElementById('image-preview-row');
    preview.innerHTML = '';
    Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-sm';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});

// Chip-based editable attribute system
function loadCategoryAttributes(catId) {
    const container = document.getElementById('category-attributes-container');
    const list = document.getElementById('category-attributes-list');
    if (!catId) { container.classList.add('hidden'); return; }

    fetch(`/admin/products/category-attributes/${catId}`)
        .then(res => res.json())
        .then(attrs => {
            list.innerHTML = '';
            if (!attrs || attrs.length === 0) {
                container.classList.add('hidden');
                return;
            }
            attrs.forEach(attr => {
                const values = attr.values ? attr.values.map(v => v.value) : [];
                list.appendChild(buildAttrBlock(attr.name, values));
            });
            container.classList.remove('hidden');
        })
        .catch(() => container.classList.add('hidden'));
}

function buildAttrBlock(attrName, values) {
    const wrap = document.createElement('div');
    wrap.className = 'attr-block';

    const label = document.createElement('div');
    label.className = 'attr-block-label';
    label.innerHTML = `<svg style="width:14px;height:14px;flex-shrink:0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>${attrName} <span style="font-weight:400;color:#64748b;font-size:10px;text-transform:none;letter-spacing:0">(click × to remove, press Enter or comma to add)</span>`;
    wrap.appendChild(label);

    const chipsWrap = document.createElement('div');
    chipsWrap.className = 'chips-wrap';
    chipsWrap.onclick = () => inputEl.focus();

    // Add existing chips
    values.forEach(val => addChip(chipsWrap, val));

    // Typing input
    const inputEl = document.createElement('input');
    inputEl.type = 'text';
    inputEl.className = 'chip-input';
    inputEl.placeholder = 'Add value…';
    inputEl.addEventListener('keydown', e => {
        if ((e.key === 'Enter' || e.key === ',') && inputEl.value.trim()) {
            e.preventDefault();
            addChip(chipsWrap, inputEl.value.trim());
            inputEl.value = '';
        } else if (e.key === 'Backspace' && !inputEl.value) {
            // Remove last chip
            const chips = chipsWrap.querySelectorAll('.attr-chip');
            if (chips.length) chips[chips.length - 1].remove();
        }
    });
    chipsWrap.appendChild(inputEl);
    wrap.appendChild(chipsWrap);
    return wrap;
}

function addChip(container, value) {
    const chip = document.createElement('span');
    chip.className = 'attr-chip';
    chip.dataset.value = value;
    chip.innerHTML = `${value}<span class="rm" title="Remove">×</span>`;
    chip.querySelector('.rm').onclick = e => { e.stopPropagation(); chip.remove(); };
    // Insert before the input
    const input = container.querySelector('.chip-input');
    container.insertBefore(chip, input);
}
</script>
@endsection
