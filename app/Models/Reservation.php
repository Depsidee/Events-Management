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
        return $this->belongsTo(Hall::class);
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
        return $this->belongsTo(Payment::class);
    }

    public function photography()
    {
        return $this->belongsTo(Photography::class);
    }

    public function songRequests()
    {
        return $this->hasMany(Song::class);
    }
}
