<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_id',
        'reservation_id',
        'amount',
        'total_price'
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    
}
