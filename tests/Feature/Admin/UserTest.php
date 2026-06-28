<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Role;

test('admin can view users list', function (): void {
    $admin = User::factory()->admin()->create();
    User::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertViewIs('admin.users.index');
});

test('non-admin cannot view users list', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
});

test('admin can assign a role to a user', function (): void {
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->patch(route('admin.users.update', $user), ['role' => 'user'])
        ->assertRedirect();

    expect($user->fresh()->hasRole('user'))->toBeTrue();
});

test('admin can promote a user to admin', function (): void {
    Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->patch(route('admin.users.update', $user), ['role' => 'admin'])
        ->assertRedirect();

    expect($user->fresh()->hasRole('admin'))->toBeTrue();
});

test('role update rejects invalid role', function (): void {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->patch(route('admin.users.update', $user), ['role' => 'superuser'])
        ->assertSessionHasErrors('role');
});

test('admin can delete a user', function (): void {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $user))
        ->assertRedirect();

    $this->assertModelMissing($user);
});

test('admin cannot delete themselves', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $admin))
        ->assertRedirect();

    $this->assertModelExists($admin);
});
