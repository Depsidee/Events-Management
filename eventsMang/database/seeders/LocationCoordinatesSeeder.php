<?php

namespace Database\Seeders;

use App\Models\LocationCoordinates;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationCoordinatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LocationCoordinates::factory()->count(10)->create();
    }
}
