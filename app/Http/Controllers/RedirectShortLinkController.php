<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ShortLinkRedirectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class RedirectShortLinkController extends Controller
{
    public function __invoke(
        Request $request,
        string $code,
        ShortLinkRedirectService $redirectService,
    ): RedirectResponse {
        $shortLink = $redirectService->resolveAndTrack(
            $code,
            (string) $request->ip(),
        );

        return redirect()->away($shortLink->original_url);
    }
}
