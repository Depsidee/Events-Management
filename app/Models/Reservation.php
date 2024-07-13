<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hall_id',
        'decoration_id',
        'payment_id',
        'photography_id',
        'reservation_type_id',
        'has_recorded',
        'date',
        'period',
        'start_time',
        'total_price',
        'children_permission',
        'transportation',
        'guest_photography',
        'delete_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function reservationType()
    {
        return $this->belongsTo(ReservationType::class);
    }

    public function decoration()
    {
        return $this->belongsTo(Decoration::class);
    }

    public function foodRequest()
    {
        return $this->hasMany(FoodRequest::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function photography()
    {
        return $this->belongsTo(Photography::class);
    }

    public function songRequest()
    {
        return $this->hasMany(SongRequest::class);
    }
    protected $dates = [
        'delete_time',
    ];

}
