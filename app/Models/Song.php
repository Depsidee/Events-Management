<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_category_id',
        'singer_name',
        'price'
    ];

    public function SongCategory()
    {
        return $this->belongsTo(SongCategory::class);
    }

    public function reservations()
    {
        $this->belongsToMany(Reservation::class);
    }
}
