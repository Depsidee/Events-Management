<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_category_id',
        'image',
        'price'
    ];

    public function foodCategory()
    {
        return $this->belongsTo(FoodCategory::class);
    }
    
    public function foodRequests()
    {
        return $this->hasMany(FoodRequest::class);
    }

    public function foodHomes()
    {
        return $this->hasMany(FoodHome::class);
    }
}
