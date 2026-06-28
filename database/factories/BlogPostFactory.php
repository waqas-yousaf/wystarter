<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BlogPostStatus;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
final class BlogPostFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title' => Str::limit($title, 150, ''),
            'slug' => Str::slug($title),
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(4)).'</p>',
            'excerpt' => fake()->paragraph(),
            'featured_image' => null,
            'status' => BlogPostStatus::Draft,
            'author_id' => User::factory(),
            'published_at' => null,
        ];
    }

    public function published(): self
    {
        return $this->state(fn (): array => [
            'status' => BlogPostStatus::Published,
            'published_at' => now(),
        ]);
    }

    public function draft(): self
    {
        return $this->state(fn (): array => [
            'status' => BlogPostStatus::Draft,
            'published_at' => null,
        ]);
    }
}
