<x-layouts.guest :title="$product->name">
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('shop.index') }}" class="text-sm text-indigo-600 hover:underline">&larr; Back to shop</a>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-10">
                <div>
                    @if ($product->featured_image)
                        <img src="{{ asset('storage/' . $product->featured_image) }}"
                             alt="{{ $product->name }}"
                             class="w-full rounded-xl object-cover aspect-square">
                    @else
                        <div class="w-full aspect-square bg-gray-100 rounded-xl flex items-center justify-center">
                            <span class="text-6xl text-gray-300">📦</span>
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</p>
                    @if ($product->compare_price)
                        <p class="mt-1 text-sm text-gray-400 line-through">${{ number_format($product->compare_price, 2) }}</p>
                    @endif

                    @if ($product->description)
                        <p class="mt-4 text-sm text-gray-600">{{ $product->description }}</p>
                    @endif

                    <p class="mt-4 text-sm text-gray-500">
                        {{ $product->stock_qty > 0 ? "{$product->stock_qty} in stock" : 'Out of stock' }}
                    </p>

                    <form method="POST" action="{{ route('cart.store') }}" class="mt-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit"
                                class="w-full rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-500 disabled:opacity-50"
                                @if($product->stock_qty === 0) disabled @endif>
                            Add to cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
