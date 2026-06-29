<x-layouts.guest title="Server Error">
    <div class="text-center py-4">
        <p class="text-6xl font-bold text-gray-400">500</p>
        <h1 class="mt-4 text-xl font-bold text-gray-900">Something went wrong</h1>
        <p class="mt-2 text-sm text-gray-600">We're experiencing a server error. Please try again shortly.</p>
        <a href="{{ url('/') }}"
           class="mt-6 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
            Return home
        </a>
    </div>
</x-layouts.guest>
