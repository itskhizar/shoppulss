{{--
    Product Card Component
    Props:
      $product  – App\Models\Product (with images, category loaded)
      $badge    – string, e.g. 'Hot Deal', 'New', 'Sale'
--}}
@php
$image     = $product->images->first();
$hasSale   = $product->sale_price && $product->sale_price < $product->regular_price;
$discount  = $hasSale
    ? (int) round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100)
    : 0;
$rating    = $product->rating_cache ?? 4.8;
$ratingCnt = $product->rating_count ?? 36;
$productUrl = route('products.show', $product->slug ?? $product->id);
@endphp

<div class="bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group flex flex-col" id="product-card-{{ $product->id }}">

    {{-- Image Area --}}
    <a href="{{ $productUrl }}" class="relative aspect-square bg-gray-50 overflow-hidden flex-shrink-0 block">

        {{-- Badges --}}
        @if($hasSale && isset($badge))
        <span class="absolute top-2 left-2 z-10 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-white text-xs font-bold" style="background-color: #FF6B35;">
            {{ $badge }} {{ $discount > 0 ? $discount.'%' : '' }}
        </span>
        @elseif(isset($badge))
        <span class="absolute top-2 left-2 z-10 px-2 py-0.5 rounded-full text-white text-xs font-bold" style="background-color: #00A8B8;">
            {{ $badge }}
        </span>
        @endif

        {{-- Product Image --}}
        @if($image)
        <img
            src="{{ $image->image_url }}"
            alt="{{ $image->alt_text ?? $product->name }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            loading="lazy">
        @else
        <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #e8ecf0 0%, #f5f7fa 100%);">
            <svg class="w-16 h-16 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        @endif
    </a>

    {{-- Details --}}
    <div class="p-3 flex flex-col flex-1">

        {{-- Category --}}
        <div class="flex items-center gap-1 mb-1">
            <span class="text-xs text-teal-600 font-medium">{{ $product->category?->name ?? 'ShopPulss' }}</span>
        </div>

        {{-- Product Name --}}
        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 leading-snug mb-2 flex-1">
            <a href="{{ $productUrl }}" class="hover:text-teal-600 transition-colors">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Star Rating --}}
        <div class="flex items-center gap-1 mb-2">
            <div class="flex items-center gap-0.5">
                @for($i = 1; $i <= 5; $i++)
                <svg class="w-3 h-3 {{ $i <= floor($rating) ? '' : 'opacity-30' }}" style="color: #f59e0b;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                @endfor
            </div>
            <span class="text-xs text-gray-400">({{ $ratingCnt }})</span>

            {{-- Authenticity badge --}}
            <span class="ml-auto text-xs font-medium flex items-center gap-0.5" style="color: #00A8B8;">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Direct
            </span>
        </div>

        {{-- Price --}}
        <div class="flex items-baseline gap-2 mb-3">
            <span class="text-base font-black" style="color: #0F1B4D;">
                Rs. {{ number_format($hasSale ? $product->sale_price : $product->regular_price, 0) }}
            </span>
            @if($hasSale)
            <span class="text-xs text-gray-400 line-through">Rs. {{ number_format($product->regular_price, 0) }}</span>
            @if($discount >= 5)
            <span class="text-xs font-bold" style="color: #FF6B35;">-{{ $discount }}%</span>
            @endif
            @endif
        </div>

        {{-- Add to Cart Form --}}
        <form action="{{ route('cart.add') }}" method="POST" class="w-full">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button
                type="submit"
                class="w-full py-2.5 rounded-xl text-sm font-semibold text-white flex items-center justify-center gap-2 transition-all hover:opacity-90 active:scale-95 shadow-sm"
                style="background-color: #00A8B8;"
                id="add-to-cart-{{ $product->id }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Add to Cart
            </button>
        </form>
    </div>
</div>
