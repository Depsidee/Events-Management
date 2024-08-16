<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'user_name',
        'email',
        'phone_number',
        'password',
        'role_name',
        'profile_image',
    'Fcm_token',
        'is_block'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];



    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function halls()
    {
        return $this->hasMany(Hall::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function homeReservations()
    {
        return $this->hasMany(HomeReservation::class);
    }

    public function roles()
    {
        return $this->belongsTo(Role::class);
     }
}
