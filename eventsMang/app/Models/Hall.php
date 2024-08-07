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
        'has_recordrd',
        'space',
        'price_per_hour',
        'license_image',
        'panorama_image',
        'external_image',
        'is_verified'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function locationCoordinates()
    {
        return $this->belongsTo(LocationCoordinates::class);
    }

    public function workTime()
    {
        return $this->belongsTo(WorkTime::class);
    }

    public function hallCapacity()
    {
        return $this->belongsTo(HallCapacity::class);
    }

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    public function hallType()
    {
        return $this->belongsTo(HallType::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
