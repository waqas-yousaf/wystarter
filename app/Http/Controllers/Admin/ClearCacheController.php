<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

final class ClearCacheController
{
    public function __invoke(): RedirectResponse
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'All caches cleared successfully.');
    }
}
