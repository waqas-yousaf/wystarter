<?php

declare(strict_types=1);

use App\Models\User;
use App\Policies\UserPolicy;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
});

test('admin can view any user', function (): void {
    $admin = User::factory()->admin()->create();
    expect((new UserPolicy)->viewAny($admin))->toBeTrue();
});

test('non-admin cannot view any user', function (): void {
    $user = User::factory()->create();
    expect((new UserPolicy)->viewAny($user))->toBeFalse();
});

test('admin can update any user', function (): void {
    $admin = User::factory()->admin()->create();
    $other = User::factory()->create();
    expect((new UserPolicy)->update($admin, $other))->toBeTrue();
});

test('non-admin cannot update users', function (): void {
    $user = User::factory()->create();
    $other = User::factory()->create();
    expect((new UserPolicy)->update($user, $other))->toBeFalse();
});

test('admin can destroy another user', function (): void {
    $admin = User::factory()->admin()->create();
    $other = User::factory()->create();
    expect((new UserPolicy)->destroy($admin, $other))->toBeTrue();
});

test('admin can destroy themselves at policy level (controller blocks it)', function (): void {
    $admin = User::factory()->admin()->create();
    expect((new UserPolicy)->destroy($admin, $admin))->toBeTrue();
});

test('non-admin cannot destroy users', function (): void {
    $user = User::factory()->create();
    $other = User::factory()->create();
    expect((new UserPolicy)->destroy($user, $other))->toBeFalse();
});
