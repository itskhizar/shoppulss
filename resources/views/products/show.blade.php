@extends('layouts.app')

@section('title', $product->name . ' - ShopPulss')
@section('description', $product->short_description ?? substr(strip_tags($product->description), 0, 160))

@section('content')
<div class="bg-white border-b border-[#E6E8F2] py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        {{-- Breadcrumbs --}}
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}">Shop</a>
            @foreach($breadcrumbs as $bc)
                <span>/</span>
                <a href="{{ route('categories.show', $bc->slug) }}">{{ $bc->name }}</a>
            @endforeach
            <span>/</span>
            <span class="current truncate max-w-xs">{{ $product->name }}</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 md:py-10">
    <div class="bg-white rounded-3xl border border-[#E6E8F2] p-6 md:p-10 shadow-sp-card">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14">

            {{-- Left: Images Gallery --}}
            <div class="space-y-4">
                @php
                    $primaryImg = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                    $mainImgUrl = $primaryImg ? $primaryImg->image_url : 'https://placehold.co/600x600/f8fafc/0F1654?text='.urlencode($product->name);
                @endphp
                <div class="aspect-square w-full rounded-3xl bg-[#F8FAFC] border border-[#E6E8F2] overflow-hidden flex items-center justify-center p-6 relative group">
                    <img
                        id="main-product-image"
                        src="{{ $mainImgUrl }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-contain mix-blend-multiply transition-transform duration-500 group-hover:scale-105"
                    >
                    @if($product->sale_price && $product->sale_price < $product->regular_price)
                        @php
                            $discount = round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100);
                        @endphp
                        <span class="badge-orange absolute top-5 left-5 text-xs py-1 px-3 shadow-md">
                            -{{ $discount }}% OFF
                        </span>
                    @endif
                    <span class="badge-teal absolute top-5 right-5 text-xs">
                        Direct Retail
                    </span>
                </div>

                {{-- Thumbnails --}}
                @if($product->images->count() > 1)
                    <div class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-none pt-1">
                        @foreach($product->images as $img)
                            <button
                                type="button"
                                onclick="setProductMainImage('{{ $img->image_url }}', this)"
                                class="product-thumb-btn w-16 h-16 sm:w-20 sm:h-20 rounded-2xl border-2 {{ $loop->first ? 'border-pulse-orange shadow-xs' : 'border-slate-200' }} bg-[#F8FAFC] overflow-hidden shrink-0 hover:border-pulse-orange transition-all p-1.5 focus:outline-none"
                            >
                                <img src="{{ $img->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain mix-blend-multiply">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Right: Product Details & Add to Cart --}}
            <div class="space-y-6">
                <div>
                    @if($product->category)
                        <div class="section-eyebrow mb-1">
                            <a href="{{ route('categories.show', $product->category->slug) }}" class="hover:underline">
                                {{ $product->category->name }}
                            </a>
                        </div>
                    @endif
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-[#0F1654] leading-tight">
                        {{ $product->name }}
                    </h1>

                    {{-- Ratings, SKU & Stock --}}
                    <div class="flex items-center gap-4 mt-3 text-xs text-gray-500 flex-wrap">
                        <div class="flex items-center gap-1.5 text-amber-500">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= ($product->rating_cache ?? 5) ? 'fill-amber-400' : 'fill-gray-200' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="font-black text-[#0F1654] ml-1">{{ number_format($product->rating_cache ?? 5.0, 1) }}</span>
                            <span class="text-gray-400">({{ $product->rating_count ?? 12 }} reviews)</span>
                        </div>
                        <span class="text-gray-300">•</span>
                        <div>SKU: <span class="font-mono font-bold text-gray-700">{{ $product->sku ?? ('SKU-' . $product->id) }}</span></div>
                        <span class="text-gray-300">•</span>
                        <div>
                            @if($product->stock_quantity > 0)
                                <span class="inline-flex items-center gap-1.5 font-bold text-emerald-600">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    In Stock ({{ $product->stock_quantity }} available)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 font-bold text-red-500">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                    Out of Stock
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Price Block --}}
                <div class="p-5 rounded-2xl bg-[#FFF1EA] border border-[#FF5A1F]/20 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-[#FF5A1F] uppercase tracking-wider">Direct Retail Price</div>
                        <div class="flex items-baseline gap-3 mt-1">
                            <span id="display-product-price" class="text-3xl sm:text-4xl font-black text-[#0F1654]">
                                Rs. {{ number_format($product->effective_price) }}
                            </span>
                            <span id="display-regular-price" class="text-base text-gray-400 line-through {{ ($product->effective_price < $product->regular_price) ? '' : 'hidden' }}">
                                Rs. {{ number_format($product->regular_price) }}
                            </span>
                            @if($product->hasActiveDeal())
                                <span class="bg-[#FF5A1F] text-white text-[11px] font-black px-2 py-0.5 rounded-md shadow-xs animate-pulse">
                                    FLASH DEAL
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="badge-teal text-[11px] py-1 px-2.5">
                            ✓ Genuine Stock
                        </span>
                        <div class="text-[10px] text-gray-500 mt-1 font-medium">All Taxes Included</div>
                    </div>
                </div>

                {{-- Short Description --}}
                @if($product->short_description)
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $product->short_description }}
                    </p>
                @endif

                {{-- Purchase Form --}}
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-4 pt-1">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Variant Selector if multiple variants exist --}}
                    @if($product->variants && $product->variants->count() > 1)
                        <div>
                            <label for="variant_id" class="sp-label">Select Variant / Option:</label>
                            <select
                                id="variant_id"
                                name="variant_id"
                                class="sp-input"
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
                            <label for="quantity" class="sp-label">Quantity</label>
                            <div class="flex items-center border border-[#E6E8F2] rounded-full bg-[#F6F7FB] overflow-hidden h-12 px-1">
                                <button type="button" onclick="changeQty(-1)" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-600 hover:bg-white hover:text-[#FF5A1F] text-base font-black transition-colors select-none" aria-label="Decrease quantity">-</button>
                                <input
                                    id="quantity"
                                    type="number"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="{{ max(1, (int) $product->stock_quantity) }}"
                                    class="w-full text-center text-sm font-black text-[#0F1654] bg-transparent border-0 focus:outline-none"
                                >
                                <button type="button" onclick="changeQty(1)" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-600 hover:bg-white hover:text-[#FF5A1F] text-base font-black transition-colors select-none" aria-label="Increase quantity">+</button>
                            </div>
                        </div>

                        <div class="flex-1 pt-6">
                            <button
                                type="submit"
                                {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}
                                class="btn-primary w-full py-3.5 text-sm {{ $product->stock_quantity <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                id="add-to-cart-btn"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>{{ $product->stock_quantity > 0 ? 'Add to Shopping Cart' : 'Out of Stock' }}</span>
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Trust Badges Box --}}
                <div class="grid grid-cols-2 gap-3 pt-6 border-t border-[#E6E8F2] text-xs text-gray-700">
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-[#F6F7FB] border border-[#E6E8F2]">
                        <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-[#0AA6B7] shadow-xs flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="font-bold text-[#0F1654]">100% Brand Authentic</span>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-[#F6F7FB] border border-[#E6E8F2]">
                        <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-[#FF5A1F] shadow-xs flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="font-bold text-[#0F1654]">Cash on Delivery (COD)</span>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-[#F6F7FB] border border-[#E6E8F2]">
                        <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-[#0AA6B7] shadow-xs flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H11a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h4l2 4v4h-6V7z"/></svg>
                        </div>
                        <span class="font-bold text-[#0F1654]">Nationwide Express Delivery</span>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-[#F6F7FB] border border-[#E6E8F2]">
                        <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-[#FF5A1F] shadow-xs flex-shrink-0">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="font-bold text-[#0F1654]">7-Day Easy Returns</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Description & Full Details Tab --}}
        <div class="mt-12 pt-8 border-t border-[#E6E8F2]">
            <h2 class="text-xl font-black text-[#0F1654] mb-4">Product Description & Direct Authenticity</h2>
            <div class="prose max-w-none text-sm text-gray-700 leading-relaxed">
                {!! nl2br(e($product->description ?? $product->short_description ?? 'Authentic product inspected, authenticated, and fulfilled directly by ShopPulss.')) !!}
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->isNotEmpty())
        <div class="mt-14">
            <div class="section-header">
                <div>
                    <div class="section-eyebrow">Explore More</div>
                    <h2 class="section-title">Related Products</h2>
                    <p class="text-xs text-gray-500 mt-1">Customers also viewed these items in {{ $product->category?->name }}</p>
                </div>
                @if($product->category)
                    <a href="{{ route('categories.show', $product->category->slug) }}" class="view-all-link">
                        <span>View Category</span>
                        <span>→</span>
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($relatedProducts as $rel)
                    <x-product-card :product="$rel" />
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
// Thumbnail image switcher
function setProductMainImage(url, btn) {
    const mainImg = document.getElementById('main-product-image');
    if (mainImg) {
        mainImg.style.opacity = '0.3';
        setTimeout(() => {
            mainImg.src = url;
            mainImg.style.opacity = '1';
        }, 120);
    }
    document.querySelectorAll('.product-thumb-btn').forEach(b => {
        b.classList.remove('border-pulse-orange', 'shadow-xs');
        b.classList.add('border-slate-200');
    });
    if (btn) {
        btn.classList.add('border-pulse-orange', 'shadow-xs');
        btn.classList.remove('border-slate-200');
    }
}

// Base price for the currently shown product (or selected variant)
let _unitPrice = {{ $product->sale_price ?? $product->regular_price }};
let _maxStock  = {{ max(1, (int) $product->stock_quantity) }};

function updateTotalDisplay() {
    const qty = parseInt(document.getElementById('quantity')?.value || '1', 10);
    const totalEl = document.getElementById('display-product-price');
    if (totalEl) {
        totalEl.textContent = 'Rs. ' + (_unitPrice * qty).toLocaleString();
    }
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
    const btn = document.getElementById('add-to-cart-btn');
    if (btn) {
        btn.disabled = stock <= 0;
        if (stock <= 0) {
            btn.innerHTML = `<span>Out of Stock</span>`;
            btn.className = 'btn-primary w-full py-3.5 text-sm opacity-50 cursor-not-allowed';
        } else {
            btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg> <span>Add to Shopping Cart</span>`;
            btn.className = 'btn-primary w-full py-3.5 text-sm';
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
