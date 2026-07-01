<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortLinkRequest;
use App\Models\ShortLink;
use App\Models\User;
use App\Services\ShortLinkService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ShortLinkController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', ShortLink::class);

        /** @var User $user */
        $user = $request->user();

        $shortLinks = $user->shortLinks()
            ->latest()
            ->paginate(10);

        return view('links.index', [
            'shortLinks' => $shortLinks,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', ShortLink::class);

        return view('links.create');
    }

    public function store(
        StoreShortLinkRequest $request,
        ShortLinkService $shortLinkService,
    ): RedirectResponse {
        $this->authorize('create', ShortLink::class);

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

    public function show(ShortLink $link): View
    {
        $this->authorize('view', $link);

        return view('links.show', [
            'shortLink' => $link,
        ]);
    }

    public function destroy(ShortLink $link): RedirectResponse
    {
        $this->authorize('delete', $link);

        $link->delete();

        return redirect()
            ->route('links.index')
            ->with('status', 'Короткая ссылка удалена.');
    }
}
