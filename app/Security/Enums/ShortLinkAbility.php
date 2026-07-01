<?php

declare(strict_types=1);

namespace App\Security\Enums;

enum ShortLinkAbility: string
{
    case ViewAny = 'viewAny';
    case View = 'view';
    case Create = 'create';
    case Delete = 'delete';

    public function middleware(string $argument): string
    {
        return sprintf('can:%s,%s', $this->value, $argument);
    }
}
