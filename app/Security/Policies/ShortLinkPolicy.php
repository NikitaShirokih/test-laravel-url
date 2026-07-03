<?php

declare(strict_types=1);

namespace App\Security\Policies;

use App\Models\ShortLink;
use App\Models\User;

final class ShortLinkPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ShortLink $shortLink): bool
    {
        return $this->belongsToUser($user, $shortLink);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, ShortLink $shortLink): bool
    {
        return $this->belongsToUser($user, $shortLink);
    }

    private function belongsToUser(User $user, ShortLink $shortLink): bool
    {
        return $shortLink->user_id === $user->id;
    }
}
