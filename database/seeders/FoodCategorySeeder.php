<?php

namespace Database\Seeders;

use App\Models\FoodCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appetizer = FoodCategory::create(['type'=>'appetizer']);
        $main_course = FoodCategory::create(['type'=>'main_course']);
        $dessert = FoodCategory::create(['type'=>'dessert']);
    }
}
