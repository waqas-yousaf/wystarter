<?php

declare(strict_types=1);

use App\Models\User;

test('login page renders', function (): void {
    $this->get('/login')->assertOk()->assertViewIs('auth.login');
});

test('guests are redirected to login from admin', function (): void {
    $this->get('/admin')->assertRedirect('/login');
});

test('authenticated users are redirected away from login', function (): void {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)->get('/login')->assertRedirect();
});

test('users can login with valid credentials', function (): void {
    $user = User::factory()->admin()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect(route('admin.main'));

    $this->assertAuthenticatedAs($user);
});

test('login fails with invalid credentials', function (): void {
    User::factory()->create();

    $this->post('/login', [
        'email' => 'user@example.com',
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('login validates required fields', function (): void {
    $this->post('/login', [])->assertSessionHasErrors(['email', 'password']);
});
