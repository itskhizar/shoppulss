{{--
    ShopPulss Master Product Card Component
    Props:
      $product  – App\Models\Product (with images, category loaded)
      $badge    – string, e.g. 'Hot Deal', 'New', 'Sale', 'Trending'
--}}
@php
    $image = $product->images->first();
    $hasSale = $product->sale_price && $product->sale_price < $product->regular_price;
    $discount = $hasSale
        ? (int) round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100)
        : 0;
    $rating = $product->rating_cache ?? 4.8;
    $ratingCount = $product->rating_count ?? 32;
    $productUrl = route('products.show', $product->slug ?? $product->id);
    $inStock = $product->stock_quantity > 0;
@endphp

<div class="bg-white rounded-2xl md:rounded-[22px] border border-[#E6E8F2] overflow-hidden flex flex-col justify-between group transition-all duration-300 hover:-translate-y-1.5 hover:shadow-sp-card-hover relative" id="product-card-{{ $product->id }}">

    {{-- Top Badges & Image Container --}}
    <div class="relative aspect-square bg-[#F8FAFC] overflow-hidden">

        {{-- Left Badge: Sale / Custom badge --}}
        @if($hasSale && $discount > 0)
            <span class="absolute top-3 left-3 z-10 inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black text-white shadow-sm bg-gradient-to-r from-[#FF6B1F] to-[#FF4A0A]">
                -{{ $discount }}%
            </span>
        @elseif(isset($badge))
            <span class="absolute top-3 left-3 z-10 inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black text-white shadow-sm {{ $badge === 'New' ? 'bg-[#0AA6B7]' : 'bg-[#0F1654]' }}">
                {{ $badge }}
            </span>
        @endif

        {{-- Right Wishlist Quick Button --}}
        <button
            type="button"
            onclick="this.classList.toggle('text-red-500'); this.classList.toggle('text-gray-400');"
            class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/90 backdrop-blur-xs flex items-center justify-center text-gray-400 hover:text-red-500 hover:scale-110 shadow-xs transition-all"
            title="Add to Wishlist"
            aria-label="Wishlist"
        >
            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
            </svg>
        </button>

        {{-- Clickable Product Image --}}
        <a href="{{ $productUrl }}" class="w-full h-full block p-3">
            @if($image && $image->image_url)
                <img
                    src="{{ $image->image_url }}"
                    alt="{{ $image->alt_text ?? $product->name }}"
                    class="w-full h-full object-contain mix-blend-multiply group-hover:scale-108 transition-transform duration-500"
                    loading="lazy"
                >
            @else
                <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 text-gray-300">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-[10px] font-medium mt-1">ShopPulss Direct</span>
                </div>
            @endif
        </a>
    </div>

    {{-- Product Body --}}
    <div class="p-4 sm:p-4.5 flex flex-col flex-1 justify-between bg-white">
        <div>
            {{-- Category & Direct Tag --}}
            <div class="flex items-center justify-between gap-1 text-[11px] mb-1.5">
                <span class="text-gray-400 font-medium truncate">
                    {{ $product->category?->name ?? 'Essentials' }}
                </span>
                <span class="inline-flex items-center gap-1 font-bold text-[#0AA6B7] bg-[#0AA6B7]/10 px-2 py-0.2 rounded-full text-[10px]">
                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Direct
                </span>
            </div>

            {{-- Product Title --}}
            <h3 class="text-xs sm:text-sm font-bold text-[#161616] leading-snug line-clamp-2 mb-2 group-hover:text-[#FF5A1F] transition-colors">
                <a href="{{ $productUrl }}">
                    {{ $product->name }}
                </a>
            </h3>

            {{-- Star Rating --}}
            <div class="flex items-center gap-1 mb-2.5">
                <div class="flex items-center text-amber-400 text-xs">
                    ★
                </div>
                <span class="text-xs font-bold text-gray-700">{{ number_format($rating, 1) }}</span>
                <span class="text-[11px] text-gray-400">({{ $ratingCount }})</span>
            </div>
        </div>

        <div>
            {{-- Price Row --}}
            <div class="flex items-baseline gap-2 mb-3">
                <span class="text-base sm:text-lg font-black text-[#0F1654]">
                    Rs. {{ number_format($hasSale ? $product->sale_price : $product->regular_price) }}
                </span>
                @if($hasSale)
                    <span class="text-xs text-gray-400 line-through">
                        Rs. {{ number_format($product->regular_price) }}
                    </span>
                @endif
            </div>

            {{-- Add to Cart Form --}}
            <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">

                @if($inStock)
                    <button
                        type="submit"
                        id="add-to-cart-{{ $product->id }}"
                        class="w-full py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-2 bg-gray-100 text-gray-700 group-hover:bg-[#FF5A1F] group-hover:text-white active:scale-97 shadow-xs hover:shadow-md transition-all duration-200"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Add to Cart</span>
                    </button>
                @else
                    <button
                        type="button"
                        disabled
                        class="w-full py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold text-gray-400 bg-gray-100 cursor-not-allowed flex items-center justify-center gap-1.5"
                    >
                        <span>Out of Stock</span>
                    </button>
                @endif
            </form>
        </div>
    </div>
</div>
