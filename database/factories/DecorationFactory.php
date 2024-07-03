<?php

namespace Database\Factories;

use App\Models\DecorationCategory;
use App\Models\Decoration;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Decoration>
 */
class DecorationFactory extends Factory
{
    protected $model = Decoration::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'decoration_category_id'=>DecorationCategory::all()->random()->id,
            'image'=>null,
            'price'=>fake()->randomFloat(2,100000,1000000)

        ];
    }
}
