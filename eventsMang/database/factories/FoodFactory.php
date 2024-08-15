<?php

namespace Database\Factories;

use App\Models\Food;
use App\Models\FoodCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    protected $model = Food::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'food_category_id'=>FoodCategory::all()->random()->id,
            'image'=>null,
            'price'=>fake()->randomFloat(2,10000,100000)

        ];
    }
}
