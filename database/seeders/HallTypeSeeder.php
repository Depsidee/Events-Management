<?php

namespace Database\Seeders;

use App\Models\HallType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HallTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $open = HallType::create(['type'=>'open']);
        $closed = HallType::create(['type'=>'closed']);
        $hotel = HallType::create(['type'=>'hotel']);
    }
}
