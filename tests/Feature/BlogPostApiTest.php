<?php

declare(strict_types=1);

use App\Enums\BlogPostStatus;
use App\Models\BlogPost;
use App\Models\Tag;
use App\Models\User;

// Index
test('api returns only published posts', function (): void {
    BlogPost::factory()->published()->count(3)->create();
    BlogPost::factory()->draft()->count(2)->create();

    $this->getJson('/api/v1/blog-posts')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

test('api supports pagination with per_page', function (): void {
    BlogPost::factory()->published()->count(5)->create();

    $this->getJson('/api/v1/blog-posts?per_page=2')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.total', 5);
});

test('api can filter posts by tag slug', function (): void {
    $tag = Tag::create(['name' => 'laravel', 'slug' => 'laravel']);
    $post = BlogPost::factory()->published()->create();
    $post->tags()->attach($tag);
    BlogPost::factory()->published()->count(2)->create();

    $this->getJson('/api/v1/blog-posts?tag=laravel')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

test('api can search posts by title', function (): void {
    BlogPost::factory()->published()->create(['title' => 'Laravel is great']);
    BlogPost::factory()->published()->count(2)->create();

    $this->getJson('/api/v1/blog-posts?search=laravel')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

test('api response includes expected fields', function (): void {
    $author = User::factory()->create();
    BlogPost::factory()->published()->create([
        'author_id' => $author->id,
        'title' => 'Test Post',
    ]);

    $this->getJson('/api/v1/blog-posts')
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'slug', 'excerpt', 'status', 'author', 'tags', 'published_at'],
            ],
        ]);
});

test('api list does not include content field', function (): void {
    BlogPost::factory()->published()->create();

    $response = $this->getJson('/api/v1/blog-posts')->assertOk();
    $this->assertArrayNotHasKey('content', $response->json('data.0'));
});

// Show
test('api returns a published post by slug', function (): void {
    $post = BlogPost::factory()->published()->create(['slug' => 'my-post']);

    $this->getJson('/api/v1/blog-posts/my-post')
        ->assertOk()
        ->assertJsonPath('data.slug', 'my-post');
});

test('api returns content on the show endpoint', function (): void {
    $post = BlogPost::factory()->published()->create(['slug' => 'my-post']);

    $this->getJson('/api/v1/blog-posts/my-post')
        ->assertOk()
        ->assertJsonStructure(['data' => ['content']]);
});

test('api returns 404 for a draft post', function (): void {
    BlogPost::factory()->draft()->create(['slug' => 'hidden-post']);

    $this->getJson('/api/v1/blog-posts/hidden-post')->assertNotFound();
});

test('api returns 404 for an archived post', function (): void {
    BlogPost::factory()->create([
        'slug' => 'archived-post',
        'status' => BlogPostStatus::Archived,
    ]);

    $this->getJson('/api/v1/blog-posts/archived-post')->assertNotFound();
});

test('api returns 404 for a non-existent post', function (): void {
    $this->getJson('/api/v1/blog-posts/does-not-exist')->assertNotFound();
});

// Tags endpoint
test('api returns all tags ordered by name', function (): void {
    Tag::create(['name' => 'php', 'slug' => 'php']);
    Tag::create(['name' => 'laravel', 'slug' => 'laravel']);

    $this->getJson('/api/v1/tags')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.name', 'laravel');
});
