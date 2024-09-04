<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, ChatRoom $chatRoom)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $messageContent = $request->input('message');
        $currentUserId = Auth::id();

        $chatRoom->messages()->create([
            'sender_id' => $currentUserId,
            'message' => $messageContent,
        ]);

        return redirect()->route('chat.show', $chatRoom->id);
    }
}
