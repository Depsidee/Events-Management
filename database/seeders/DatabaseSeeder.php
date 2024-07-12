<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call(
        [
        roleSeeder::class,
        UserSeeder::class,

        HallCapacitySeeder::class,
        ReservationTypeSeeder::class,
        DecorationCategorySeeder::class,
        FoodCategorySeeder::class,
        FoodSeeder::class,
        HallTypeSeeder::class,
        LocationCoordinatesSeeder::class,
        WorkTimeSeeder::class,
        HallSeeder::class,
        PhotographySeeder::class,
        songCategorySeeder::class,
        SongSeeder::class,
        DecorationSeeder::class,
        RatingSeeder::class,
    ]);
    }
}
