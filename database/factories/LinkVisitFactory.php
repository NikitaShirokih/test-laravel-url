<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LinkVisit;
use App\Models\ShortLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LinkVisit>
 */
final class LinkVisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'short_link_id' => ShortLink::factory(),
            'ip_address' => fake()->ipv4(),
        ];
    }
}
