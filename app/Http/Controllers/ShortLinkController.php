<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortLinkRequest;
use App\Models\ShortLink;
use App\Models\User;
use App\Services\ShortLinkService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ShortLinkController extends Controller
{
    public function index(Request $request, ShortLinkService $shortLinkService): View
    {
        /** @var User $user */
        $user = $request->user();

        return view('links.index', [
            'shortLinks' => $shortLinkService->paginateForUser($user),
        ]);
    }

    public function create(): View
    {
        return view('links.create');
    }

    public function store(
        StoreShortLinkRequest $request,
        ShortLinkService $shortLinkService,
    ): RedirectResponse {
        /** @var User $user */
        $user = $request->user();

        $shortLink = $shortLinkService->create(
            $user,
            (string) $request->validated('original_url'),
        );

        return redirect()
            ->route('links.show', $shortLink)
            ->with('status', 'Короткая ссылка создана.');
    }

    public function show(ShortLink $link, ShortLinkService $shortLinkService): View
    {
        return view('links.show', [
            'shortLink' => $link,
            'visits' => $shortLinkService->paginateVisits($link),
            'visitsCount' => $shortLinkService->countVisits($link),
        ]);
    }

    public function destroy(ShortLink $link, ShortLinkService $shortLinkService): RedirectResponse
    {
        $shortLinkService->delete($link);

        return redirect()
            ->route('links.index')
            ->with('status', 'Короткая ссылка удалена.');
    }
}
