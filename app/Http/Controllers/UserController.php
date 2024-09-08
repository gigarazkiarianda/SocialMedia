<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserFollowed;
use App\Models\Message; // Pastikan Anda memiliki model Message
use App\Models\Follow; // Pastikan Anda memiliki model Follow

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'LIKE', '%' . $query . '%')
                     ->orWhere('email', 'LIKE', '%' . $query . '%')
                     ->get();

        return view('user.search', compact('users', 'query'));
    }

    public function profile($id)
    {
        $user = User::with(['biodata', 'followers', 'following', 'posts' => function($query) {
            $query->orderBy('created_at', 'asc');
        }])->findOrFail($id);
        $biodata = $user->biodata;

        return view('user.profile', compact('user', 'biodata'));
    }

    public function myProfile()
    {
        $user = Auth::user();
        $biodata = $user->biodata;
        $posts = $user->posts; // Ambil postingan dari pengguna yang sedang login
        return view('user.myprofile', compact('user', 'biodata', 'posts'));
    }

    public function follow($userId)
    {
        $userToFollow = User::findOrFail($userId); // Temukan pengguna yang akan diikuti
        $currentUser = auth()->user(); // Ambil pengguna yang sedang login

        // Periksa jika pengguna saat ini belum mengikuti pengguna tersebut
        if (!$currentUser->following->contains($userToFollow)) {
            $currentUser->following()->attach($userId); // Ikuti pengguna

            // Opsional, tangani logika tambahan di sini (misalnya, logging, analitik)
        }

        return redirect()->back(); // Kembali ke halaman sebelumnya
    }

    public function unfollow($id)
    {
        $user = User::findOrFail($id);
        Auth::user()->following()->detach($user->id);

        return redirect()->back();
    }

    public function dashboard()
    {
        $user = Auth::user();
        $followingUsers = $user->following;

        return view('dashboard', compact('user', 'followingUsers'));
    }

    public function followers($id)
    {
        $user = User::findOrFail($id);
        $followers = $user->followers;
        return view('user.followers', compact('user', 'followers'));
    }

    public function following($id)
    {
        $user = User::findOrFail($id);
        $following = $user->following;
        return view('user.following', compact('user', 'following'));
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class);
    }

    public function followings()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function show($id)
    {
        $user = User::with('biodata', 'followers', 'following', 'posts')->findOrFail($id);
        return view('profile.show', compact('user'));
    }

    public function showUserProfile($id)
    {
        $user = User::findOrFail($id); // Ambil pengguna berdasarkan ID
        return view('profile.show', compact('user')); // Tampilkan view profil
    }
}
