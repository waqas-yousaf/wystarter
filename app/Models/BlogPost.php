<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BlogPostStatus;
use Carbon\CarbonInterface;
use Database\Factories\BlogPostFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * @property-read string $id
 * @property-read string $title
 * @property-read string $slug
 * @property-read string|null $content
 * @property-read string|null $excerpt
 * @property-read string|null $featured_image
 * @property-read BlogPostStatus $status
 * @property-read string $author_id
 * @property-read CarbonInterface|null $published_at
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class BlogPost extends Model
{
    /** @use HasFactory<BlogPostFactory> */
    use HasFactory;

    use HasUuids;

    /** @return array<string, mixed> */
    public function casts(): array
    {
        return [
            'id' => 'string',
            'author_id' => 'string',
            'status' => BlogPostStatus::class,
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /** @return BelongsToMany<Tag, $this> */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', "{$slug}%")->count();

        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }
}
