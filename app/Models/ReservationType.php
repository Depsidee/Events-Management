<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function HomeReservations()
    {
        return $this->hasMany(HomeReservation::class);
    }
}
