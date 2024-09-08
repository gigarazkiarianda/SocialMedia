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
        $chatRooms = ChatRoom::where('user1_id', Auth::id())
                             ->orWhere('user2_id', Auth::id())
                             ->get();

        $users = User::where('id', '!=', Auth::id())->get();

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
    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $userId = $request->input('user_id');
    $currentUserId = Auth::id();

    // Find or create a chat room
    $chatRoom = ChatRoom::where(function ($query) use ($currentUserId, $userId) {
        $query->where(function ($query) use ($currentUserId, $userId) {
            $query->where('user1_id', $currentUserId)
                  ->where('user2_id', $userId);
        })->orWhere(function ($query) use ($currentUserId, $userId) {
            $query->where('user1_id', $userId)
                  ->where('user2_id', $currentUserId);
        });
    })->first();

    if (!$chatRoom) {
        $chatRoom = ChatRoom::create([
            'user1_id' => $currentUserId,
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

        return view('chat.show', compact('chatRoom', 'messages'));
    }

    public function create($user_id)
    {

        return view('chat.create', compact('user_id'));
    }
}
