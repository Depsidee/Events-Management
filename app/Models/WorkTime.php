<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'open_time',
        'close_time'
    ];

    public function hall()
    {
        $this->belongsTo(Hall::class);
    }
}
