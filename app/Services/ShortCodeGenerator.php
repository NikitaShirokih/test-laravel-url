<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ShortLink;
use Illuminate\Support\Str;

final class ShortCodeGenerator
{
    private const CODE_LENGTH = 6;

    public function generate(): string
    {
        do {
            $code = Str::random(self::CODE_LENGTH);
        } while (ShortLink::query()->where('short_code', $code)->exists());

        return $code;
    }
}
