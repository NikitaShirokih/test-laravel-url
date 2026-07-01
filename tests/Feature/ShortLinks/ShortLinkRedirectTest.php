<?php

declare(strict_types=1);

namespace Tests\Feature\ShortLinks;

use App\Models\LinkVisit;
use App\Models\ShortLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ShortLinkRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_be_redirected_by_short_link(): void
    {
        $shortLink = ShortLink::factory()->create([
            'original_url' => 'https://example.com/page',
            'short_code' => 'abc123',
        ]);

        $response = $this->get('/'.$shortLink->short_code);

        $response->assertRedirect('https://example.com/page');
    }

    public function test_visit_is_tracked_when_short_link_is_opened(): void
    {
        $shortLink = ShortLink::factory()->create([
            'short_code' => 'abc123',
        ]);

        $this->get('/'.$shortLink->short_code);

        $visit = LinkVisit::query()->firstOrFail();

        $this->assertSame($shortLink->id, $visit->short_link_id);
        $this->assertNotEmpty($visit->ip_address);
        $this->assertDatabaseHas('link_visits', [
            'short_link_id' => $shortLink->id,
            'ip_address' => $visit->ip_address,
        ]);
    }

    public function test_unknown_short_code_returns_not_found(): void
    {
        $this->get('/unknown-code')
            ->assertNotFound();
    }

    public function test_deleted_short_link_does_not_redirect(): void
    {
        $shortLink = ShortLink::factory()->create([
            'short_code' => 'abc123',
        ]);

        $shortLink->delete();

        $this->get('/'.$shortLink->short_code)
            ->assertNotFound();

        $this->assertDatabaseMissing('link_visits', [
            'short_link_id' => $shortLink->id,
        ]);
    }

    public function test_redirect_does_not_require_authentication(): void
    {
        $shortLink = ShortLink::factory()->create([
            'original_url' => 'https://example.com/public-page',
            'short_code' => 'pub123',
        ]);

        $response = $this->get('/'.$shortLink->short_code);

        $response->assertRedirect('https://example.com/public-page');
    }
}
