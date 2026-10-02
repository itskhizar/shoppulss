@extends('layouts.app')

@section('title', $product->name . ' - ShopPulss')
@section('description', $product->short_description ?? substr(strip_tags($product->description), 0, 160))

@section('content')
<div class="bg-gray-50 py-4 border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        {{-- Breadcrumbs --}}
        <div class="flex items-center gap-2 text-xs text-gray-500 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-teal-600">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-teal-600">Shop</a>
            @foreach($breadcrumbs as $bc)
                <span>/</span>
                <a href="{{ route('categories.show', $bc->slug) }}" class="hover:text-teal-600">{{ $bc->name }}</a>
            @endforeach
            <span>/</span>
            <span class="text-gray-800 font-semibold truncate max-w-xs">{{ $product->name }}</span>
        </div>
    </div>
</div>

<div class="max-w-screen-xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">

            {{-- Left: Images Gallery --}}
            <div class="space-y-4">
                @php
                    $primaryImg = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                    $mainImgUrl = $primaryImg ? $primaryImg->image_url : 'https://placehold.co/600x600/f8fafc/0F1B4D?text='.urlencode($product->name);
                @endphp
                <div class="aspect-square w-full rounded-2xl bg-gray-50 border border-gray-100 overflow-hidden flex items-center justify-center p-4 relative group">
                    <img
                        id="main-product-image"
                        src="{{ $mainImgUrl }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105"
                    >
                    @if($product->sale_price && $product->sale_price < $product->regular_price)
                        @php
                            $discount = round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100);
                        @endphp
                        <span class="absolute top-4 left-4 text-xs font-black text-white px-2.5 py-1 rounded-md" style="background-color: #FF6B35;">
                            -{{ $discount }}% OFF
                        </span>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if($product->images->count() > 1)
                    <div class="flex items-center gap-3 overflow-x-auto pb-2">
                        @foreach($product->images as $img)
                            <button
                                type="button"
                                onclick="document.getElementById('main-product-image').src='{{ $img->image_url }}'"
                                class="w-16 h-16 rounded-xl border border-gray-200 overflow-hidden flex-shrink-0 hover:border-teal-500 focus:outline-none transition-colors p-1"
                            >
                                <img src="{{ $img->image_url }}" alt="" class="w-full h-full object-contain">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Right: Product Details & Add to Cart --}}
            <div class="space-y-6">
                <div>
                    @if($product->category)
                        <div class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-1">
                            {{ $product->category->name }}
                        </div>
                    @endif
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight" style="color: #0F1B4D;">
                        {{ $product->name }}
                    </h1>

                    {{-- Ratings & SKU --}}
                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-500 flex-wrap">
                        <div class="flex items-center gap-1 text-amber-500">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= ($product->rating_cache ?? 5) ? 'fill-amber-400' : 'fill-gray-200' }}" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            <span class="font-bold text-gray-800 ml-1">{{ number_format($product->rating_cache ?? 5.0, 1) }}</span>
                            <span class="text-gray-400">({{ $product->rating_count ?? 12 }} reviews)</span>
                        </div>
                        <span>•</span>
                        <div>SKU: <span class="font-semibold text-gray-700">{{ $product->sku ?? ('SKU-' . $product->id) }}</span></div>
                        <span>•</span>
                        <div>
                            @if($product->stock_quantity > 0)
                                <span class="inline-flex items-center gap-1 font-semibold text-emerald-600">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    In Stock ({{ $product->stock_quantity }} available)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 font-semibold text-red-500">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    Out of Stock
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Price Block --}}
                <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-500">Special Price</div>
                        <div class="flex items-baseline gap-3 mt-1">
                            <span id="display-product-price" class="text-3xl font-black" style="color: #0F1B4D;">
                                Rs. {{ number_format($product->sale_price ?? $product->regular_price) }}
                            </span>
                            <span id="display-regular-price" class="text-base text-gray-400 line-through {{ ($product->sale_price && $product->sale_price < $product->regular_price) ? '' : 'hidden' }}">
                                Rs. {{ number_format($product->regular_price) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right text-xs text-emerald-700 font-semibold bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                        ✓ All Taxes Included
                    </div>
                </div>

                {{-- Short Description --}}
                @if($product->short_description)
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $product->short_description }}
                    </p>
                @endif

                {{-- Purchase Form --}}
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-4 pt-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Variant Selector if multiple variants exist --}}
                    @if($product->variants && $product->variants->count() > 1)
                        <div>
                            <label for="variant_id" class="block text-xs font-semibold text-gray-700 mb-1.5">Select Variant / Option:</label>
                            <select
                                id="variant_id"
                                name="variant_id"
                                class="w-full h-11 px-3.5 rounded-lg border border-gray-200 text-xs font-medium focus:outline-none focus:border-teal-500 bg-white"
                                onchange="onVariantChange(this)"
                            >
                                @foreach($product->variants as $variant)
                                    <option
                                        value="{{ $variant->id }}"
                                        data-price="{{ $variant->sale_price ?? $variant->price }}"
                                        data-regular="{{ $variant->price }}"
                                        data-sale="{{ $variant->sale_price }}"
                                        data-stock="{{ $variant->stock_quantity }}"
                                        data-sku="{{ $variant->sku }}"
                                    >
                                        {{ $variant->name }} — Rs. {{ number_format($variant->sale_price ?? $variant->price) }}
                                        ({{ $variant->stock_quantity > 0 ? $variant->stock_quantity . ' in stock' : 'Out of stock' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="flex items-center gap-4">
                        <div class="w-32">
                            <label for="quantity" class="block text-xs font-semibold text-gray-700 mb-1">Quantity</label>
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden h-11">
                                <button type="button" onclick="changeQty(-1)" class="w-9 h-full flex items-center justify-center text-gray-500 hover:bg-gray-100 text-base font-bold select-none">-</button>
                                <input
                                    id="quantity"
                                    type="number"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="{{ max(1, (int) $product->stock_quantity) }}"
                                    class="w-full h-full text-center text-sm font-semibold border-x border-gray-200 focus:outline-none"
                                >
                                <button type="button" onclick="changeQty(1)" class="w-9 h-full flex items-center justify-center text-gray-500 hover:bg-gray-100 text-base font-bold select-none">+</button>
                            </div>
                        </div>

                        <div class="flex-1 pt-5">
                            <button
                                type="submit"
                                {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}
                                class="w-full h-11 rounded-lg text-white font-bold text-sm shadow-md transition-all flex items-center justify-center gap-2 {{ $product->stock_quantity <= 0 ? 'bg-gray-400 cursor-not-allowed' : 'hover:opacity-95' }}"
                                style="{{ $product->stock_quantity > 0 ? 'background-color: #00A8B8;' : '' }}"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Trust Badges Box --}}
                <div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-100 text-xs text-gray-600">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                        <span>100% Authentic Guaranteed</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                        </div>
                        <span>Cash on Delivery (COD)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H11a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h4l2 4v4h-6V7z"/></svg>
                        </div>
                        <span>Free Shipping Over Rs 2,500</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>
                        </div>
                        <span>7-Day Return Policy</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Description & Full Details Tab --}}
        <div class="mt-12 pt-8 border-t border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 mb-4" style="color: #0F1B4D;">Product Description & Details</h2>
            <div class="prose max-w-none text-sm text-gray-700 leading-relaxed">
                {!! nl2br(e($product->description ?? $product->short_description ?? 'Authentic product verified and fulfilled directly by ShopPulss.')) !!}
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->isNotEmpty())
        <div class="mt-12">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-black text-gray-900" style="color: #0F1B4D;">Related Products</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Customers also viewed these items in {{ $product->category?->name }}</p>
                </div>
                <a href="{{ route('categories.show', $product->category->slug) }}" class="text-xs font-bold text-teal-600 hover:text-teal-700">View Category →</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($relatedProducts as $rel)
                    <x-product-card :product="$rel" />
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
// Base price for the currently shown product (or selected variant)
let _unitPrice = {{ $product->sale_price ?? $product->regular_price }};
let _maxStock  = {{ max(1, (int) $product->stock_quantity) }};

function updateTotalDisplay() {
    const qty = parseInt(document.getElementById('quantity')?.value || '1', 10);
    const totalEl = document.getElementById('display-product-price');
    if (totalEl) {
        totalEl.textContent = 'Rs. ' + (_unitPrice * qty).toLocaleString();
    }
    // show per-unit under total when qty > 1
    let unitNote = document.getElementById('unit-price-note');
    if (qty > 1) {
        if (!unitNote) {
            unitNote = document.createElement('div');
            unitNote.id = 'unit-price-note';
            unitNote.className = 'text-xs text-gray-400 mt-0.5';
            totalEl?.parentElement?.appendChild(unitNote);
        }
        unitNote.textContent = 'Rs. ' + _unitPrice.toLocaleString() + ' × ' + qty;
    } else if (unitNote) {
        unitNote.textContent = '';
    }
}

function changeQty(delta) {
    const q = document.getElementById('quantity');
    if (!q) { return; }
    const max = parseInt(q.getAttribute('max') || String(_maxStock), 10);
    const min = parseInt(q.getAttribute('min') || '1', 10);
    let val = parseInt(q.value, 10) || 1;
    val += delta;
    if (val < min) { val = min; }
    if (val > max) { val = max; }
    q.value = val;
    updateTotalDisplay();
}

function onVariantChange(select) {
    const opt = select.options[select.selectedIndex];
    if (!opt) { return; }

    const price   = parseFloat(opt.getAttribute('data-price') || '0');
    const regular = parseFloat(opt.getAttribute('data-regular') || '0');
    const sale    = parseFloat(opt.getAttribute('data-sale') || '0');
    const stock   = parseInt(opt.getAttribute('data-stock') || '0', 10);

    _unitPrice = price || regular;
    _maxStock  = Math.max(1, stock);

    const regEl = document.getElementById('display-regular-price');
    if (regEl) {
        if (sale > 0 && sale < regular) {
            regEl.textContent = 'Rs. ' + regular.toLocaleString();
            regEl.classList.remove('hidden');
        } else {
            regEl.classList.add('hidden');
        }
    }

    const qty = document.getElementById('quantity');
    if (qty) {
        qty.setAttribute('max', _maxStock);
        if (parseInt(qty.value, 10) > stock) {
            qty.value = Math.max(1, stock);
        }
    }

    updateTotalDisplay();

    // Update add-to-cart button disabled state
    const btn = document.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = stock <= 0;
        if (stock <= 0) {
            btn.textContent = 'Out of Stock';
            btn.className = 'w-full h-11 rounded-lg text-white font-bold text-sm transition-all flex items-center justify-center gap-2 bg-gray-400 cursor-not-allowed';
            btn.style.backgroundColor = '';
        } else {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg> Add to Cart`;
            btn.className = 'w-full h-11 rounded-lg text-white font-bold text-sm shadow-md transition-all flex items-center justify-center gap-2 hover:opacity-95';
            btn.style.backgroundColor = '#00A8B8';
        }
    }
}

// Wire up qty input directly
document.addEventListener('DOMContentLoaded', function () {
    const qtyInput = document.getElementById('quantity');
    if (qtyInput) {
        qtyInput.addEventListener('input', updateTotalDisplay);
        qtyInput.addEventListener('change', updateTotalDisplay);
    }
    updateTotalDisplay();
});
</script>
@endsection
