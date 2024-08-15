<?php

namespace Database\Factories;

use App\Models\Hall;
use App\Models\HallCapacity;
use App\Models\HallType;
use App\Models\LocationCoordinates;
use App\Models\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\WorkTime;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hall>
 */
class HallFactory extends Factory
{
    protected $model = Hall::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=>User::factory()->create()->id,
            'location_coordinates_id'=>LocationCoordinates::factory()->create()->id,
            'work_time_id'=>WorkTime::factory()->create()->id,
            'hall_capacity_id'=>HallCapacity::factory()->create()->id,
            'rating_id'=>Rating::factory()->create()->id,
            'hall_type_id'=>HallType::all()->random()->id,
            'name'=>fake()->name(),
            'has_recorded'=>0,
            'space'=>fake()->randomFloat(2,50,500),
            'price_per_hour'=>fake()->randomFloat(2,100000,1000000),
            'license_image'=>null,
            'panorama_image'=>null,
            'external_image'=>null,
            'is_verified'=>0
        ];
    }
}
