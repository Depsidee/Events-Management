<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservations_id',
        'songs_id'
    ];
}
