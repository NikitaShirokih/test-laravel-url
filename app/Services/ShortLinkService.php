<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ShortLink;
use App\Models\User;

final readonly class ShortLinkService
{
    public function __construct(
        private ShortCodeGenerator $shortCodeGenerator,
    ) {}

    public function create(User $user, string $originalUrl): ShortLink
    {
        return ShortLink::query()->create([
            'user_id' => $user->id,
            'original_url' => $originalUrl,
            'short_code' => $this->shortCodeGenerator->generate(),
        ]);
    }
}
