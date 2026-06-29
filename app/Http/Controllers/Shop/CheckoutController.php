<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class CheckoutController
{
    public function create(): View
    {
        return view('shop.checkout');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('shop.index')->with('success', 'Order placed successfully.');
    }
}
