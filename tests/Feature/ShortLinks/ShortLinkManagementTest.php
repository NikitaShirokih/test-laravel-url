<?php

declare(strict_types=1);

namespace Tests\Feature\ShortLinks;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ShortLinkManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_not_open_links_index(): void
    {
        $response = $this->get('/links');

        $response->assertRedirect(route('login', absolute: false));
    }

    public function test_authenticated_user_can_open_links_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/links');

        $response->assertOk();
    }

    public function test_authenticated_user_can_create_short_link(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/links', [
            'original_url' => 'https://example.com/articles/laravel',
        ]);

        $shortLink = ShortLink::query()->firstOrFail();

        $response->assertRedirect(route('links.show', $shortLink, absolute: false));
        $this->assertSame($user->id, $shortLink->user_id);
        $this->assertSame('https://example.com/articles/laravel', $shortLink->original_url);
        $this->assertNotEmpty($shortLink->short_code);
    }

    public function test_short_link_can_not_be_created_with_invalid_scheme(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/links', [
            'original_url' => 'javascript:alert(1)',
        ]);

        $response->assertSessionHasErrors('original_url');
        $this->assertDatabaseCount('short_links', 0);
    }

    public function test_user_sees_only_own_short_links_in_index(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownShortLink = ShortLink::factory()->create([
            'user_id' => $user->id,
            'original_url' => 'https://example.com/own-link',
        ]);

        $otherShortLink = ShortLink::factory()->create([
            'user_id' => $otherUser->id,
            'original_url' => 'https://example.com/other-link',
        ]);

        $response = $this->actingAs($user)->get('/links');

        $response
            ->assertOk()
            ->assertSee($ownShortLink->original_url)
            ->assertDontSee($otherShortLink->original_url);
    }

    public function test_user_can_not_open_other_users_short_link(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherShortLink = ShortLink::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($user)->get(route('links.show', $otherShortLink));

        $response->assertForbidden();
    }

    public function test_user_can_delete_own_short_link(): void
    {
        $user = User::factory()->create();
        $shortLink = ShortLink::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('links.destroy', $shortLink));

        $response->assertRedirect(route('links.index', absolute: false));
        $this->assertSoftDeleted('short_links', [
            'id' => $shortLink->id,
        ]);
    }

    public function test_user_can_not_delete_other_users_short_link(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherShortLink = ShortLink::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($user)->delete(route('links.destroy', $otherShortLink));

        $response->assertForbidden();
        $this->assertNotSoftDeleted('short_links', [
            'id' => $otherShortLink->id,
        ]);
    }
}
