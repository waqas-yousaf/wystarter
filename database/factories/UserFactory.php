<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): self
    {
        return $this->state(fn (): array => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): self
    {
        return $this->afterCreating(function (User $user): void {
            Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
            $user->assignRole('admin');
        });
    }
}
