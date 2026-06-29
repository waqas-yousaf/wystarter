<x-layouts.admin title="Categories">
    <div class="flex items-center justify-between mb-6">
        <div></div>
        <a href="{{ route('admin.shop.categories.create') }}"
           class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
            Add Category
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $category->products_count }}</td>
                        <td class="px-4 py-3 text-right text-sm space-x-2">
                            <a href="{{ route('admin.shop.categories.edit', $category) }}"
                               class="text-indigo-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.shop.categories.destroy', $category) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete this category?')"
                                        class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-400">No categories yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $categories->links() }}</div>
    </div>
</x-layouts.admin>
