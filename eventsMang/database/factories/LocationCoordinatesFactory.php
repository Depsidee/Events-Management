<?php

namespace Database\Factories;

use App\Models\LocationCoordinates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LocationCoordinates>
 */
class LocationCoordinatesFactory extends Factory
{
    protected $model = LocationCoordinates::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->name(),
            'description'=>fake()->paragraph(),
            'latitude'=>fake()->latitude(-90,90),
            'longitude'=>fake()->longitude(-180,180)
        ];
    }
}
