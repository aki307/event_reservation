<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'start_date_and_time', 'end_date_and_time', 'location', 'description', 'user_id', 'group_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function views()
    {
        return $this->hasOne(EventView::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_events');
    }

    public function favoritesCount()
    {
        return $this->favoritedByUsers()->count();
    }
}
