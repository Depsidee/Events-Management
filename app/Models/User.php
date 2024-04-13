<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'profile_image'
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
        $this->hasOne(Wallet::class);
    }

    public function notifications()
    {
        $this->hasMany(Notification::class);
    }

    public function halls()
    {
        $this->hasMany(Hall::class);
    }

    public function favorites()
    {
        $this->hasMany(Favorite::class);
    }

    public function protests()
    {
        $this->hasMany(Protest::class);
    }

    public function views()
    {
        $this->hasMany(View::class);
    }

    public function reservations()
    {
        $this->hasMany(Reservation::class);
    }

    public function homeReservations()
    {
        $this->hasMany(HomeReservation::class);
    }

    public function roles()
    {
        return $this->belongsTo(Role::class);
     }
}
