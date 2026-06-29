<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\BlogPostStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

final class StoreBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\BlogPost::class);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'content' => ['nullable', 'string'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', new Enum(BlogPostStatus::class)],
            'tags' => ['nullable', 'string'],
            'author_id' => ['nullable', 'uuid', Rule::exists('users', 'id')],
        ];
    }
}
