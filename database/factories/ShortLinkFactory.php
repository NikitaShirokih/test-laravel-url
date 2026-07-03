<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ShortLink>
 */
final class ShortLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'original_url' => fake()->url(),
            'short_code' => Str::random(6),
        ];
    }

    public function forUser(User $user): self
    {
        return $this->state([
            'user_id' => $user->id,
        ]);
    }

    public function deleted(): self
    {
        return $this->state([
            'deleted_at' => now(),
        ]);
    }
}
