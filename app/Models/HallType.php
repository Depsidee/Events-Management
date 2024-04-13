<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type'
    ];

    public function halls()
    {
        $this->hasMany(Hall::class);
    }
}
