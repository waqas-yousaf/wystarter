<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

final class PageController
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize('viewAny', Page::class);

        $pages = Page::latest()->paginate(15);

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        $this->authorize('create', Page::class);

        return view('admin.pages.create');
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Page::create([
            'title' => $validated['title'],
            'slug' => ! empty($validated['slug']) ? $validated['slug'] : Page::generateSlug($validated['title']),
            'body' => $validated['body'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'published_at' => ! empty($validated['published_at']) ? now() : null,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page created.');
    }

    public function edit(Page $page): View
    {
        $this->authorize('update', $page);

        return view('admin.pages.edit', compact('page'));
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        $validated = $request->validated();

        $page->update([
            'title' => $validated['title'],
            'slug' => ! empty($validated['slug']) ? $validated['slug'] : $page->slug,
            'body' => $validated['body'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'published_at' => ! empty($validated['published_at'])
                ? ($page->published_at ?? now())
                : null,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $this->authorize('delete', $page);

        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted.');
    }
}
