<x-layouts.guest title="Forbidden">
    <div class="text-center py-4">
        <p class="text-6xl font-bold text-red-500">403</p>
        <h1 class="mt-4 text-xl font-bold text-gray-900">Access denied</h1>
        <p class="mt-2 text-sm text-gray-600">You don't have permission to view this page.</p>
        <a href="{{ url('/') }}"
           class="mt-6 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
            Return home
        </a>
    </div>
</x-layouts.guest>
