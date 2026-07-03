<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Models\LinkVisit;
use App\Models\ShortLink;
use App\Models\User;

trait ShortLinkFixtures
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function createShortLinkForUser(User $user, array $attributes = []): ShortLink
    {
        return ShortLink::factory()
            ->forUser($user)
            ->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $shortLinkAttributes
     */
    protected function createVisitedShortLinkForUser(
        User $user,
        int $visitsCount = 1,
        array $shortLinkAttributes = [],
    ): ShortLink {
        $shortLink = $this->createShortLinkForUser($user, $shortLinkAttributes);

        LinkVisit::factory()
            ->forShortLink($shortLink)
            ->count($visitsCount)
            ->create();

        return $shortLink;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function createDeletedShortLinkForUser(User $user, array $attributes = []): ShortLink
    {
        return ShortLink::factory()
            ->forUser($user)
            ->deleted()
            ->create($attributes);
    }

    /**
     * @return array{0: User, 1: User, 2: ShortLink, 3: ShortLink}
     */
    protected function createUsersWithOwnLinks(): array
    {
        $user = $this->createUser();
        $otherUser = $this->createUser();

        return [
            $user,
            $otherUser,
            $this->createShortLinkForUser($user),
            $this->createShortLinkForUser($otherUser),
        ];
    }
}
