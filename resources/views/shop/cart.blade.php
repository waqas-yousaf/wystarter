<x-layouts.guest title="Your Cart">
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Your Cart</h1>

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500">
                <p>Your cart is empty.</p>
                <a href="{{ route('shop.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline text-sm">
                    Continue shopping
                </a>
            </div>
        </div>
    </div>
</x-layouts.guest>
