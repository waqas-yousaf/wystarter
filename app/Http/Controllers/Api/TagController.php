<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class TagController
{
    public function index(): AnonymousResourceCollection
    {
        return TagResource::collection(Tag::orderBy('name')->get());
    }
}
