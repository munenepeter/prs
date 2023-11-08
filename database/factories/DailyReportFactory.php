<?php

namespace Database\Factories;

use Illuminate\Support\Arr;
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

        $targets = [
            "Above Target by ". $this->faker->numberBetween(0, 900) . "%",
            "Below Target by ". $this->faker->numberBetween(0, 700) . "%",
            "On Target",
            "Pending"
        ];

        return [
            'units_completed' => $this->faker->numberBetween(10, 6000),
            'reported_at' => $date->format('Y-m-d'),
            'started_at' => $date->format('H:i:s'),
            'ended_at' => $date->addMinutes($this->faker->numberBetween(10, 480))->format('H:i:s'),
            'perfomance' => Arr::random($targets),
        ];
    }
}
