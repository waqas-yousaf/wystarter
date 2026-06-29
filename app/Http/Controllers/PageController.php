<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;

final class PageController
{
    public function show(Page $page): View
    {
        abort_if($page->published_at === null, 404);

        return view('pages.show', compact('page'));
    }
}
