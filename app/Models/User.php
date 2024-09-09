<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

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

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // Metode untuk menambahkan notifikasi ketika mengikuti
    public function notifyFollow(User $followedUser)
    {
        // Cek apakah pengguna yang mengikuti adalah pengguna yang sama dengan yang diikuti
        if (Auth::id() !== $followedUser->id) {
            $notification = new Notification([
                'user_id' => $followedUser->id,
                'type' => 'follow',
                'data' => [
                    'user_name' => Auth::user()->name,
                ],
            ]);
            $notification->save();
        }
    }

    // Metode untuk menambahkan notifikasi ketika menyukai post
    public function notifyLike(Post $post)
    {
        // Cek apakah pengguna yang menyukai adalah pengguna yang sama dengan pemilik post
        if (Auth::id() !== $post->user_id) {
            $notification = new Notification([
                'user_id' => $post->user_id,
                'type' => 'like',
                'data' => [
                    'user_name' => Auth::user()->name,
                ],
            ]);
            $notification->save();
        }
    }

    // Metode untuk menambahkan notifikasi ketika mengomentari post
    public function notifyComment(Comment $comment)
    {
        // Cek apakah pengguna yang mengomentari adalah pengguna yang sama dengan pemilik post
        if (Auth::id() !== $comment->post->user_id) {
            $notification = new Notification([
                'user_id' => $comment->post->user_id,
                'type' => 'comment',
                'data' => [
                    'user_name' => Auth::user()->name,
                    'comment' => $comment->content,
                ],
            ]);
            $notification->save();
        }
    }

    public function sentMessages()
{
    return $this->hasMany(Message::class);
}

public function followings()
{
    return $this->hasMany(Follow::class, 'follower_id');
}
}
