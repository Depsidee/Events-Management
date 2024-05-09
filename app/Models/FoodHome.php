<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodHome extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_reservation_id',
        'food_id',
        'amount',
        'total_price'
    ];

    public function homeReservation() 
    {
        return $this->belongsTo(HomeReservation::class);
    }

    public function food() 
    {
        return $this->belongsTo(Food::class);
    }
}
