<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;

final class DashboardController
{
    public function __invoke(): View
    {
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::role('admin')->count(),
            'recent_users' => User::with('roles')->latest()->limit(5)->get(),
        ];

        return view('admin.main', compact('stats'));
    }
}
