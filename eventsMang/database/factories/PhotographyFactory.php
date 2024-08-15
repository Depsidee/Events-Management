<?php

namespace Database\Factories;

use App\Models\Photography;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photography>
 */
class photographyFactory extends Factory
{
    protected $model = Photography::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'photographer_name'=>fake()->name(),
            'price'=>fake()->randomFloat(2,100000,500000)
        ];
    }
}
