<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\LinkVisit;
use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        $firstLink = ShortLink::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'short_code' => 'demo01',
            ],
            [
                'original_url' => 'https://laravel.com',
                'deleted_at' => null,
            ],
        );

        $secondLink = ShortLink::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'short_code' => 'demo02',
            ],
            [
                'original_url' => 'https://filamentphp.com',
                'deleted_at' => null,
            ],
        );

        $emptyLink = ShortLink::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'short_code' => 'demo03',
            ],
            [
                'original_url' => 'https://php.net',
                'deleted_at' => null,
            ],
        );

        LinkVisit::query()
            ->whereIn('short_link_id', [
                $firstLink->id,
                $secondLink->id,
                $emptyLink->id,
            ])
            ->delete();

        LinkVisit::factory()
            ->forShortLink($firstLink)
            ->count(5)
            ->create();

        LinkVisit::factory()
            ->forShortLink($secondLink)
            ->count(2)
            ->create();
    }
}
