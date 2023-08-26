<?php

namespace Database\Factories;

use App\Models\Project;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DailyReport>
 */
class DailyReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $date = CarbonImmutable::parse($this->faker->dateTimeThisMonth());
        $date->hour(8);
        $date->minute(0);
        $date->second(0);
        $project = Project::query()
            ->inRandomOrder()->take(1)
            ->with('tasks:id,project_id')
            ->first();
        return [
            'units_completed' => $this->faker->numberBetween(10, 6000),
            'reported_at' => $date->format('Y-m-d'),
            'started_at' => $date->format('H:i:s'),
            'ended_at' => $date->addMinutes($this->faker->numberBetween(10, 480))->format('H:i:s'),
            //            'project_id' => $project->id ?? Project::factory()->create(),
            //            'user_id' => User::query()->inRandomOrder()->take(1)->first('id'),
            //            'task_id' => $project->tasks->random()->id ?? Task::factory()->create(),
        ];
    }
}
