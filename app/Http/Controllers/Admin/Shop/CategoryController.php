<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Shop;

use App\Models\ProductCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class CategoryController
{
    public function index(): View
    {
        $categories = ProductCategory::withCount('products')->orderBy('name')->paginate(15);

        return view('admin.shop.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parents = ProductCategory::orderBy('name')->pluck('name', 'id');

        return view('admin.shop.categories.create', compact('parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:product_categories,id'],
        ]);

        ProductCategory::create([
            ...$validated,
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('admin.shop.categories.index')->with('success', 'Category created.');
    }

    public function edit(ProductCategory $category): View
    {
        $parents = ProductCategory::where('id', '!=', $category->id)->orderBy('name')->pluck('name', 'id');

        return view('admin.shop.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, ProductCategory $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:product_categories,id'],
        ]);

        $category->update($validated);

        return redirect()->route('admin.shop.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(ProductCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.shop.categories.index')->with('success', 'Category deleted.');
    }
}
