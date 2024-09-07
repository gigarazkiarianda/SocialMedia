<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserFollowed;

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
    $userToFollow = User::findOrFail($userId); // Find the user to follow
    $currentUser = auth()->user(); // Get the currently authenticated user

    // Check if the current user is not already following the user
    if (!$currentUser->following->contains($userToFollow)) {
        $currentUser->following()->attach($userId); // Follow the user

        // Optionally, handle any additional logic here (e.g., logging, analytics)
    }

    return redirect()->back(); // Redirect back to the previous page
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


}
