<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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




public function isFollowing($userId)
{
    return $this->following()->where('following_id', $userId)->exists();
}

public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
        return $this->hasMany(Follow::class, 'follower_id');
    }

public function followers()
{
    return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    return $this->hasMany(Follow::class, 'following_id');
}

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_url ?? asset('storage/default-profile-photo.jpg');
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'likes');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }







}
