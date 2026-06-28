<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\BlogPostStatus;
use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

final class BlogPostController
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize('viewAny', BlogPost::class);

        $posts = BlogPost::with(['author', 'tags'])
            ->when(
                ! auth()->user()->hasRole('admin'),
                fn ($q) => $q->where('author_id', auth()->id()),
            )
            ->latest()
            ->paginate(15);

        return view('admin.blog-posts.index', compact('posts'));
    }

    public function create(): View
    {
        $this->authorize('create', BlogPost::class);

        $statuses = BlogPostStatus::cases();

        return view('admin.blog-posts.create', compact('statuses'));
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $post = BlogPost::create([
            'title' => $validated['title'],
            'slug' => BlogPost::generateSlug($validated['title']),
            'content' => $validated['content'] ?? null,
            'excerpt' => $validated['excerpt'] ?? null,
            'featured_image' => $request->hasFile('featured_image')
                ? $request->file('featured_image')->store('blog/images', 'public')
                : null,
            'status' => $validated['status'],
            'author_id' => auth()->id(),
            'published_at' => $validated['status'] === BlogPostStatus::Published->value
                ? now()
                : null,
        ]);

        $post->tags()->sync($this->resolveTagIds($validated['tags'] ?? ''));

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('success', 'Blog post created.');
    }

    public function edit(BlogPost $blogPost): View
    {
        $this->authorize('update', $blogPost);

        $blogPost->load('tags');
        $statuses = BlogPostStatus::cases();

        return view('admin.blog-posts.edit', compact('blogPost', 'statuses'));
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost): RedirectResponse
    {
        $validated = $request->validated();

        $featuredImage = $blogPost->featured_image;
        if ($request->hasFile('featured_image')) {
            $featuredImage = $request->file('featured_image')->store('blog/images', 'public');
        }

        $blogPost->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'excerpt' => $validated['excerpt'] ?? null,
            'featured_image' => $featuredImage,
            'status' => $validated['status'],
            'published_at' => $validated['status'] === BlogPostStatus::Published->value
                ? ($blogPost->published_at ?? now())
                : null,
        ]);

        $blogPost->tags()->sync($this->resolveTagIds($validated['tags'] ?? ''));

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('success', 'Blog post updated.');
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $this->authorize('delete', $blogPost);

        $blogPost->delete();

        return redirect()
            ->route('admin.blog-posts.index')
            ->with('success', 'Blog post deleted.');
    }

    /** @return array<int> */
    private function resolveTagIds(string $tagInput): array
    {
        if (blank($tagInput)) {
            return [];
        }

        return collect(explode(',', $tagInput))
            ->map(fn (string $name): string => Str::lower(trim($name)))
            ->filter()
            ->unique()
            ->map(function (string $name): int {
                return Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name],
                )->id;
            })
            ->values()
            ->all();
    }
}
