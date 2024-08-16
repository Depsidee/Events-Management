<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservation_type_id',
        'decoration_id',
        'payment_id',
        'photography_id',
        'location_coordinates_id',
        'has_recorded',
        'date',
        'period',
        'start_time',
        'total_price',
         'delete_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservationType()
    {
        return $this->belongsTo(ReservationType::class);
    }

    public function decoration()
    {
        return $this->belongsTo(Decoration::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function photography()
    {
        return $this->belongsTo(Photography::class);
    }

    public function locationCoordinates()
    {
        return $this->belongsTo(locationCoordinates::class);
    }

    public function foodHome()
    {
        return $this->hasMany(FoodHome::class);
    }
}
