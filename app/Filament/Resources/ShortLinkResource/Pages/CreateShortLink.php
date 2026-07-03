<?php

declare(strict_types=1);

namespace App\Filament\Resources\ShortLinkResource\Pages;

use App\Filament\Resources\ShortLinkResource;
use App\Models\ShortLink;
use App\Models\User;
use App\Services\ShortLinkService;
use Filament\Resources\Pages\CreateRecord;

final class CreateShortLink extends CreateRecord
{
    protected static string $resource = ShortLinkResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): ShortLink
    {
        /** @var User $user */
        $user = request()->user();

        return app(ShortLinkService::class)->create(
            $user,
            (string) $data['original_url'],
        );
    }
}
