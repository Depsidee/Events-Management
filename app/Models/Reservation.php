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
        'has_recording',
        'date',
        'period',
        'start_time',
        'total_price',
        'children_permission',
        'transportation',
        'guest_photography'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hall()
    {
        return $this->hasOne(Hall::class);
    }

    public function decoration()
    {
        return $this->hasOne(Decoration::class);
    }

    public function foodRequest()
    {
        return $this->hasOne(FoodRequest::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function photography()
    {
        return $this->hasOne(Photography::class);
    }

    public function song()
    {
        return $this->hasOne(Song::class);
    }
}
