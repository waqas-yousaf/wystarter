<x-layouts.guest title="Checkout">
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <p class="text-sm text-gray-500 text-center">
                    Checkout flow coming soon. Integrate your payment gateway here.
                </p>
                <a href="{{ route('cart.index') }}"
                   class="mt-4 block text-center text-sm text-indigo-600 hover:underline">
                    Back to cart
                </a>
            </div>
        </div>
    </div>
</x-layouts.guest>
