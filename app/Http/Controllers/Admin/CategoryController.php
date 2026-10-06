<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AuditLog;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * List categories with two-panel / tree overview.
     */
    public function index(Request $request): View
    {
        $query = Category::with(['parent', 'children', 'attributes'])
            ->withCount('products');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($parentFilter = $request->get('parent_id')) {
            if ($parentFilter === 'root') {
                $query->whereNull('parent_id');
            } elseif ($parentFilter === 'sub') {
                $query->whereNotNull('parent_id');
            } else {
                $query->where('parent_id', $parentFilter);
            }
        }

        // Load the full hierarchy tree (parents with their subcategories)
        // For tree display: always load parent-grouped structure
        $parentTree = Category::with(['children' => function ($q) use ($status) {
            $q->withCount('products')->with('attributes');
            if ($status) {
                $q->where('status', $status);
            }
            $q->orderBy('name');
        }, 'attributes'])
            ->withCount('products')
            ->whereNull('parent_id');

        if ($search) {
            $parentTree->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        if ($status) {
            $parentTree->where('status', $status);
        }
        if ($parentFilter === 'sub') {
            // Only subcategories: show parents that have children
            $parentTree->whereHas('children');
        } elseif ($parentFilter && $parentFilter !== 'root' && $parentFilter !== 'sub') {
            // Filter by specific parent id
            $parentTree->where('id', $parentFilter);
        }

        $parentCategories = $parentTree->orderBy('display_order')->orderBy('name')->get();

        $parents = Category::parents()->orderBy('name')->get();
        $allAttributes = Attribute::active()->orderBy('name')->get();

        $selectedCategory = null;
        if ($editId = $request->get('edit')) {
            $selectedCategory = Category::with('attributes')->find($editId);
        }

        return view('admin.categories.index', compact('parentCategories', 'parents', 'allAttributes', 'selectedCategory'));
    }

    /**
     * Store new category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
            'attribute_ids' => ['nullable', 'array'],
            'attribute_ids.*' => ['exists:attributes,id'],
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'parent_id' => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $request->boolean('is_active', true) ? 'active' : 'inactive',
            'display_order' => (Category::max('display_order') ?? 0) + 1,
        ]);

        if (! empty($validated['attribute_ids'])) {
            $category->attributes()->sync($validated['attribute_ids']);
        }

        AuditLog::record('category.created', $category, "Category '{$category->name}' created", null, $category->toArray());

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Update category.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'parent_id' => ['nullable', 'exists:categories,id', 'not_in:'.$id],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
            'attribute_ids' => ['nullable', 'array'],
            'attribute_ids.*' => ['exists:attributes,id'],
        ]);

        if (! empty($validated['parent_id'])) {
            $childIds = $category->children()->pluck('id')->all();
            if (in_array((int) $validated['parent_id'], $childIds, true)) {
                return back()->with('error', 'Cannot set a subcategory as the parent (circular hierarchy).');
            }
        }

        $oldValues = $category->only(['name', 'parent_id', 'description', 'status']);

        $category->update([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $request->boolean('is_active', true) ? 'active' : 'inactive',
        ]);

        $category->attributes()->sync($validated['attribute_ids'] ?? []);

        AuditLog::record(
            'category.updated',
            $category,
            "Category '{$category->name}' updated",
            $oldValues,
            $category->only(['name', 'parent_id', 'description', 'status'])
        );

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Show create category form — handled inline on the index page.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('admin.categories.index');
    }

    /**
     * Show single category — redirects to index with edit panel open.
     */
    public function show(int $id): RedirectResponse
    {
        return redirect()->route('admin.categories.index', ['edit' => $id]);
    }

    /**
     * Show category edit form — handled inline on the index page.
     */
    public function edit(int $id): RedirectResponse
    {
        return redirect()->route('admin.categories.index', ['edit' => $id]);
    }

    /**
     * Delete category.
     */
    public function destroy(int $id): RedirectResponse
    {
        $category = Category::withCount(['products', 'children'])->findOrFail($id);

        if ($category->children_count > 0) {
            return back()->with('error', 'Cannot delete category that contains subcategories. Please reassign or delete subcategories first.');
        }

        if ($category->products_count > 0) {
            return back()->with('error', 'Cannot delete category that contains products.');
        }

        $oldData = $category->toArray();
        $category->attributes()->detach();
        $category->delete();

        AuditLog::record('category.deleted', null, "Category '{$category->name}' deleted", $oldData, null);

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}
