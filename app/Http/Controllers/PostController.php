<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\ReplyComment;
use App\Models\Notification;
use App\Models\HiddenPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Form untuk membuat post baru
    public function create()
    {
        return view('posts.create');
    }

    // Menampilkan semua postingan kecuali yang disembunyikan oleh user
    public function index()
    {
        $user = Auth::user();

        try {
            // Ambil semua postingan kecuali yang disembunyikan oleh pengguna saat ini
            $posts = Post::with(['comments', 'comments.replies'])
                ->whereDoesntHave('hiddenByUsers', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest()
                ->get();

            return view('posts.index', compact('posts'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat postingan: ' . $e->getMessage());
        }
    }

    // Store postingan baru
    public function store(Request $request)
    {
        try {
            // Validasi input, termasuk handling image yang nullable
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
            ]);

            // Simpan gambar jika ada, jika tidak maka null
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
            }

            // Simpan postingan dengan atau tanpa gambar
            auth()->user()->posts()->create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'image' => $imagePath, // Simpan null jika tidak ada gambar
            ]);

            return redirect()->route('dashboard')->with('success', 'Post created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat postingan: ' . $e->getMessage());
        }
    }

    // Like/unlike post
    public function like($id)
    {
        $user = Auth::user();

        try {
            $post = Post::findOrFail($id);

            // Jika sudah di-like, maka unlike, jika belum maka like
            if ($post->likes->contains($user->id)) {
                $post->likes()->detach($user->id);
            } else {
                $post->likes()->attach($user->id);

                // Menambahkan notifikasi jika ada like
                Notification::create([
                    'user_id' => $post->user_id,
                    'type' => 'like',
                    'data' => [
                        'user_name' => $user->name,
                        'post_id' => $post->id,
                    ],
                    'read' => false,
                    'notifiable_type' => 'App\Models\User', // Sesuaikan dengan model yang relevan
                    'notifiable_id' => $post->user_id,
                ]);
            }

            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Postingan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan like: ' . $e->getMessage());
        }
    }

    // Menambahkan komentar pada postingan
    public function addComment(Request $request, $id)
    {
        try {
            $request->validate([
                'comment' => 'required|string|max:255',
            ]);

            $post = Post::findOrFail($id);
            $comment = new Comment();
            $comment->post_id = $post->id;
            $comment->user_id = Auth::id();
            $comment->content = $request->input('comment');
            $comment->save();

            // Menambahkan notifikasi untuk komentar
            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'comment',
                'data' => [
                    'user_name' => Auth::user()->name,
                    'post_id' => $post->id,
                    'comment_id' => $comment->id,
                    'comment_content' => $comment->content,
                ],
                'read' => false,
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $post->user_id,
            ]);

            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Postingan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan komentar: ' . $e->getMessage());
        }
    }

    // Membalas komentar
    public function reply(Request $request, $post_id, $comment_id)
    {
        try {
            $request->validate([
                'reply_text' => 'required|string|max:255',
            ]);

            $post = Post::findOrFail($post_id);
            $comment = Comment::findOrFail($comment_id);

            $reply = new ReplyComment();
            $reply->post_id = $post_id;
            $reply->user_id = Auth::id();
            $reply->comment_id = $comment_id;
            $reply->content = $request->input('reply_text');
            $reply->save();

            // Menambahkan notifikasi untuk balasan
            Notification::create([
                'user_id' => $comment->user_id,
                'type' => 'reply',
                'data' => [
                    'user_name' => Auth::user()->name,
                    'post_id' => $post_id,
                    'comment_id' => $comment_id,
                    'reply_id' => $reply->id,
                    'reply_content' => $reply->content,
                ],
                'read' => false,
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $comment->user_id,
            ]);

            return redirect()->back()->with('success', 'Balasan berhasil ditambahkan!');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Postingan atau komentar tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan balasan: ' . $e->getMessage());
        }
    }

    // Menampilkan detail post
    public function show($id)
    {
        try {
            $post = Post::with(['user', 'likes', 'comments.replies', 'comments.user', 'comments.replies.user'])
                        ->findOrFail($id);

            return view('posts.show', compact('post'));
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Postingan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat detail postingan: ' . $e->getMessage());
        }
    }

    // Menampilkan post di dashboard
    public function dashboard()
    {
        try {
            $followingIds = Auth::user()->following->pluck('id')->toArray();

            $posts = Post::whereIn('user_id', $followingIds)
                ->with(['user', 'comments', 'likes'])
                ->get();

            return view('dashboard', compact('posts'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }

    // Form edit post
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // Update post
    public function update(Request $request, Post $post)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
            ]);

            // Update caption and content
            $post->update($request->only('title', 'content'));

            // Simpan gambar baru jika ada
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                // Simpan gambar baru
                $post->update(['image' => $request->file('image')->store('posts', 'public')]);
            }

            return redirect()->route('dashboard')->with('success', 'Postingan berhasil diperbarui.');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Postingan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui postingan: ' . $e->getMessage());
        }
    }

    // Hapus post
    public function destroy(Post $post)
    {
        try {
            // Hapus gambar jika ada
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $post->delete();

            return redirect()->route('dashboard')->with('success', 'Postingan berhasil dihapus.');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Postingan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus postingan: ' . $e->getMessage());
        }
    }

    // Menyembunyikan post
    public function hidePost($postId)
    {
        $userId = Auth::id();

        try {
            // Cek apakah post sudah disembunyikan sebelumnya
            $hiddenPost = HiddenPost::where('user_id', $userId)->where('post_id', $postId)->first();

            if (!$hiddenPost) {
                HiddenPost::create([
                    'user_id' => $userId,
                    'post_id' => $postId,
                ]);
            }

            return redirect()->back()->with('success', 'Post telah disembunyikan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyembunyikan postingan: ' . $e->getMessage());
        }
    }

    // Mengembalikan post yang disembunyikan
    public function unhide($id)
    {
        $userId = Auth::id();

        try {
            // Hapus postingan dari tabel hidden_post
            HiddenPost::where('user_id', $userId)->where('post_id', $id)->delete();

            return redirect()->route('dashboard')->with('success', 'Postingan telah ditampilkan kembali.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menampilkan kembali postingan: ' . $e->getMessage());
        }
    }
}
