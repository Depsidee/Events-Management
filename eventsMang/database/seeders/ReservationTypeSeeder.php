<?php

namespace Database\Seeders;

use App\Models\ReservationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $joys = ReservationType::create(['type'=>'joys']);
        $condolence = ReservationType::create(['type'=>'condolence']);
        $birthday = ReservationType::create(['type'=>'birthday']);
    }
}
