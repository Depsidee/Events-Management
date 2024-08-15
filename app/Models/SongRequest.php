<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'song_id'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}
