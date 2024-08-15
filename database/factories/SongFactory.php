<?php

namespace Database\Factories;

use App\Models\Song;
use App\Models\SongCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Song>
 */
class SongFactory extends Factory
{
    protected $model = Song::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'song_category_id'=>SongCategory::all()->random()->id,
            'song_name'=>fake()->name(),
            'song'=>null
        ];
    }
}
