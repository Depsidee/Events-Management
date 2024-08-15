<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'type'
    ];

    public function songs()
    {
        return $this->hasMany(Song::class);
    }
}
