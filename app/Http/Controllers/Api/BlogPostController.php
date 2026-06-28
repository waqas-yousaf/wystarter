<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\BlogPostStatus;
use App\Http\Resources\BlogPostListResource;
use App\Http\Resources\BlogPostResource;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class BlogPostController
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $posts = BlogPost::with(['author', 'tags'])
            ->where('status', BlogPostStatus::Published)
            ->when($request->tag, fn ($q, $tag) => $q->whereHas('tags', fn ($q) => $q->where('slug', $tag)))
            ->when($request->search, fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
            ->latest('published_at')
            ->paginate($request->integer('per_page', 15));

        return BlogPostListResource::collection($posts);
    }

    public function show(BlogPost $blogPost): BlogPostResource
    {
        abort_if($blogPost->status !== BlogPostStatus::Published, 404);

        $blogPost->load(['author', 'tags']);

        return new BlogPostResource($blogPost);
    }
}
