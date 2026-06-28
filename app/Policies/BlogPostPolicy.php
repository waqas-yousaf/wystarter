<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;

final class BlogPostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'author']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'author']);
    }

    public function update(User $user, BlogPost $blogPost): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('author') && $blogPost->author_id === $user->id;
    }

    public function delete(User $user, BlogPost $blogPost): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('author') && $blogPost->author_id === $user->id;
    }
}
