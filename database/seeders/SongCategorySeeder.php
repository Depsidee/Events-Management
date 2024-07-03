<?php

namespace Database\Seeders;

use App\Models\SongCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class songCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $joys = SongCategory::create(['type'=>'joys']);
        $birthday = SongCategory::create(['type'=>'birthday']);
    }
}
