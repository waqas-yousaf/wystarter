<?php

declare(strict_types=1);

use App\Models\Page;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
});

// Index
test('admin can view pages index', function (): void {
    $admin = User::factory()->admin()->create();
    $page = Page::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.pages.index'))
        ->assertOk()
        ->assertSee($page->title);
});

test('guest is redirected from pages index', function (): void {
    $this->get(route('admin.pages.index'))->assertRedirect(route('login'));
});

test('non-admin gets 403 on pages index', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.pages.index'))->assertForbidden();
});

// Create
test('admin can view create page form', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get(route('admin.pages.create'))->assertOk();
});

test('admin can create a draft page', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), ['title' => 'About Us'])
        ->assertRedirect(route('admin.pages.index'));

    $this->assertDatabaseHas('pages', ['title' => 'About Us', 'published_at' => null]);
});

test('admin can create a published page', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), ['title' => 'Contact', 'published_at' => '1'])
        ->assertRedirect(route('admin.pages.index'));

    $page = Page::where('title', 'Contact')->first();
    expect($page->published_at)->not->toBeNull();
});

test('slug is auto-generated from title on create', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), ['title' => 'My New Page']);

    $this->assertDatabaseHas('pages', ['slug' => 'my-new-page']);
});

test('admin can provide a custom slug on create', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), ['title' => 'About Us', 'slug' => 'custom-about']);

    $this->assertDatabaseHas('pages', ['slug' => 'custom-about']);
});

test('store rejects a duplicate slug', function (): void {
    $admin = User::factory()->admin()->create();
    Page::factory()->create(['slug' => 'existing-slug']);

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), ['title' => 'Test', 'slug' => 'existing-slug'])
        ->assertSessionHasErrors('slug');
});

test('store rejects an invalid slug format', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), ['title' => 'Test', 'slug' => 'Has Spaces'])
        ->assertSessionHasErrors('slug');
});

test('admin can update page slug', function (): void {
    $admin = User::factory()->admin()->create();
    $page = Page::factory()->create(['slug' => 'old-slug']);

    $this->actingAs($admin)
        ->put(route('admin.pages.update', $page), ['title' => $page->title, 'slug' => 'new-slug']);

    $this->assertDatabaseHas('pages', ['id' => $page->id, 'slug' => 'new-slug']);
});

test('update allows keeping the same slug', function (): void {
    $admin = User::factory()->admin()->create();
    $page = Page::factory()->create(['slug' => 'my-page']);

    $this->actingAs($admin)
        ->put(route('admin.pages.update', $page), ['title' => 'New Title', 'slug' => 'my-page'])
        ->assertRedirect(route('admin.pages.index'));

    $this->assertDatabaseHas('pages', ['id' => $page->id, 'slug' => 'my-page']);
});

test('update rejects slug already used by another page', function (): void {
    $admin = User::factory()->admin()->create();
    Page::factory()->create(['slug' => 'taken-slug']);
    $page = Page::factory()->create(['slug' => 'my-page']);

    $this->actingAs($admin)
        ->put(route('admin.pages.update', $page), ['title' => $page->title, 'slug' => 'taken-slug'])
        ->assertSessionHasErrors('slug');
});

test('store validates title is required', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), [])
        ->assertSessionHasErrors('title');
});

test('store validates title max 150 chars', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), ['title' => str_repeat('a', 151)])
        ->assertSessionHasErrors('title');
});

test('store validates meta_title max 100 chars', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), ['title' => 'Valid', 'meta_title' => str_repeat('a', 101)])
        ->assertSessionHasErrors('meta_title');
});

test('store validates meta_description max 300 chars', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.pages.store'), ['title' => 'Valid', 'meta_description' => str_repeat('a', 301)])
        ->assertSessionHasErrors('meta_description');
});

// Edit
test('admin can view edit page form', function (): void {
    $admin = User::factory()->admin()->create();
    $page = Page::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.pages.edit', $page))
        ->assertOk()
        ->assertSee($page->title);
});

test('non-admin gets 403 on edit page form', function (): void {
    $user = User::factory()->create();
    $page = Page::factory()->create();

    $this->actingAs($user)->get(route('admin.pages.edit', $page))->assertForbidden();
});

// Update
test('admin can update a page', function (): void {
    $admin = User::factory()->admin()->create();
    $page = Page::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.pages.update', $page), ['title' => 'Updated Title'])
        ->assertRedirect(route('admin.pages.index'));

    $this->assertDatabaseHas('pages', ['id' => $page->id, 'title' => 'Updated Title']);
});

test('unchecking publish sets published_at to null', function (): void {
    $admin = User::factory()->admin()->create();
    $page = Page::factory()->published()->create();

    $this->actingAs($admin)
        ->put(route('admin.pages.update', $page), ['title' => $page->title]);

    expect($page->fresh()->published_at)->toBeNull();
});

test('re-checking publish on already-published page preserves original date', function (): void {
    $admin = User::factory()->admin()->create();
    $original = now()->subDays(5);
    $page = Page::factory()->create(['published_at' => $original]);

    $this->actingAs($admin)
        ->put(route('admin.pages.update', $page), ['title' => $page->title, 'published_at' => '1']);

    expect($page->fresh()->published_at->timestamp)->toBe($original->timestamp);
});

test('update validates title is required', function (): void {
    $admin = User::factory()->admin()->create();
    $page = Page::factory()->create();

    $this->actingAs($admin)
        ->put(route('admin.pages.update', $page), [])
        ->assertSessionHasErrors('title');
});

// Destroy
test('admin can delete a page', function (): void {
    $admin = User::factory()->admin()->create();
    $page = Page::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.pages.destroy', $page))
        ->assertRedirect(route('admin.pages.index'));

    $this->assertModelMissing($page);
});

test('non-admin cannot delete a page', function (): void {
    $user = User::factory()->create();
    $page = Page::factory()->create();

    $this->actingAs($user)->delete(route('admin.pages.destroy', $page))->assertForbidden();
    $this->assertModelExists($page);
});
