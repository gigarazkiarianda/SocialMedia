<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function follow($id)
    {
        $user = User::findOrFail($id);

        // Attach the followed user to the authenticated user's following list
        Auth::user()->following()->toggle($user->id);

        return redirect()->back();
    }

    public function unfollow($id)
    {
        $user = User::findOrFail($id);

        // Detach the followed user from the authenticated user's following list
        Auth::user()->following()->detach($user->id);

        return redirect()->back();
    }

    public function dashboard()
    {
        $user = Auth::user();
        // Fetch users that the logged-in user is following
        $followingUsers = $user->following;

        return view('dashboard', compact('user', 'followingUsers'));
    }

}
