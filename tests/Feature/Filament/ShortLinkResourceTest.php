<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Filament\Resources\ShortLinkResource\Pages\CreateShortLink;
use App\Filament\Resources\ShortLinkResource\Pages\ViewShortLink;
use App\Filament\Resources\ShortLinkResource\RelationManagers\LinkVisitsRelationManager;
use App\Models\LinkVisit;
use App\Models\ShortLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Support\ShortLinkFixtures;
use Tests\TestCase;

final class ShortLinkResourceTest extends TestCase
{
    use RefreshDatabase;
    use ShortLinkFixtures;

    public function test_guest_is_redirected_to_filament_login(): void
    {
        $response = $this->get('/cabinet');

        $response->assertRedirect('/cabinet/login');
    }

    public function test_authenticated_user_can_open_filament_panel(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/cabinet');

        $response->assertOk();
    }

    public function test_user_can_open_own_short_links_list_in_filament(): void
    {
        $user = $this->createUser();

        $response = $this
            ->actingAs($user)
            ->get(route('filament.cabinet.resources.short-links.index'));

        $response->assertOk();
    }

    public function test_user_sees_only_own_short_links_in_filament_resource(): void
    {
        $user = $this->createUser();
        $otherUser = $this->createUser();

        $ownShortLink = $this->createShortLinkForUser($user, [
            'original_url' => 'https://example.com/filament-own-link',
        ]);

        $otherShortLink = $this->createShortLinkForUser($otherUser, [
            'original_url' => 'https://example.com/filament-other-link',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('filament.cabinet.resources.short-links.index'));

        $response
            ->assertOk()
            ->assertSee($ownShortLink->original_url)
            ->assertDontSee($otherShortLink->original_url);
    }

    public function test_user_can_create_short_link_through_filament_resource(): void
    {
        $user = $this->createUser();

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

    public function test_user_can_not_create_javascript_url_through_filament_resource(): void
    {
        $user = $this->createUser();

        $this->actingAs($user);

        Livewire::test(CreateShortLink::class)
            ->fillForm([
                'original_url' => 'javascript:alert(1)',
            ])
            ->call('create')
            ->assertHasFormErrors(['original_url']);

        $this->assertDatabaseCount('short_links', 0);
    }

    public function test_user_can_not_create_ftp_url_through_filament_resource(): void
    {
        $user = $this->createUser();

        $this->actingAs($user);

        Livewire::test(CreateShortLink::class)
            ->fillForm([
                'original_url' => 'ftp://example.com/file',
            ])
            ->call('create')
            ->assertHasFormErrors(['original_url']);

        $this->assertDatabaseCount('short_links', 0);
    }

    public function test_user_can_not_open_other_users_short_link_directly_in_filament(): void
    {
        [$user, , , $otherShortLink] = $this->createUsersWithOwnLinks();

        $response = $this
            ->actingAs($user)
            ->get(route('filament.cabinet.resources.short-links.view', $otherShortLink));

        $response->assertNotFound();
    }

    public function test_filament_index_shows_visit_count(): void
    {
        $user = $this->createUser();
        $shortLink = $this->createVisitedShortLinkForUser($user, visitsCount: 3, shortLinkAttributes: [
            'original_url' => 'https://example.com/filament-clicks',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('filament.cabinet.resources.short-links.index'));

        $response
            ->assertOk()
            ->assertSee($shortLink->original_url)
            ->assertSee('3');
    }

    public function test_filament_view_page_shows_link_visits(): void
    {
        $user = $this->createUser();
        $shortLink = $this->createShortLinkForUser($user, [
            'original_url' => 'https://example.com/filament-stats',
        ]);

        $visit = LinkVisit::factory()->forShortLink($shortLink)->create([
            'ip_address' => '203.0.113.42',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('filament.cabinet.resources.short-links.view', $shortLink));

        $response
            ->assertOk()
            ->assertSee($shortLink->original_url);

        Livewire::test(LinkVisitsRelationManager::class, [
            'ownerRecord' => $shortLink,
            'pageClass' => ViewShortLink::class,
        ])
            ->assertCanSeeTableRecords([$visit])
            ->assertSee('203.0.113.42');
    }
}
