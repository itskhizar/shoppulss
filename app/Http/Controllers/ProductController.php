<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\Seo\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Product catalog & search with filtering and sorting.
     */
    public function index(Request $request, SeoService $seo): View
    {
        // Search keyword
        $search = $request->get('q');
        if ($search) {
            $seo->forSearch($search);
        } else {
            $seo->setTitle('Online Shopping in Pakistan | All Products | ShopPulss')
                ->setDescription('Explore our complete direct retail catalog with genuine electronics, mobile accessories, audio gear, and lifestyle essentials. Nationwide COD across Pakistan.')
                ->setCanonical(route('products.index'))
                ->addBreadcrumb('Home', route('home'))
                ->addBreadcrumb('Shop', route('products.index'))
                ->setRobots('index, follow');
        }

        $query = Product::published()
            ->with(['images', 'category']);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        // Category filter
        if ($catSlug = $request->get('category')) {
            $category = Category::where('slug', $catSlug)->first();
            if ($category) {
                $catIds = $category->children()->pluck('id')->prepend($category->id)->all();
                $query->whereIn('category_id', $catIds);
            }
        }

        // Price range
        if ($minPrice = $request->get('min_price')) {
            $query->whereRaw('COALESCE(sale_price, regular_price) >= ?', [(float) $minPrice]);
        }
        if ($maxPrice = $request->get('max_price')) {
            $query->whereRaw('COALESCE(sale_price, regular_price) <= ?', [(float) $maxPrice]);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, regular_price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, regular_price) DESC'),
            'popular' => $query->orderByDesc('is_featured')->orderByDesc('created_at'),
            'sale' => $query->whereNotNull('sale_price')->orderByDesc('sale_price'),
            default => $query->orderByDesc('created_at'),
        };

        $products = $query->paginate(16)->withQueryString();

        $categories = Category::active()->parents()->withCount('products')->get();

        return view('products.index', compact('products', 'categories', 'sort', 'search'));
    }

    /**
     * Show product detail page.
     */
    public function show(string $slug, SeoService $seo): View
    {
        $product = Product::published()
            ->with([
                'images',
                'category.parent',
                'variants.attributeValues.attribute',
            ])
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug);
                if (is_numeric($slug)) {
                    $q->orWhere('id', (int) $slug);
                }
            })
            ->firstOrFail();

        $seo->forProduct($product);

        // Related products from same category
        $relatedProducts = Product::published()
            ->inStock()
            ->with(['images', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $breadcrumbs = [];
        if ($product->category) {
            if ($product->category->parent) {
                $breadcrumbs[] = $product->category->parent;
            }
            $breadcrumbs[] = $product->category;
        }

        return view('products.show', compact('product', 'relatedProducts', 'breadcrumbs'));
    }
}
