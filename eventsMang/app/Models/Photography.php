<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photography extends Model
{
    use HasFactory;

    protected $fillable = [
        'photographer_name',
        'price'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function homeReservations()
    {
        return $this->hasMany(HomeReservation::class);
    }
}
