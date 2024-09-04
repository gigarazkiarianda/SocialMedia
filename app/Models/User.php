<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Fillable fields
    protected $fillable = [
        'name', 'email', 'password',
    ];

    // Hidden fields
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Define the relationship with Biodata
    public function biodata()
    {
        return $this->hasOne(Biodata::class);
    }

    public function following()
{
    return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
}

public function followers()
{
    return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
}

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_url ?? asset('storage/default-profile-photo.jpg');
    }
}
