<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class CartController
{
    public function index(): View
    {
        return view('shop.cart');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('cart.index')->with('success', 'Item added to cart.');
    }

    public function update(Request $request, int $cartItem): RedirectResponse
    {
        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function destroy(int $cartItem): RedirectResponse
    {
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
