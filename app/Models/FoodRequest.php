<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_id',
        'amount',
        'total_price'
    ];

    public function food()
    {
        return $this->hasOne(Food::class);
    }

    public function reservation()
    {
        $this->belongsTo(Reservation::class);
    }

    public function homeReservation()
    {
        $this->belongsTo(HomeReservation::class);
    }
}
