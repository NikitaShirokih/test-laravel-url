<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function paginateForUser(User $user): LengthAwarePaginator
    {
        return $user->shortLinks()
            ->withCount('visits')
            ->latest()
            ->paginate(10);
    }

    public function paginateVisits(ShortLink $shortLink): LengthAwarePaginator
    {
        return $shortLink->visits()
            ->latest()
            ->paginate(15);
    }

    public function countVisits(ShortLink $shortLink): int
    {
        return $shortLink->visits()->count();
    }

    public function delete(ShortLink $shortLink): void
    {
        $shortLink->delete();
    }
}
