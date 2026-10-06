<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * List all products in admin.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'images', 'variants']);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($catId = $request->get('category_id')) {
            $query->where('category_id', $catId);
        }

        if ($stock = $request->get('stock')) {
            if ($stock === 'low') {
                $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0);
            } elseif ($stock === 'out') {
                $query->where('stock_quantity', '<=', 0);
            } elseif ($stock === 'in') {
                $query->where('stock_quantity', '>', 0);
            }
        }

        if ($sort = $request->get('sort')) {
            if ($sort === 'oldest') {
                $query->oldest('created_at');
            } elseif ($sort === 'price_high') {
                $query->orderByDesc('regular_price');
            } elseif ($sort === 'price_low') {
                $query->orderBy('regular_price');
            } elseif ($sort === 'stock_high') {
                $query->orderByDesc('stock_quantity');
            } elseif ($sort === 'stock_low') {
                $query->orderBy('stock_quantity');
            } else {
                $query->latest('created_at');
            }
        } else {
            $query->latest('created_at');
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::active()
            ->whereNull('parent_id')
            ->with(['children' => fn ($q) => $q->active()->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show create product form.
     */
    public function create(): View
    {
        $categories = Category::active()
            ->parents()
            ->with(['children' => fn ($q) => $q->active()->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Show comprehensive product details in admin.
     */
    public function show(int $id): View
    {
        $product = Product::with([
            'category.parent',
            'images',
            'variants.attributeValues.attribute',
        ])->findOrFail($id);

        $orderItems = OrderItem::where('product_id', $product->id)
            ->with('order')
            ->latest()
            ->limit(10)
            ->get();
        $totalSold = (int) OrderItem::where('product_id', $product->id)->sum('quantity');
        $totalRevenue = (float) OrderItem::where('product_id', $product->id)->sum('total_price');

        return view('admin.products.show', compact('product', 'orderItems', 'totalSold', 'totalRevenue'));
    }

    /**
     * Return attributes for a selected category as JSON.
     */
    public function categoryAttributes(int $categoryId): JsonResponse
    {
        $category = Category::with('attributes.values')->findOrFail($categoryId);

        // Also check parent category attributes if this is a subcategory
        $attributes = $category->attributes;
        if ($attributes->isEmpty() && $category->parent_id) {
            $attributes = $category->parent?->attributes ?? collect();
        }

        return response()->json($attributes);
    }

    /**
     * Store new product.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:simple,variant'],
            'regular_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'lt:regular_price'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_featured' => ['boolean'],
            'is_new' => ['boolean'],
            'is_deal' => ['boolean'],
            'deal_start_at' => ['nullable', 'date'],
            'deal_end_at' => ['nullable', 'date'],
            'is_trending' => ['boolean'],
            'image_url' => ['nullable', 'url'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp,avif', 'max:20480'],
        ], [
            'images.*.image' => 'Each uploaded file must be an image.',
            'images.*.mimes' => 'Images must be in JPEG, PNG, JPG, WebP, or AVIF format.',
            'images.*.max' => 'Each image must not exceed 20MB in size.',
            'images.*.uploaded' => 'An image failed to upload. Please ensure the file is under 20MB and not corrupted.',
        ]);

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(5);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_deal'] = $request->boolean('is_deal');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['deal_start_at'] = $request->filled('deal_start_at') ? $request->date('deal_start_at') : null;
        $validated['deal_end_at'] = $request->filled('deal_end_at') ? $request->date('deal_end_at') : null;

        $imageUrl = $validated['image_url'] ?? null;
        unset($validated['image_url'], $validated['images']);

        $product = Product::create($validated);

        AuditLog::record('product.created', $product, "Product '{$product->name}' (SKU: {$product->sku}) created", null, $product->toArray());

        if ($imageUrl) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $imageUrl,
                'alt_text' => $product->name,
                'is_featured' => true,
                'display_order' => 1,
            ]);
        }

        if ($request->hasFile('images')) {
            $nextOrder = (int) $product->images()->max('display_order') + 1;
            $hasFeatured = $product->images()->where('is_featured', true)->exists();
            foreach ($request->file('images') as $idx => $file) {
                $ext = $file->getClientOriginalExtension() ?: 'jpg';
                $filename = time().'_'.Str::random(10).'.'.$ext;
                $file->storeAs('products', $filename, 'public');
                $publicUrl = '/storage/products/'.$filename;

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $publicUrl,
                    'alt_text' => $product->name,
                    'is_featured' => ! $hasFeatured && $idx === 0,
                    'display_order' => $nextOrder++,
                ]);
            }
        }

        // Always create a standard variant for simple products
        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $product->sku.'-V1',
            'name' => 'Standard',
            'price' => $product->regular_price,
            'sale_price' => $product->sale_price,
            'stock_quantity' => $product->stock_quantity,
            'status' => 'active',
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Show edit product form.
     */
    public function edit(int $id): View
    {
        $product = Product::with(['images', 'variants.attributeValues', 'category.attributes.values'])->findOrFail($id);
        $categories = Category::active()
            ->parents()
            ->with(['children' => fn ($q) => $q->active()->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,'.$product->id],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:simple,variant'],
            'regular_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_featured' => ['boolean'],
            'is_new' => ['boolean'],
            'is_deal' => ['boolean'],
            'deal_start_at' => ['nullable', 'date'],
            'deal_end_at' => ['nullable', 'date'],
            'is_trending' => ['boolean'],
            'image_url' => ['nullable', 'url'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp,avif', 'max:20480'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:product_images,id'],
        ], [
            'images.*.image' => 'Each uploaded file must be an image.',
            'images.*.mimes' => 'Images must be in JPEG, PNG, JPG, WebP, or AVIF format.',
            'images.*.max' => 'Each image must not exceed 20MB in size.',
            'images.*.uploaded' => 'An image failed to upload. Please ensure the file is under 20MB and not corrupted.',
        ]);

        $oldValues = $product->only([
            'name', 'sku', 'category_id', 'regular_price', 'sale_price',
            'stock_quantity', 'status', 'is_featured', 'is_deal', 'deal_start_at', 'deal_end_at',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new'] = $request->boolean('is_new');
        $validated['is_deal'] = $request->boolean('is_deal');
        $validated['is_trending'] = $request->boolean('is_trending');
        $validated['deal_start_at'] = $request->filled('deal_start_at') ? $request->date('deal_start_at') : null;
        $validated['deal_end_at'] = $request->filled('deal_end_at') ? $request->date('deal_end_at') : null;

        $imageUrl = $validated['image_url'] ?? null;
        $deleteImages = $validated['delete_images'] ?? [];
        unset($validated['image_url'], $validated['images'], $validated['delete_images']);

        $product->update($validated);

        AuditLog::record('product.updated', $product, "Product '{$product->name}' updated", $oldValues, $product->only(array_keys($oldValues)));

        if (! empty($deleteImages)) {
            $product->images()->whereIn('id', $deleteImages)->delete();
        }

        if ($imageUrl) {
            $featuredImage = $product->images()->where('is_featured', true)->first();
            if ($featuredImage) {
                $featuredImage->update(['image_url' => $imageUrl]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imageUrl,
                    'alt_text' => $product->name,
                    'is_featured' => true,
                    'display_order' => 1,
                ]);
            }
        }

        if ($request->hasFile('images')) {
            $nextOrder = (int) $product->images()->max('display_order') + 1;
            $hasFeatured = $product->images()->where('is_featured', true)->exists();
            foreach ($request->file('images') as $idx => $file) {
                $ext = $file->getClientOriginalExtension() ?: 'jpg';
                $filename = time().'_'.Str::random(10).'.'.$ext;
                $file->storeAs('products', $filename, 'public');
                $publicUrl = '/storage/products/'.$filename;

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $publicUrl,
                    'alt_text' => $product->name,
                    'is_featured' => ! $hasFeatured && $idx === 0,
                    'display_order' => $nextOrder++,
                ]);
            }
        }

        if (! $product->images()->where('is_featured', true)->exists() && $product->images()->exists()) {
            $product->images()->first()->update(['is_featured' => true]);
        }

        // Sync standard variant stock and price if it has variants
        $standardVariant = $product->variants()->first();
        if ($standardVariant) {
            $standardVariant->update([
                'price' => $product->regular_price,
                'sale_price' => $product->sale_price,
                'stock_quantity' => $product->stock_quantity,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product safely.
     */
    public function destroy(int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $hasOrders = OrderItem::where('product_id', $product->id)->exists();
        if ($hasOrders) {
            $product->update(['status' => 'archived']);
            $product->delete();

            AuditLog::record('product.archived', $product, "Product '{$product->name}' archived and soft-deleted (has order history)", $product->toArray(), null);

            return redirect()->route('admin.products.index')->with('success', 'Product has associated orders, so it was safely archived and soft-deleted.');
        }

        AuditLog::record('product.deleted', $product, "Product '{$product->name}' permanently deleted", $product->toArray(), null);

        $product->images()->delete();
        $product->variants()->delete();
        $product->forceDelete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
}
