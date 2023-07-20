<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_date = new Carbon(fake()->dateTime());
        $end_date = fake()->dateTimeBetween(
            $start_date,
            $start_date->addDay()
        );

        return [
            'event_title' => fake()->unique()->sentence(),
            'event_start_date' => $start_date,
            'event_end_date' => $end_date ,
        ];
    }
}
