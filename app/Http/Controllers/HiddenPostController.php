<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\HiddenPost;

class HiddenPostController extends Controller
{
    // Method untuk menampilkan postingan yang disembunyikan
    public function index()
    {
        $user = Auth::user();
        $hiddenPosts = $user->hiddenPosts;

        return view('hide-post', compact('hiddenPosts'));
    }

    // Method untuk menyembunyikan postingan
    public function hide($id)
    {
        $post = Post::findOrFail($id);

        // Cek jika postingan sudah disembunyikan
        if (!HiddenPost::where('user_id', Auth::id())->where('post_id', $post->id)->exists()) {
            HiddenPost::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id
            ]);
        }

        return back()->with('status', 'Postingan berhasil disembunyikan.');
    }

    // Method untuk menampilkan postingan yang disembunyikan
    public function unhide($id)
    {
        $userId = Auth::id();

        // Cek apakah postingan sudah di-hide
        $hiddenPost = HiddenPost::where('user_id', $userId)
                                ->where('post_id', $id)
                                ->first();

        if ($hiddenPost) {
            // Hapus postingan dari tabel hidden_posts
            $hiddenPost->delete();

            return redirect()->route('hide.posts')->with('success', 'Postingan berhasil ditampilkan kembali.');
        }

        return redirect()->route('hide.posts')->with('error', 'Postingan tidak ditemukan atau tidak di-hide.');
    }
}
