<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'send_date',
        'body'
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
