<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($notifications);
    }

    public function follow(Request $request)
    {
        $actorId = $request->input('actor_id');
        $user = Auth::user();

        // Handle follow logic here
        // Example: $user->follow($actorId);

        return response()->json(['success' => true]);
    }

    public function unfollow(Request $request)
    {
        $actorId = $request->input('actor_id');
        $user = Auth::user();

        // Handle unfollow logic here
        // Example: $user->unfollow($actorId);

        return response()->json(['success' => true]);
    }
}
