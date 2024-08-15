<?php

namespace Database\Factories;

use App\Models\HallCapacity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HallCapacity>
 */
class HallCapacityFactory extends Factory
{
    protected $model = HallCapacity::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'minimum'=>fake()->numberBetween(100,1000),
            'maximum'=>fake()->numberBetween(100,1000),
            'recommended'=>fake()->numberBetween(100,1000)
        ];
    }
}
