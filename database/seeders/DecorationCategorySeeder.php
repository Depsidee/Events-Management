<?php

namespace Database\Seeders;

use App\Models\DecorationCategory;
use Database\Factories\DecorationFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DecorationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $joys = DecorationCategory::create(['type'=>'joys']);
        $condolence = DecorationCategory::create(['type'=>'condolence']);
        $birthday = DecorationCategory::create(['type'=>'birthday']);
    }
}
