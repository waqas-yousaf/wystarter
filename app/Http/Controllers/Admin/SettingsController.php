<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class SettingsController
{
    public function index(): View
    {
        $googleAnalyticsCode = Setting::get('google_analytics_code');
        $hasLogo = file_exists(public_path('logo.png'));
        $hasLogoLight = file_exists(public_path('logo-light.png'));
        $hasFavicon = file_exists(public_path('favicon.png'));

        return view('admin.settings.index', compact(
            'googleAnalyticsCode',
            'hasLogo',
            'hasLogoLight',
            'hasFavicon',
        ));
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Setting::set('google_analytics_code', $validated['google_analytics_code'] ?? null);

        if ($request->hasFile('logo')) {
            $request->file('logo')->move(public_path('images'), 'logo.png');
        }

        if ($request->hasFile('logo_light')) {
            $request->file('logo_light')->move(public_path('images'), 'logo-light.png');
        }

        if ($request->hasFile('favicon')) {
            $request->file('favicon')->move(public_path('images'), 'favicon.png');
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings saved.');
    }
}
