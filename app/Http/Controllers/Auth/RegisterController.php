<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

final class RegisterController
{
    public function create(): View|RedirectResponse
    {
        if (! config('app.user_reg')) {
            return redirect()->route('login')->with('error', 'Registration is currently disabled.');
        }

        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! config('app.user_reg')) {
            abort(403, 'Registration is currently disabled.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        $user->assignRole('user');

        Auth::login($user);

        return redirect()->route('admin.main');
    }
}
