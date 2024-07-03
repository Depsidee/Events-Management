<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decoration extends Model
{
    use HasFactory;

    protected $fillable = [
        'decoration_category_id',
        'image',
        'price'
    ];

    public function decorationCategory()
    {
        return $this->belongsTo(DecorationCategory::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function homeReservations()
    {
        return $this->hasMany(HomeReservation::class);
    }
}
