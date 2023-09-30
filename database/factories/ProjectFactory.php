<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Arr;
use App\Enums\ProjectStatuses;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $status = Arr::random(ProjectStatuses::cases());
        return [
            'name' => $this->faker->unique()->words(nb: random_int(1, 2), asText: true),
            'status' => $status,
            'description' => $this->faker->sentences(nb: random_int(3, 6), asText: true),
            'created_at' => $date = $this->faker->dateTimeThisMonth(),
            'updated_at' => $date,
            'deleted_at' => $status === ProjectStatuses::CLOSED ? now() : null,
            'client_id' => Client::factory()->create(),
            'user_id' => User::factory()->create(),
        ];
    }
}
