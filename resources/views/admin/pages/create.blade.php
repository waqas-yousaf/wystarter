<x-layouts.admin title="New Page">
    <form method="POST" action="{{ route('admin.pages.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Title <span class="text-gray-400 text-xs font-normal">(max 150 chars)</span>
                    </label>
                    <input id="title" name="title" type="text" maxlength="150"
                           value="{{ old('title') }}" required autofocus
                           class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-xs text-gray-400 shrink-0">Slug:</span>
                        <input id="slug" name="slug" type="text" maxlength="200"
                               value="{{ old('slug') }}" placeholder="auto-generated"
                               class="flex-1 rounded border border-gray-300 px-2 py-1 text-xs font-mono focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('slug') border-red-500 @enderror">
                        <button type="button" id="generate-slug"
                                class="shrink-0 rounded border border-indigo-300 px-2 py-1 text-xs text-indigo-600 hover:bg-indigo-50">
                            Generate
                        </button>
                    </div>
                    @error('slug')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                    <x-editor name="body" />
                    @error('body')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                {{-- Publish panel --}}
                <div class="bg-white rounded-xl border border-gray-200 p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">Publish</h3>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="published_at" value="1"
                               {{ old('published_at') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Publish now
                    </label>
                    <div class="flex gap-2 pt-1">
                        <a href="{{ route('admin.pages.index') }}"
                           class="flex-1 text-center rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit"
                                class="flex-1 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            Save
                        </button>
                    </div>
                </div>

                {{-- SEO panel --}}
                <div class="bg-white rounded-xl border border-gray-200 p-4 space-y-3">
                    <h3 class="text-sm font-semibold text-gray-900">SEO</h3>
                    <div>
                        <label for="meta_title" class="block text-xs font-medium text-gray-600 mb-1">
                            Meta Title <span class="text-gray-400">(max 100)</span>
                        </label>
                        <input id="meta_title" name="meta_title" type="text" maxlength="100"
                               value="{{ old('meta_title') }}"
                               class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        @error('meta_title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="meta_description" class="block text-xs font-medium text-gray-600 mb-1">
                            Meta Description <span class="text-gray-400">(max 300)</span>
                        </label>
                        <textarea id="meta_description" name="meta_description" rows="3" maxlength="300"
                                  class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>

@push('scripts')
<script>
document.getElementById('generate-slug').addEventListener('click', function () {
    const title = document.getElementById('title').value;
    document.getElementById('slug').value = title
        .toLowerCase()
        .replace(/[^a-z0-9\s\-]/g, '')
        .trim()
        .replace(/[\s\-]+/g, '-')
        .replace(/^-+|-+$/g, '');
});
</script>
@endpush
