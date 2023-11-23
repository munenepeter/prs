<?php

namespace Database\Factories;

use Illuminate\Support\Arr;
use App\Enums\TaskUnitTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(nb: 1, asText: true),
            'unit_type' => 1,
            'target' => $this->faker->numberBetween(0, 400),
        ];
    }
}
