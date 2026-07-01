<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\ShortLinkResource\Pages\CreateShortLink;
use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class ShortLinkResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_open_filament_panel(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
    }

    public function test_user_sees_only_own_short_links_in_filament_resource(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownShortLink = ShortLink::factory()->create([
            'user_id' => $user->id,
            'original_url' => 'https://example.com/filament-own-link',
        ]);

        $otherShortLink = ShortLink::factory()->create([
            'user_id' => $otherUser->id,
            'original_url' => 'https://example.com/filament-other-link',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('filament.admin.resources.short-links.index'));

        $response
            ->assertOk()
            ->assertSee($ownShortLink->original_url)
            ->assertDontSee($otherShortLink->original_url);
    }

    public function test_user_can_create_short_link_through_filament_resource(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(CreateShortLink::class)
            ->fillForm([
                'original_url' => 'https://example.com/filament-create',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $shortLink = ShortLink::query()
            ->where('original_url', 'https://example.com/filament-create')
            ->firstOrFail();

        $this->assertSame($user->id, $shortLink->user_id);
        $this->assertNotEmpty($shortLink->short_code);
    }
}
