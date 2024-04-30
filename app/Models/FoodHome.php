<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodHome extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_reservations_id',
        'foods_id',
        'amount',
        'total_price'
    ];
}
