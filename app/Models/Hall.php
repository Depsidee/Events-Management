<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_coordinates_id',
        'work_time_id',
        'hall_capacity_id',
        'rating_id',
        'hall_type_id',
        'name',
        'space',
        'price_per_hour',
        'license_image',
        'panorama_image',
        'external_image',
        'is_verified'
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function location()
    {
        $this->hasOne(LocationCoordinates::class);
    }

    public function workTime()
    {
        $this->hasOne(WorkTime::class);
    }

    public function hallCapacity()
    {
        $this->hasOne(HallCapacity::class);
    }

    public function rating()
    {
        $this->hasOne(Rating::class);
    }

    public function hallType()
    {
        $this->belongsTo(HallType::class);
    }

    public function favorites()
    {
        $this->belongsToMany(Favorite::class);
    }

    public function protests()
    {
        $this->belongsToMany(Protest::class);
    }

    public function views()
    {
        $this->belongsToMany(View::class);
    }

    public function reservations()
    {
        $this->belongsToMany(Reservation::class);
    }
}
