<?php

declare(strict_types=1);

namespace Tests\Feature\ShortLinks;

use App\Models\LinkVisit;
use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ShortLinkStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_total_visits_on_own_short_link_page(): void
    {
        $user = User::factory()->create();
        $shortLink = ShortLink::factory()->create([
            'user_id' => $user->id,
        ]);
        LinkVisit::factory()->count(3)->create([
            'short_link_id' => $shortLink->id,
        ]);

        $response = $this->actingAs($user)->get(route('links.show', $shortLink));

        $response
            ->assertOk()
            ->assertSee('Всего переходов: 3');
    }

    public function test_user_can_see_visit_ip_addresses_for_own_short_link(): void
    {
        $user = User::factory()->create();
        $shortLink = ShortLink::factory()->create([
            'user_id' => $user->id,
        ]);
        LinkVisit::factory()->create([
            'short_link_id' => $shortLink->id,
            'ip_address' => '203.0.113.10',
        ]);

        $response = $this->actingAs($user)->get(route('links.show', $shortLink));

        $response
            ->assertOk()
            ->assertSee('203.0.113.10');
    }

    public function test_user_can_see_visit_date_for_own_short_link(): void
    {
        $user = User::factory()->create();
        $shortLink = ShortLink::factory()->create([
            'user_id' => $user->id,
        ]);
        $visit = LinkVisit::factory()->create([
            'short_link_id' => $shortLink->id,
            'created_at' => now()->setDate(2026, 7, 1)->setTime(12, 30),
        ]);

        $response = $this->actingAs($user)->get(route('links.show', $shortLink));

        $response
            ->assertOk()
            ->assertSee($visit->created_at?->format('d.m.Y H:i'));
    }

    public function test_empty_statistics_message_is_shown_when_there_are_no_visits(): void
    {
        $user = User::factory()->create();
        $shortLink = ShortLink::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('links.show', $shortLink));

        $response
            ->assertOk()
            ->assertSee('Переходов пока нет.');
    }

    public function test_user_can_not_see_other_users_short_link_statistics(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherShortLink = ShortLink::factory()->create([
            'user_id' => $otherUser->id,
        ]);
        LinkVisit::factory()->create([
            'short_link_id' => $otherShortLink->id,
        ]);

        $response = $this->actingAs($user)->get(route('links.show', $otherShortLink));

        $response->assertForbidden();
    }

    public function test_links_index_shows_click_count(): void
    {
        $user = User::factory()->create();
        $shortLink = ShortLink::factory()->create([
            'user_id' => $user->id,
            'original_url' => 'https://example.com/click-count',
        ]);
        LinkVisit::factory()->count(4)->create([
            'short_link_id' => $shortLink->id,
        ]);

        $response = $this->actingAs($user)->get(route('links.index'));

        $response
            ->assertOk()
            ->assertSee('Клики')
            ->assertSee('https://example.com/click-count')
            ->assertSee('4');
    }
}
