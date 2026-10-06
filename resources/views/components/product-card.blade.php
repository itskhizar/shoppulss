@props(['product', 'badge' => null])

@php
    $effectivePrice = (float) $product->effective_price;
    $regularPrice = (float) $product->regular_price;
    $hasSale = $effectivePrice > 0 && $effectivePrice < $regularPrice;
    $discount = $product->discount_percentage > 0 
        ? $product->discount_percentage 
        : ($hasSale && $regularPrice > 0 ? (int) round((($regularPrice - $effectivePrice) / $regularPrice) * 100) : 0);
    
    $productUrl = route('products.show', $product->slug ?? $product->id);
    $inStock = $product->stock_quantity > 0;
    
    // Rating
    $rating = 4.8;
    $reviewCount = 32;

    // Image URL with fallback
    $imageUrl = $product->primary_image_url ?? $product->images->first()?->image_url;
@endphp

<article class="product-card bg-white rounded-2xl border border-pulse-border p-3.5 sm:p-4 flex flex-col justify-between shadow-subtle hover:shadow-card group" id="product-card-{{ $product->id }}">
    <div>
        {{-- Image Container --}}
        <div class="relative rounded-xl overflow-hidden bg-slate-50 aspect-square flex items-center justify-center mb-3">
            {{-- Discount / New / Deal Badge --}}
            @if($product->hasActiveDeal())
                <span class="absolute top-2 left-2 z-10 bg-pulse-orange text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-xs animate-pulse">DEAL -{{ $discount }}%</span>
            @elseif($badge === 'New' || $product->is_new)
                <span class="absolute top-2 left-2 z-10 bg-pulse-teal text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-xs">New</span>
            @elseif($hasSale && $discount > 0)
                <span class="absolute top-2 left-2 z-10 bg-pulse-orange text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-xs">-{{ $discount }}%</span>
            @elseif($badge)
                <span class="absolute top-2 left-2 z-10 bg-pulse-navy text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-xs">{{ $badge }}</span>
            @endif

            {{-- Wishlist Heart Button --}}
            <button
                type="button"
                data-wishlist-id="{{ $product->id }}"
                onclick="toggleWishlist({{ $product->id }}, this)"
                class="absolute top-2 right-2 z-10 text-slate-400 hover:text-rose-500 bg-white/90 backdrop-blur-xs p-1.5 rounded-full text-xs shadow-sm transition-colors hover:scale-110"
                title="Wishlist"
                aria-label="Wishlist"
            >
                <i class="fa-regular fa-heart"></i>
            </button>

            {{-- Clickable Image --}}
            <a href="{{ $productUrl }}" class="w-full h-full flex items-center justify-center p-3">
                @if($imageUrl && !str_contains($imageUrl, 'placeholder'))
                    <img
                        src="{{ $imageUrl }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-300"
                        loading="lazy"
                    >
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 group-hover:text-pulse-orange transition-colors">
                        <i class="fa-solid fa-box-open text-4xl mb-1"></i>
                        <span class="text-[10px] font-semibold text-slate-400">Direct Retail</span>
                    </div>
                @endif
            </a>
        </div>

        {{-- Category & Direct Tag --}}
        <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1">
            <span class="truncate max-w-[130px] font-medium">{{ $product->category?->name ?? 'Direct Retail' }}</span>
            <span class="text-pulse-teal font-semibold flex items-center space-x-0.5 shrink-0">
                <i class="fa-solid fa-check text-[9px]"></i>
                <span>Direct</span>
            </span>
        </div>

        {{-- Product Title --}}
        <h3 class="text-xs sm:text-sm font-bold text-pulse-navy line-clamp-1 group-hover:text-pulse-orange transition-colors">
            <a href="{{ $productUrl }}">{{ $product->name }}</a>
        </h3>

        {{-- Rating --}}
        <div class="flex items-center space-x-1 text-amber-400 text-[11px] mt-1">
            <i class="fa-solid fa-star"></i>
            <span class="font-bold text-slate-700 ml-0.5">{{ number_format((float) $rating, 1) }}</span>
            <span class="text-slate-400">({{ $reviewCount }})</span>
        </div>
    </div>

    {{-- Bottom Price & Add To Cart --}}
    <div class="mt-4 pt-3 border-t border-slate-100">
        <div class="flex items-baseline space-x-2 mb-2.5">
            <span class="text-base sm:text-lg font-black text-pulse-navy">
                Rs. {{ number_format($effectivePrice) }}
            </span>
            @if($hasSale)
                <span class="text-xs text-slate-400 line-through">
                    Rs. {{ number_format($regularPrice) }}
                </span>
            @endif
        </div>

        @if($inStock)
            <form action="{{ route('cart.add') }}" method="POST" class="ajax-add-to-cart w-full">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button
                    type="submit"
                    class="w-full bg-slate-100 hover:bg-pulse-orange hover:text-white text-slate-700 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center space-x-1.5 active:scale-98 shadow-2xs"
                >
                    <i class="fa-solid fa-bag-shopping text-xs"></i>
                    <span>Add to Cart</span>
                </button>
            </form>
        @else
            <button
                type="button"
                disabled
                class="w-full bg-slate-100 text-slate-400 py-2 rounded-xl text-xs font-bold cursor-not-allowed flex items-center justify-center space-x-1.5"
            >
                <i class="fa-solid fa-ban text-xs"></i>
                <span>Out of Stock</span>
            </button>
        @endif
    </div>
</article>
