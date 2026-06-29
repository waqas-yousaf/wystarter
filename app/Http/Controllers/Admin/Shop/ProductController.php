<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Shop;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class ProductController
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(15);

        return view('admin.shop.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = ProductCategory::orderBy('name')->pluck('name', 'id');

        return view('admin.shop.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_qty' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:product_categories,id'],
        ]);

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(6);

        Product::create($validated);

        return redirect()->route('admin.shop.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::orderBy('name')->pluck('name', 'id');

        return view('admin.shop.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_qty' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:product_categories,id'],
        ]);

        $product->update($validated);

        return redirect()->route('admin.shop.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.shop.products.index')->with('success', 'Product deleted.');
    }
}
