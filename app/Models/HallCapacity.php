<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallCapacity extends Model
{
    use HasFactory;

    protected $fillable = [
        'minimum',
        'maximum',
        'recommended'
    ];

    public function hall()
    {
        return $this->hasOne(Hall::class);
    }
}
