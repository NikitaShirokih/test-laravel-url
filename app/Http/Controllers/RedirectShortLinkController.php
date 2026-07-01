<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class RedirectShortLinkController extends Controller
{
    public function __invoke(Request $request, string $code): RedirectResponse
    {
        $shortLink = ShortLink::query()
            ->where('short_code', $code)
            ->firstOrFail();

        $shortLink->visits()->create([
            'ip_address' => $request->ip(),
        ]);

        return redirect()->away($shortLink->original_url);
    }
}
