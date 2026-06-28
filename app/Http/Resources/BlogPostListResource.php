<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\BlogPost */
final class BlogPostListResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'featured_image' => $this->featured_image
                ? asset('storage/'.$this->featured_image)
                : null,
            'status' => $this->status->value,
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ],
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
