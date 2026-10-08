<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\Seo\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Show products in a category.
     */
    public function show(Request $request, string $slug, SeoService $seo): View
    {
        $category = Category::where('slug', $slug)
            ->with(['children', 'parent'])
            ->firstOrFail();

        $seo->forCategory($category, (int) $request->get('page', 1));

        // Get category IDs including children
        $categoryIds = $category->children->pluck('id')->prepend($category->id)->all();

        $query = Product::published()
            ->inStock()
            ->with(['images', 'category'])
            ->whereIn('category_id', $categoryIds);

        // Sorting
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, regular_price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, regular_price) DESC'),
            'popular' => $query->orderByDesc('is_featured')->orderByDesc('created_at'),
            default => $query->orderByDesc('created_at'),
        };

        $products = $query->paginate(16)->withQueryString();

        $allCategories = Category::active()->parents()->with('children')->get();

        return view('categories.show', compact('category', 'products', 'allCategories', 'sort'));
    }
}
