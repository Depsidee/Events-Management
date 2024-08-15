<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecorationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'type'
    ];

    public function decorations()
    {
        return $this->hasMany(Decoration::class);
    }
}
