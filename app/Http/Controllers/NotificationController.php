<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Menampilkan semua notifikasi untuk pengguna yang sedang login
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung jumlah notifikasi yang belum dibaca
        $unreadNotificationsCount = Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->count();

        return view('notifications.index', compact('notifications', 'unreadNotificationsCount'));
    }

    // Menandai notifikasi sebagai dibaca
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read' => true]);
        return redirect()->back();
    }

    // Menambahkan notifikasi ketika pengguna mengikuti pengguna lain
    public function notifyFollow(User $followedUser)
    {
        // Cek apakah pengguna yang mengikuti adalah pengguna yang sama dengan yang diikuti
        if (Auth::id() !== $followedUser->id) {
            $notification = new Notification([
                'user_id' => $followedUser->id,
                'type' => 'follow',
                'data' => [
                    'user_name' => Auth::user()->name,
                    'follower_id' => Auth::id(), // Menyimpan ID pengikut
                ],
                'read' => false, // Set default notifikasi belum dibaca
            ]);
            $notification->save();
        }
    }

    // Menambahkan notifikasi ketika pengguna menyukai sebuah postingan
    public function notifyLike(Post $post)
    {
        // Cek apakah pengguna yang menyukai adalah pengguna yang sama dengan pemilik postingan
        if (Auth::id() !== $post->user_id) {
            $notification = new Notification([
                'user_id' => $post->user_id,
                'type' => 'like',
                'data' => [
                    'user_name' => Auth::user()->name,
                    'post_id' => $post->id,
                ],
                'read' => false, // Set default notifikasi belum dibaca
            ]);
            $notification->save();
        }
    }

    // Menambahkan notifikasi ketika pengguna mengomentari sebuah postingan
    public function notifyComment(Comment $comment)
    {
        // Cek apakah pengguna yang mengomentari adalah pengguna yang sama dengan pemilik postingan
        if (Auth::id() !== $comment->post->user_id) {
            $notification = new Notification([
                'user_id' => $comment->post->user_id,
                'type' => 'comment',
                'data' => [
                    'user_name' => Auth::user()->name,
                    'comment' => $comment->content,
                    'post_id' => $comment->post->id, // Menyimpan ID postingan
                ],
                'read' => false, // Set default notifikasi belum dibaca
            ]);
            $notification->save();
        }
    }

    // Menambahkan notifikasi ketika pengguna mengikuti pengguna lain
    public function follow(User $user)
    {
        // Simpan hubungan follow di database
        Auth::user()->following()->attach($user->id);

        // Tambahkan notifikasi
        $this->notifyFollow($user);

        return redirect()->back();
    }

    // Menambahkan notifikasi ketika pengguna mengomentari sebuah postingan
    public function comment(Request $request, Post $post)
    {
        // Validasi input komentar
        $request->validate([
            'content' => 'required|string',
        ]);

        // Simpan komentar
        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        // Tambahkan notifikasi
        $this->notifyComment($comment);

        return redirect()->back();
    }

    // Menambahkan notifikasi ketika pengguna menyukai sebuah postingan
    public function like(Post $post)
    {
        // Simpan like di database
        Auth::user()->likes()->attach($post->id);

        // Tambahkan notifikasi
        $this->notifyLike($post);

        return redirect()->back();
    }
}
