<?php

namespace Database\Factories;

use App\Models\WorkTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkTime>
 */
class WorkTimeFactory extends Factory
{
    protected $model = WorkTime::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'open_time'=>fake()->time(),
            'close_time'=>fake()->time()
        ];
    }
}
