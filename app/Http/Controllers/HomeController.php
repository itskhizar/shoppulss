<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index(): View
    {
        $featuredCategories = Category::active()
            ->featured()
            ->parents()
            ->orderBy('display_order')
            ->limit(6)
            ->get();

        $trendingProducts = Product::published()
            ->with(['images', 'category'])
            ->inStock()
            ->where(function ($q) {
                $q->where('is_featured', true)->orWhere('sale_price', '>', 0);
            })
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $newArrivals = Product::published()
            ->with(['images', 'category'])
            ->inStock()
            ->new()
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $deals = Product::published()
            ->with(['images', 'category'])
            ->onSale()
            ->inStock()
            ->orderByRaw('(regular_price - sale_price) DESC')
            ->limit(4)
            ->get();

        $heroProducts = Product::published()
            ->with(['images'])
            ->featured()
            ->inStock()
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $announcementText = Setting::get('announcement_text', '🚀 Free delivery over Rs 2,500 | 💰 COD available nationwide');
        $whatsappNumber = Setting::get('whatsapp_helpline', '+923328912706');

        return view('pages.home', compact(
            'featuredCategories',
            'trendingProducts',
            'newArrivals',
            'deals',
            'heroProducts',
            'announcementText',
            'whatsappNumber'
        ));
    }
}
