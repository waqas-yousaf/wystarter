<x-layouts.guest title="Page Not Found">
    <div class="text-center py-4">
        <p class="text-6xl font-bold text-indigo-600">404</p>
        <h1 class="mt-4 text-xl font-bold text-gray-900">Page not found</h1>
        <p class="mt-2 text-sm text-gray-600">The page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ url('/') }}"
           class="mt-6 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
            Return home
        </a>
    </div>
</x-layouts.guest>
