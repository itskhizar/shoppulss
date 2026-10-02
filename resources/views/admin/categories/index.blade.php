@extends('layouts.admin')

@section('title', 'Manage Categories')
@section('header', 'Categories & Dynamic Attributes')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-xl font-black text-gray-900" style="color: #0F1B4D;">Categories Hierarchy & Attributes</h1>
            <p class="text-xs text-gray-500">Manage parent categories, subcategories, and their dynamic product attributes</p>
        </div>
        @if($selectedCategory)
            <a href="{{ route('admin.categories.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700 bg-teal-50 px-3 py-1.5 rounded-lg border border-teal-200 transition-colors">
                + Create New Category
            </a>
        @endif
    </div>

    {{-- Horizontal Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-wrap items-center gap-3">
            {{-- Search input with properly centered icon --}}
            <div class="relative flex-1 min-w-[240px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search category name or slug..."
                    class="w-full h-10 pl-10 pr-4 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs text-gray-800 placeholder-gray-400 transition-colors focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8]"
                >
            </div>

            {{-- Level filter --}}
            <select name="parent_id" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[170px] cursor-pointer" onchange="this.form.submit()">
                <option value="">All Levels</option>
                <option value="root" {{ request('parent_id') === 'root' ? 'selected' : '' }}>Parent Categories Only</option>
                <option value="sub" {{ request('parent_id') === 'sub' ? 'selected' : '' }}>Subcategories Only</option>
                <optgroup label="Under a Specific Parent">
                    @foreach($parents as $p)
                        <option value="{{ $p->id }}" {{ request('parent_id') == $p->id ? 'selected' : '' }}>Under: {{ $p->name }}</option>
                    @endforeach
                </optgroup>
            </select>

            {{-- Status filter --}}
            <select name="status" class="h-10 px-3.5 bg-gray-50/70 hover:bg-white focus:bg-white rounded-xl border border-gray-200 text-xs font-medium text-gray-700 focus:outline-none focus:border-[#00A8B8] focus:ring-1 focus:ring-[#00A8B8] min-w-[130px] cursor-pointer" onchange="this.form.submit()">
                <option value="">Status: All</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2">
                <button type="submit" class="h-10 px-4 rounded-xl text-white font-semibold text-xs shadow-xs hover:opacity-95 transition-opacity inline-flex items-center gap-1.5" style="background-color: #00A8B8;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Filter</span>
                </button>
                @if(request('q') || request('parent_id') || request('status'))
                    <a href="{{ route('admin.categories.index') }}" class="h-10 px-3.5 flex items-center justify-center rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:text-red-600 hover:border-red-200 hover:bg-red-50/30 transition-colors whitespace-nowrap" title="Reset Filters">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Category Tree (Left 2 cols) --}}
        <div class="lg:col-span-2 space-y-4">

            @forelse($parentCategories as $parent)
                {{-- Parent Category Block --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                    {{-- Parent Header Row --}}
                    <div class="flex items-center justify-between px-5 py-4 bg-gradient-to-r from-[#0F1B4D]/5 to-transparent border-b border-gray-100">
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Parent icon --}}
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: #0F1B4D;">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-black text-sm text-gray-900">{{ $parent->name }}</span>
                                    <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-[#0F1B4D] text-white tracking-wider">Parent</span>
                                    <span class="text-[11px] text-gray-400 font-mono">/{{ $parent->slug }}</span>
                                </div>
                                <div class="flex items-center gap-3 mt-1 flex-wrap">
                                    <span class="text-[11px] text-gray-500">{{ $parent->products_count }} products</span>
                                    @if($parent->attributes->isNotEmpty())
                                        <div class="flex gap-1 flex-wrap">
                                            @foreach($parent->attributes as $attr)
                                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100 font-semibold">{{ $attr->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $parent->status === 'active' ? 'bg-teal-100 text-teal-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $parent->status }}
                            </span>
                            <a href="{{ route('admin.categories.index', ['edit' => $parent->id]) }}" class="px-3 py-1 rounded-lg text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 transition-colors">Edit</a>
                            @if($parent->products_count === 0 && $parent->children->isEmpty())
                                <form method="POST" action="{{ route('admin.categories.destroy', $parent->id) }}" onsubmit="return confirm('Delete {{ $parent->name }}?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2 py-1 rounded-lg text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">Del</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- Subcategories under this parent --}}
                    @if($parent->children->isNotEmpty())
                        <div class="divide-y divide-gray-50">
                            @foreach($parent->children as $sub)
                                <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50/70 transition-colors">
                                    <div class="flex items-center gap-3 min-w-0">
                                        {{-- Sub indent line --}}
                                        <div class="flex items-center gap-2 flex-shrink-0 pl-3">
                                            <div class="w-px h-5 bg-gray-200"></div>
                                            <div class="w-3 h-px bg-gray-200"></div>
                                            <div class="w-6 h-6 rounded-md flex items-center justify-center flex-shrink-0" style="background-color: #00A8B8;">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                            </div>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-xs text-gray-800">{{ $sub->name }}</span>
                                                <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded-full bg-teal-100 text-teal-700 tracking-wider">Sub</span>
                                                <span class="text-[10px] text-gray-400 font-mono">/{{ $sub->slug }}</span>
                                            </div>
                                            <div class="flex items-center gap-3 mt-0.5 flex-wrap">
                                                <span class="text-[11px] text-gray-500">{{ $sub->products_count }} products</span>
                                                @if($sub->attributes->isNotEmpty())
                                                    <div class="flex gap-1 flex-wrap">
                                                        @foreach($sub->attributes as $attr)
                                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-100 font-semibold">{{ $attr->name }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-[10px] text-gray-300 italic">inherits parent attributes</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $sub->status === 'active' ? 'bg-teal-100 text-teal-800' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $sub->status }}
                                        </span>
                                        <a href="{{ route('admin.categories.index', ['edit' => $sub->id]) }}" class="px-3 py-1 rounded-lg text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 transition-colors">Edit</a>
                                        @if($sub->products_count === 0)
                                            <form method="POST" action="{{ route('admin.categories.destroy', $sub->id) }}" onsubmit="return confirm('Delete {{ $sub->name }}?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-2 py-1 rounded-lg text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">Del</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-5 py-3 text-[11px] text-gray-400 italic flex items-center gap-2 pl-16">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            No subcategories yet — add one using the form
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-10 shadow-sm text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <p class="text-sm font-semibold">No categories found</p>
                    <p class="text-xs mt-1">Use the form on the right to create your first parent category</p>
                </div>
            @endforelse
        </div>

        {{-- Add / Edit Category Form (Right 1 col) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4 sticky top-24">
                <h2 class="text-sm font-bold text-gray-900 pb-3 border-b border-gray-100" style="color: #0F1B4D;">
                    {{ $selectedCategory ? 'Edit: ' . $selectedCategory->name : 'Create Category' }}
                </h2>

                <form method="POST" action="{{ $selectedCategory ? route('admin.categories.update', $selectedCategory->id) : route('admin.categories.store') }}" class="space-y-4">
                    @csrf
                    @if($selectedCategory) @method('PUT') @endif

                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Category Name *</label>
                        <input
                            id="name" type="text" name="name" required
                            value="{{ old('name', $selectedCategory?->name) }}"
                            placeholder="e.g. Smart Watches"
                            class="w-full h-10 px-3.5 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500"
                        >
                    </div>

                    <div>
                        <label for="parent_id" class="block text-xs font-semibold text-gray-700 mb-1">
                            Parent Category
                            <span class="font-normal text-gray-400 ml-1">— leave blank to create as top-level</span>
                        </label>
                        <select id="parent_id" name="parent_id" class="w-full h-10 px-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500">
                            <option value="">None — Top-Level Parent Category</option>
                            @foreach($parents as $p)
                                @if(!$selectedCategory || $selectedCategory->id !== $p->id)
                                    <option value="{{ $p->id }}" {{ old('parent_id', $selectedCategory?->parent_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1">
                            Selecting a parent makes this a <strong>subcategory</strong>.
                        </p>
                    </div>

                    {{-- Dynamic Attributes --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Assign Dynamic Attributes</label>
                        <p class="text-[11px] text-gray-400 mb-2">Products in this category use these attributes for variant options (e.g. Color, Size)</p>
                        <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-xl border border-gray-100 max-h-40 overflow-y-auto">
                            @php
                                $selectedAttrIds = old('attribute_ids', $selectedCategory ? $selectedCategory->attributes->pluck('id')->all() : []);
                            @endphp
                            @foreach($allAttributes as $attr)
                                <label class="flex items-center gap-2 text-xs text-gray-700 cursor-pointer hover:text-teal-700">
                                    <input
                                        type="checkbox"
                                        name="attribute_ids[]"
                                        value="{{ $attr->id }}"
                                        {{ in_array($attr->id, $selectedAttrIds) ? 'checked' : '' }}
                                        class="rounded text-teal-600 focus:ring-teal-500 w-3.5 h-3.5"
                                    >
                                    <span class="font-medium">{{ $attr->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-semibold text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="2"
                            placeholder="Optional category overview..."
                            class="w-full p-3 rounded-lg border border-gray-200 text-xs focus:outline-none focus:border-teal-500">{{ old('description', $selectedCategory?->description) }}</textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox" name="is_active" value="1"
                            id="is_active_check"
                            {{ old('is_active', $selectedCategory ? $selectedCategory->status === 'active' : true) ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500"
                        >
                        <label for="is_active_check" class="text-xs font-semibold text-gray-700 cursor-pointer">Category Active</label>
                    </div>

                    <div class="flex gap-2 pt-1">
                        @if($selectedCategory)
                            <a href="{{ route('admin.categories.index') }}" class="w-1/3 h-10 rounded-lg border border-gray-200 text-gray-600 font-semibold text-xs flex items-center justify-center hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                        @endif
                        <button type="submit" class="flex-1 h-10 rounded-lg text-white font-semibold text-xs shadow-md transition-all hover:opacity-95" style="background-color: #00A8B8;">
                            {{ $selectedCategory ? 'Save Changes' : 'Create Category' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
@endsection
