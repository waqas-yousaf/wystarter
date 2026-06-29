<?php

declare(strict_types=1);

use App\Models\Page;

test('published page is accessible at its slug', function (): void {
    $page = Page::factory()->published()->create();

    $this->get('/site/'.$page->slug)->assertOk()->assertSee($page->title);
});

test('draft page returns 404', function (): void {
    $page = Page::factory()->draft()->create();

    $this->get('/site/'.$page->slug)->assertNotFound();
});

test('unknown slug returns 404', function (): void {
    $this->get('/site/this-page-does-not-exist')->assertNotFound();
});

test('published page response contains body content', function (): void {
    $page = Page::factory()->published()->create(['body' => '<p>Hello World</p>']);

    $this->get('/site/'.$page->slug)->assertSee('Hello World', false);
});

test('published page links to main css stylesheet', function (): void {
    $page = Page::factory()->published()->create();

    $this->get('/site/'.$page->slug)->assertSee('/build/css/main.css', false);
});

test('published page does not contain vite script tags', function (): void {
    $page = Page::factory()->published()->create();

    $response = $this->get('/site/'.$page->slug);
    $response->assertDontSee('@vite', false);
    $response->assertDontSee('app.js', false);
});
