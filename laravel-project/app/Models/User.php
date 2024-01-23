<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login_id',
        'password',
        'user_name',
        'user_type_id',
        'group_id',
        'gender',
        'dob',
        'google_account',
        'email',
        'google_token'
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

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

     /**
     * 
     *管理ユーザーと一般ユーザーの識別
     * 
     */

    
    public function isAdmin()
    {
        return $this->user_type_id == 2; // adminユーザー
    }

    public function isGeneral()
    {
        return $this->user_type_id == 1; // generalユーザー
    }

    public function events(){
        return $this->hasMany(Event::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getAgeAttribute()
    {
        return $this->dob ? Carbon::parse($this->dob)->age : null;
    }

    public function favoriteEvents()
    {
        return $this->belongsToMany(Event::class, 'favorite_events');
    }
}
