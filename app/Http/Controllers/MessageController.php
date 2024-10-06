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
        // Validasi input
        $request->validate([
            'message' => 'required|string',
        ]);

        // Cari atau temukan chat room berdasarkan ID
        $chatRoom = ChatRoom::findOrFail($chatRoomId);

        // Simpan pesan baru
        $message = Message::create([
            'chat_room_id' => $chatRoom->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'seen' => false, // Default pesan belum terlihat
            'seen_by_recipient' => false, // Default pesan belum dibaca oleh penerima
        ]);

        // Broadcast event jika perlu
        // event(new MessageSent($message));

        // Redirect ke halaman sebelumnya
        return redirect()->back();
    }

    public function show($chatRoomId)
    {
        // Temukan chat room
        $chatRoom = ChatRoom::findOrFail($chatRoomId);

        // Tandai semua pesan dalam chat room sebagai dibaca oleh penerima
        $chatRoom->messages()
            ->where('sender_id', '!=', Auth::id())
            ->update(['seen_by_recipient' => true]);

        return view('chat.show', compact('chatRoom'));
    }
}
