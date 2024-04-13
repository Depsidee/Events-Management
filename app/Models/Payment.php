<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'amount'
    ];

    public function reservation()
    {
        $this->belongsTo(Reservation::class);
    }

    public function homeReservation()
    {
        $this->belongsTo(HomeReservation::class);
    }
}
