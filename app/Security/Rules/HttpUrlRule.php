<?php

declare(strict_types=1);

namespace App\Security\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class HttpUrlRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $scheme = parse_url((string) $value, PHP_URL_SCHEME);

        if (! in_array($scheme, ['http', 'https'], true)) {
            $fail('Разрешены только HTTP и HTTPS ссылки.');
        }
    }
}
