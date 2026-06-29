<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Page;
use App\Models\User;

final class PagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Page $page): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Page $page): bool
    {
        return $user->hasRole('admin');
    }
}
