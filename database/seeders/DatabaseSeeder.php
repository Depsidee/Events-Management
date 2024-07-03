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
        UserSeeder::class,
        DecorationSeeder::class,
        FoodSeeder::class,
        HallSeeder::class,
        PhotographySeeder::class,
        SongSeeder::class

    ]);
    }
}
