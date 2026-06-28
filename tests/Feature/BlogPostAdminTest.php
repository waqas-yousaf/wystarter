<?php

declare(strict_types=1);

use App\Enums\BlogPostStatus;
use App\Models\BlogPost;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'author', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
});

// Index
test('admin can view all blog posts', function (): void {
    $admin = User::factory()->admin()->create();
    BlogPost::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.blog-posts.index'))
        ->assertOk()
        ->assertViewIs('admin.blog-posts.index')
        ->assertViewHas('posts');
});

test('author only sees their own posts in the index', function (): void {
    $author = User::factory()->create();
    $author->assignRole('author');

    BlogPost::factory()->count(2)->create(['author_id' => $author->id]);
    BlogPost::factory()->count(2)->create();

    $response = $this->actingAs($author)->get(route('admin.blog-posts.index'));
    $response->assertOk();
    expect($response->viewData('posts')->total())->toBe(2);
});

test('regular user cannot view blog posts index', function (): void {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->actingAs($user)->get(route('admin.blog-posts.index'))->assertForbidden();
});

test('guest is redirected to login from blog posts index', function (): void {
    $this->get(route('admin.blog-posts.index'))->assertRedirect('/login');
});

// Create
test('admin can view create blog post form', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.blog-posts.create'))
        ->assertOk()
        ->assertViewIs('admin.blog-posts.create');
});

test('admin can create a blog post', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.blog-posts.store'), [
            'title' => 'My Test Post',
            'status' => BlogPostStatus::Draft->value,
        ])
        ->assertRedirect(route('admin.blog-posts.index'));

    $this->assertDatabaseHas('blog_posts', [
        'title' => 'My Test Post',
        'status' => BlogPostStatus::Draft->value,
        'author_id' => $admin->id,
    ]);
});

test('creating a published post sets published_at', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.blog-posts.store'), [
        'title' => 'Published Post',
        'status' => BlogPostStatus::Published->value,
    ]);

    $post = BlogPost::where('title', 'Published Post')->first();
    expect($post->published_at)->not->toBeNull();
});

test('creating a post with tags creates the tags', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.blog-posts.store'), [
        'title' => 'Tagged Post',
        'status' => BlogPostStatus::Draft->value,
        'tags' => 'laravel, php',
    ]);

    $post = BlogPost::where('title', 'Tagged Post')->first();
    expect($post->tags)->toHaveCount(2);
});

test('blog post store validates title max length', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.blog-posts.store'), [
            'title' => str_repeat('a', 151),
            'status' => BlogPostStatus::Draft->value,
        ])
        ->assertSessionHasErrors('title');
});

test('blog post store requires title', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.blog-posts.store'), [
            'status' => BlogPostStatus::Draft->value,
        ])
        ->assertSessionHasErrors('title');
});

// Edit
test('admin can view edit blog post form', function (): void {
    $admin = User::factory()->admin()->create();
    $post = BlogPost::factory()->create(['author_id' => $admin->id]);

    $this->actingAs($admin)
        ->get(route('admin.blog-posts.edit', $post))
        ->assertOk()
        ->assertViewIs('admin.blog-posts.edit');
});

test('author can edit their own post', function (): void {
    $author = User::factory()->create();
    $author->assignRole('author');
    $post = BlogPost::factory()->create(['author_id' => $author->id]);

    $this->actingAs($author)
        ->get(route('admin.blog-posts.edit', $post))
        ->assertOk();
});

test('author cannot edit another authors post', function (): void {
    $author = User::factory()->create();
    $author->assignRole('author');
    $post = BlogPost::factory()->create();

    $this->actingAs($author)
        ->get(route('admin.blog-posts.edit', $post))
        ->assertForbidden();
});

// Update
test('admin can update any blog post', function (): void {
    $admin = User::factory()->admin()->create();
    $post = BlogPost::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.blog-posts.update', $post), [
            'title' => 'Updated Title',
            'status' => BlogPostStatus::Published->value,
        ])
        ->assertRedirect(route('admin.blog-posts.index'));

    expect($post->fresh()->title)->toBe('Updated Title');
});

test('author can update their own post', function (): void {
    $author = User::factory()->create();
    $author->assignRole('author');
    $post = BlogPost::factory()->create(['author_id' => $author->id]);

    $this->actingAs($author)
        ->put(route('admin.blog-posts.update', $post), [
            'title' => 'My Updated Post',
            'status' => BlogPostStatus::Draft->value,
        ])
        ->assertRedirect(route('admin.blog-posts.index'));

    expect($post->fresh()->title)->toBe('My Updated Post');
});

test('author cannot update another authors post', function (): void {
    $author = User::factory()->create();
    $author->assignRole('author');
    $post = BlogPost::factory()->create();

    $this->actingAs($author)
        ->put(route('admin.blog-posts.update', $post), [
            'title' => 'Stolen Title',
            'status' => BlogPostStatus::Draft->value,
        ])
        ->assertForbidden();
});

// Destroy
test('admin can delete any blog post', function (): void {
    $admin = User::factory()->admin()->create();
    $post = BlogPost::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.blog-posts.destroy', $post))
        ->assertRedirect(route('admin.blog-posts.index'));

    $this->assertModelMissing($post);
});

test('author can delete their own post', function (): void {
    $author = User::factory()->create();
    $author->assignRole('author');
    $post = BlogPost::factory()->create(['author_id' => $author->id]);

    $this->actingAs($author)
        ->delete(route('admin.blog-posts.destroy', $post))
        ->assertRedirect(route('admin.blog-posts.index'));

    $this->assertModelMissing($post);
});

test('author cannot delete another authors post', function (): void {
    $author = User::factory()->create();
    $author->assignRole('author');
    $post = BlogPost::factory()->create();

    $this->actingAs($author)
        ->delete(route('admin.blog-posts.destroy', $post))
        ->assertForbidden();

    $this->assertModelExists($post);
});
