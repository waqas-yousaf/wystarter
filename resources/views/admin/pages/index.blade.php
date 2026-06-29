<x-layouts.admin title="Pages">
    <div class="mb-4 flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ $pages->total() }} {{ Str::plural('page', $pages->total()) }}</p>
        <a href="{{ route('admin.pages.create') }}"
           class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
            New Page
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($pages as $page)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900 max-w-xs truncate">{{ $page->title }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $page->slug }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($page->published_at)
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800">Published</span>
                            @else
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $page->published_at?->format('M j, Y') ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                @if ($page->published_at)
                                    <a href="{{ route('page.show', $page->slug) }}" target="_blank"
                                       class="text-gray-400 hover:text-gray-600 font-medium">View</a>
                                @endif
                                <a href="{{ route('admin.pages.edit', $page) }}"
                                   class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.pages.destroy', $page) }}"
                                      onsubmit="return confirm('Delete this page?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-400">No pages yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($pages->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $pages->links() }}</div>
        @endif
    </div>
</x-layouts.admin>
