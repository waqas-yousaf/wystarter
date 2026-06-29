<?php

declare(strict_types=1);

use App\Models\User;

test('guests cannot access admin.main', function (): void {
    $this->get(route('admin.main'))->assertRedirect('/login');
});

test('non-admin users cannot access admin.main', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.main'))->assertForbidden();
});

test('admin users can access dashboard', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.main'))
        ->assertOk()
        ->assertViewIs('admin.main');
});

test('dashboard shows correct user stats', function (): void {
    $admin = User::factory()->admin()->create();
    User::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('admin.main'));

    $response->assertViewHas('stats', fn (array $stats): bool => $stats['total_users'] === 4
        && $stats['admin_users'] === 1
    );
});
