<?php

declare(strict_types=1);

use App\Models\User;

test('guests cannot access admin dashboard', function (): void {
    $this->get(route('admin.dashboard'))->assertRedirect('/login');
});

test('non-admin users cannot access admin dashboard', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
});

test('admin users can access dashboard', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertViewIs('admin.dashboard');
});

test('dashboard shows correct user stats', function (): void {
    $admin = User::factory()->admin()->create();
    User::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertViewHas('stats', fn (array $stats): bool =>
        $stats['total_users'] === 4
        && $stats['admin_users'] === 1
    );
});
