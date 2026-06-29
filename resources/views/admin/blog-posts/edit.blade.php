<x-layouts.admin title="Edit Blog Post">
    <form method="POST" action="{{ route('admin.blog-posts.update', $blogPost) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Main content column --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Title --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Title <span class="text-gray-400 text-xs font-normal">(max 150 chars)</span>
                    </label>
                    <input id="title" name="title" type="text" maxlength="150"
                           value="{{ old('title', $blogPost->title) }}" required
                           class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-400">Slug: <span class="font-mono">{{ $blogPost->slug }}</span></p>
                </div>

                {{-- Excerpt --}}
                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">
                        Excerpt <span class="text-gray-400 text-xs font-normal">(optional)</span>
                    </label>
                    <textarea id="excerpt" name="excerpt" rows="2" maxlength="500"
                              class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('excerpt', $blogPost->excerpt) }}</textarea>
                </div>

                {{-- Content (TipTap) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                    <x-editor name="content" :value="old('content', $blogPost->content ?? '')" />
                    @error('content')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">

                {{-- Actions --}}
                <div class="bg-white rounded-xl border border-gray-200 p-4 space-y-3">
                    <h3 class="text-sm font-semibold text-gray-900">Publish</h3>
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select id="status" name="status"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}"
                                        @selected(old('status', $blogPost->status->value) === $status->value)>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($blogPost->published_at)
                        <p class="text-xs text-gray-400">
                            Published {{ $blogPost->published_at->format('M j, Y') }}
                        </p>
                    @endif
                    <div class="flex gap-2 pt-1">
                        <a href="{{ route('admin.blog-posts.index') }}"
                           class="flex-1 text-center rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit"
                                class="flex-1 rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            Update
                        </button>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Featured Image</h3>
                    @if ($blogPost->featured_image)
                        <img src="{{ asset('storage/'.$blogPost->featured_image) }}"
                             alt="Featured image"
                             class="mb-3 w-full rounded-lg object-cover aspect-video">
                    @endif
                    <input id="featured_image" name="featured_image" type="file" accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-3 file:rounded file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('featured_image')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tags --}}
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">Tags</h3>
                    <p class="text-xs text-gray-400 mb-2">Comma-separated</p>
                    <input id="tags" name="tags" type="text"
                           value="{{ old('tags', $blogPost->tags->pluck('name')->join(', ')) }}"
                           placeholder="laravel, php, tutorial"
                           class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                {{-- Author --}}
                @if ($users->isNotEmpty())
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Author</h3>
                        <select id="author_id" name="author_id"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                        @selected(old('author_id', $blogPost->author_id) === $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Meta --}}
                <div class="bg-white rounded-xl border border-gray-200 p-4 text-xs text-gray-500 space-y-1">
                    @if ($users->isEmpty())
                        <p>Author: <span class="font-medium text-gray-700">{{ $blogPost->author->name }}</span></p>
                    @endif
                    <p>Created: {{ $blogPost->created_at->format('M j, Y H:i') }}</p>
                    <p>Updated: {{ $blogPost->updated_at->format('M j, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </form>
</x-layouts.admin>
