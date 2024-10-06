<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatRoomController extends Controller
{
    /**
     * Display a listing of chat rooms.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the authenticated user
        $authUser = Auth::user();

        // Get users that the current user is following or are mutual followers
        $users = User::whereHas('followers', function ($query) use ($authUser) {
            $query->where('follower_id', $authUser->id);
        })
        ->orWhereHas('following', function ($query) use ($authUser) {
            $query->where('following_id', $authUser->id);
        })
        ->where('id', '!=', $authUser->id)
        ->get();

        // Get all chat rooms involving the authenticated user
        $chatRooms = ChatRoom::where('user1_id', $authUser->id)
            ->orWhere('user2_id', $authUser->id)
            ->with(['messages', 'user1', 'user2'])
            ->get();

        return view('chat.index', compact('chatRooms', 'users'));
    }


    /**
     * Store a new message in the chat room.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $authUser = Auth::id();
        $userId = $request->user_id;

        // Check if a chat room already exists between these users
        $chatRoom = ChatRoom::where(function ($query) use ($authUser, $userId) {
            $query->where('user1_id', $authUser)->where('user2_id', $userId);
        })->orWhere(function ($query) use ($authUser, $userId) {
            $query->where('user1_id', $userId)->where('user2_id', $authUser);
        })->first();

        if (!$chatRoom) {
            // Create a new chat room
            $chatRoom = ChatRoom::create([
                'user1_id' => $authUser,
                'user2_id' => $userId,
            ]);
        }

        return redirect()->route('chat.show', $chatRoom->id);
    }

    /**
     * Show the messages for a specific chat room.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chatRoom = ChatRoom::findOrFail($id);
        $messages = $chatRoom->messages()->with('sender')->get();

        // Tandai semua pesan dalam chat room sebagai dibaca oleh penerima
        $chatRoom->messages()
            ->where('sender_id', '!=', Auth::id())
            ->update(['seen_by_recipient' => true]);

        return view('chat.show', compact('chatRoom', 'messages'));
    }

    /**
     * Create or navigate to an existing chat room.
     *
     * @param int $user_id
     * @return \Illuminate\Http\Response
     */
    public function create($user_id = null)
    {
        $currentUserId = Auth::id();

        // Cek apakah chat room sudah ada
        $chatRoom = ChatRoom::where(function ($query) use ($currentUserId, $user_id) {
            $query->where(function ($query) use ($currentUserId, $user_id) {
                $query->where('user1_id', $currentUserId)
                      ->where('user2_id', $user_id);
            })->orWhere(function ($query) use ($currentUserId, $user_id) {
                $query->where('user1_id', $user_id)
                      ->where('user2_id', $currentUserId);
            });
        })->first();

        // Jika belum ada, buat chat room baru
        if (!$chatRoom) {
            $chatRoom = ChatRoom::create([
                'user1_id' => $currentUserId,
                'user2_id' => $user_id,
            ]);
        }

        // Arahkan ke halaman chat room
        return redirect()->route('chat.show', $chatRoom->id);
    }
}
