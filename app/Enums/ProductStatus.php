<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Draft = 'draft';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Draft => 'Draft',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'text-green-700 bg-green-50 ring-green-600/20',
            self::Inactive => 'text-red-700 bg-red-50 ring-red-600/20',
            self::Draft => 'text-yellow-700 bg-yellow-50 ring-yellow-600/20',
        };
    }
}
