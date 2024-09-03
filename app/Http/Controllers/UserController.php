<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserFollowed;


class UserController extends Controller
{
    /**
     * Search for users based on the query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('name', 'LIKE', '%' . $query . '%')
                     ->orWhere('email', 'LIKE', '%' . $query . '%')
                     ->get();

        return view('user.search', compact('users', 'query'));
    }

    /**
     * Display the profile of the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile($id)
    {
        $user = User::findOrFail($id);
        $biodata = $user->biodata; // Using the relationship to get biodata

        return view('user.profile', compact('user', 'biodata')); // Adjusted to 'user.profile'
    }
    public function myProfile()
    {
        $user = Auth::user();
        $biodata = $user->biodata;
        return view('user.myprofile', compact('user', 'biodata'));
    }

    public function follow($userId)
    {
        $userToFollow = User::findOrFail($userId);
        $currentUser = auth()->user();

        if (!$currentUser->following->contains($userToFollow)) {
            $currentUser->following()->attach($userId);

            // Send a notification to the user being followed
            $userToFollow->notify(new UserFollowed($currentUser, 'follow'));

            // Optionally, send a notification to the current user if they are followed back
            if ($userToFollow->following->contains($currentUser)) {
                $currentUser->notify(new UserFollowed($userToFollow, 'follow_back'));
            }
        }

        return redirect()->back();
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

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->latest()->get();
        return view('user.notifications', compact('notifications'));
    }
}
