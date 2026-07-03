<?php

declare(strict_types=1);

namespace Tests\Feature\Filament;

use App\Models\User;
use Filament\Pages\Auth\Login;
use Filament\Pages\Auth\Register;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

final class FilamentAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_opening_cabinet_is_redirected_to_filament_login(): void
    {
        $response = $this->get('/cabinet');

        $response->assertRedirect('/cabinet/login');
    }

    public function test_guest_can_open_filament_login(): void
    {
        $response = $this->get('/cabinet/login');

        $response->assertOk();
    }

    public function test_guest_can_open_filament_register(): void
    {
        $response = $this->get('/cabinet/register');

        $response->assertOk();
    }

    public function test_user_can_register_through_filament_registration(): void
    {
        Livewire::test(Register::class)
            ->fillForm([
                'name' => 'Filament User',
                'email' => 'filament@example.com',
                'password' => 'password',
                'passwordConfirmation' => 'password',
            ])
            ->call('register')
            ->assertHasNoFormErrors();

        $user = User::query()
            ->where('email', 'filament@example.com')
            ->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_user_can_login_through_filament_login(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('password'),
        ]);

        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'login@example.com',
                'password' => 'password',
                'remember' => false,
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors();

        $this->assertAuthenticatedAs($user);
    }

    public function test_old_login_route_does_not_exist(): void
    {
        $this->get('/login')->assertNotFound();
    }

    public function test_old_register_route_does_not_exist(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_old_dashboard_route_does_not_exist(): void
    {
        $this->get('/dashboard')->assertNotFound();
    }

    public function test_old_links_routes_do_not_exist(): void
    {
        $this->get('/links')->assertNotFound();
        $this->get('/links/create')->assertNotFound();
    }

    public function test_old_admin_route_does_not_exist(): void
    {
        $this->get('/admin')->assertNotFound();
    }
}
