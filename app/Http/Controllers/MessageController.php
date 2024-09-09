<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, $chatRoomId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $chatRoom = ChatRoom::findOrFail($chatRoomId);

        // Simpan pesan baru
        $message = Message::create([
            'chat_room_id' => $chatRoom->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'seen' => false,
            'seen_by_recipient' => false, // Pesan belum dibaca
        ]);

        // Broadcast event jika perlu
        // event(new MessageSent($message));

        return redirect()->back();
    }

    public function show($chatRoomId)
    {
        $chatRoom = ChatRoom::findOrFail($chatRoomId);

        // Tandai semua pesan dalam chat room sebagai dibaca oleh penerima
        $chatRoom->messages()->where('sender_id', '!=', Auth::id())->update([
            'seen_by_recipient' => true,
        ]);

        return view('chat.show', compact('chatRoom'));
    }
}
