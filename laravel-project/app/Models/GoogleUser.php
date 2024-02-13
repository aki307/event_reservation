<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'google_id',
        'email',
        'name',
        'token',
        'refresh_token'
    ];
}
