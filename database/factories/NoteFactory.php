<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'content' => $this->faker->paragraphs(3, true),
            'is_pinned' => $this->faker->boolean(20), // 20% chance of being pinned
            'color' => $this->faker->safeHexColor(),
            'user_id' => User::first()?->id ?? User::factory(), // Gunakan user pertama atau buat baru jika tidak ada
        ];
    }
}
