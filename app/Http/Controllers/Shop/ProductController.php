<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Models\Product;
use Illuminate\Contracts\View\View;

final class ProductController
{
    public function index(): View
    {
        $products = Product::query()
            ->where('status', '=', 'active')
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('shop.index', compact('products'));
    }

    public function show(Product $product): View
    {
        return view('shop.show', compact('product'));
    }
}
