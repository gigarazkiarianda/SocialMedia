<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'message',
        'seen',
        'seen_by_recipient', // Tambahkan kolom ini
    ];

    // Relasi dengan pengguna
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
