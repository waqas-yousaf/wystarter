<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => config('app.admin_email')],
            [
                'name' => 'Admin',
                'password' => Hash::make(config('app.admin_password')),
            ],
        );

        $admin->syncRoles(['admin']);
    }
}
