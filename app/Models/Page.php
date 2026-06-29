<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property-read string $id
 * @property-read string $title
 * @property-read string $slug
 * @property-read string|null $body
 * @property-read string|null $meta_title
 * @property-read string|null $meta_description
 * @property-read CarbonInterface|null $published_at
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    use HasUuids;

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = self::where('slug', 'like', "{$slug}%")->count();

        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }

    /** @return array<string, mixed> */
    public function casts(): array
    {
        return [
            'id' => 'string',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
