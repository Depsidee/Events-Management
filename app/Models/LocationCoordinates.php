<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationCoordinates extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'langitude'
    ];

    public function hall()
    {
        return $this->hasOne(Hall::class);
    }

    public function homeReservation()
    {
        return $this->hasOne(HomeReservation::class);
    }
}
