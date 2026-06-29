<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;

afterEach(function (): void {
    RateLimiter::clear('');
});

test('login is rate limited after 10 failed attempts', function (): void {
    User::factory()->create(['email' => 'throttle@example.com']);

    foreach (range(1, 10) as $i) {
        $this->post('/login', [
            'email' => 'throttle@example.com',
            'password' => 'wrong-password',
        ]);
    }

    $this->post('/login', [
        'email' => 'throttle@example.com',
        'password' => 'wrong-password',
    ])->assertStatus(429);
});

test('register is rate limited after 5 attempts', function (): void {
    foreach (range(1, 5) as $i) {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => "user{$i}@example.com",
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
    }

    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'extra@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertStatus(429);
});
