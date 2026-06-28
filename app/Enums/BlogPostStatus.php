<?php

declare(strict_types=1);

namespace App\Enums;

enum BlogPostStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::Archived => 'Archived',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'bg-yellow-50 text-yellow-700',
            self::Published => 'bg-green-50 text-green-700',
            self::Archived => 'bg-gray-100 text-gray-600',
        };
    }
}
