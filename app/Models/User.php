<?php

// app/Models/User.php

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
        'name', 'email', 'password', 'is_online', // Tambahkan 'is_online' ke fillable
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
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
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

    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'like', "%$query%")->get(['name', 'profile_photo_url']); // Ambil hanya nama dan foto profil

        return response()->json([
            'data' => $users
        ]);
    }

    // Aksesori untuk menentukan apakah pengguna online
    public function getIsOnlineAttribute()
    {
        return $this->attributes['is_online'];
    }
}
