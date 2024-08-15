<?php

namespace Database\Seeders;

use App\Models\HallCapacity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HallCapacitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HallCapacity::factory()->count(10)->create();
    }
}
