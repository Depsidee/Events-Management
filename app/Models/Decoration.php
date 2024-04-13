<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decoration extends Model
{
    use HasFactory;

    protected $fillable = [
        'decoration_category_id',
        'images_paths',
        'price'
    ];

    public function decorationCategory()
    {
        return $this->belongsTo(DecorationCategory::class);
    }

    public function reservations()
    {
        $this->belongsToMany(Reservation::class);
    }

    public function homeReservations()
    {
        $this->belongsToMany(HomeReservation::class);
    }
}
