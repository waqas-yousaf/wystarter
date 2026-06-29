<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Shipped => 'Shipped',
            self::Delivered => 'Delivered',
            self::Cancelled => 'Cancelled',
            self::Refunded => 'Refunded',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'text-yellow-700 bg-yellow-50 ring-yellow-600/20',
            self::Processing => 'text-blue-700 bg-blue-50 ring-blue-600/20',
            self::Shipped => 'text-indigo-700 bg-indigo-50 ring-indigo-600/20',
            self::Delivered => 'text-green-700 bg-green-50 ring-green-600/20',
            self::Cancelled => 'text-red-700 bg-red-50 ring-red-600/20',
            self::Refunded => 'text-gray-700 bg-gray-50 ring-gray-600/20',
        };
    }
}
