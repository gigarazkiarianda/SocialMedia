<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Daftar atribut yang dapat diisi
    protected $fillable = [
        'user_id', 'notifiable_type', 'notifiable_id', 'type', 'data', 'read',
    ];

    // Casts untuk tipe data atribut
    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
    ];

    // Scope untuk mendapatkan notifikasi yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    // Relasi morphTo untuk notifikasi
    public function notifiable()
    {
        return $this->morphTo();
    }

    // Metode untuk menambahkan notifikasi berdasarkan jenis notifikasi
    public static function createNotification($userId, $type, $data)
    {
        // Pastikan bahwa userId bukanlah ID dari pengguna yang mengirim notifikasi
        if (Auth::id() !== $userId) {
            return self::create([
                'user_id' => $userId,
                'type' => $type,
                'data' => $data,
                'read' => false, // Notifikasi baru dianggap belum dibaca
            ]);
        }
    }
}
