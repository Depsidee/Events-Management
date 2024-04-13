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
        $this->belongsTo(Hall::class);
    }

    public function homeReservation()
    {
        $this->belongsTo(HomeReservation::class);
    }
}
