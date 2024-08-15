<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_category_id',
        'song_name',
        'song'
    ];

    public function SongCategory()
    {
        return $this->belongsTo(SongCategory::class);
    }

    public function songRequests()
    {
        return $this->hasMany(SongRequest::class);
    }
}
