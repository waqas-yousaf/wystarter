<x-layouts.guest title="Shop">
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-8">Shop</h1>

            @if ($products->isEmpty())
                <p class="text-gray-500">No products available yet.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        <a href="{{ route('shop.show', $product) }}"
                           class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                            @if ($product->featured_image)
                                <img src="{{ asset('storage/' . $product->featured_image) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full aspect-square object-cover">
                            @else
                                <div class="w-full aspect-square bg-gray-100 flex items-center justify-center">
                                    <span class="text-4xl text-gray-300">📦</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <h2 class="text-sm font-semibold text-gray-900 line-clamp-2">{{ $product->name }}</h2>
                                <p class="mt-1 text-indigo-600 font-bold">${{ number_format($product->price, 2) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.guest>
