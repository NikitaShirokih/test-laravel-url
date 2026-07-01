<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ShortLink;

final class ShortLinkRedirectService
{
    public function resolveAndTrack(string $code, string $ipAddress): ShortLink
    {
        $shortLink = ShortLink::query()
            ->where('short_code', $code)
            ->firstOrFail();

        $shortLink->visits()->create([
            'ip_address' => $ipAddress,
        ]);

        return $shortLink;
    }
}
