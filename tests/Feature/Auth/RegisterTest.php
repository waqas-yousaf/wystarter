<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Role;

test('register page renders', function (): void {
    $this->get('/register')->assertOk()->assertViewIs('auth.register');
});

test('register page redirects when registration is disabled', function (): void {
    config(['app.user_reg' => false]);

    $this->get('/register')->assertRedirect(route('login'));
});

test('registration is blocked when disabled', function (): void {
    config(['app.user_reg' => false]);

    $this->post('/register', [
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertForbidden();
});

test('authenticated users are redirected away from register', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/register')->assertRedirect();
});

test('users can register with valid data', function (): void {
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

test('registration fails with duplicate email', function (): void {
    User::factory()->create(['email' => 'existing@example.com']);

    $this->post('/register', [
        'name' => 'Another User',
        'email' => 'existing@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHasErrors('email');
});

test('registration validates required fields', function (): void {
    $this->post('/register', [])->assertSessionHasErrors(['name', 'email', 'password']);
});

test('registration requires password confirmation', function (): void {
    $this->post('/register', [
        'name' => 'Test',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'different',
    ])->assertSessionHasErrors('password');
});
