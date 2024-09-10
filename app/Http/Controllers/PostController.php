<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\ReplyComment;
use App\Models\Notification;
use App\Models\HiddenPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function index()
    {
        $user = Auth::user();

        // Ambil semua postingan kecuali yang disembunyikan oleh pengguna saat ini
        $posts = Post::with(['comments', 'comments.replies'])
            ->whereDoesntHave('hiddenByUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'caption' => 'required|string|max:255',
        'image' => 'nullable|image|max:2048',
    ]);

    $imagePath = $request->file('image') ? $request->file('image')->store('posts', 'public') : null;

    auth()->user()->posts()->create([
        'caption' => $validated['caption'],
        'image' => $imagePath,
    ]);

    return redirect()->route('dashboard')->with('success', 'Post created successfully.');
}

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();

        if ($post->likes->contains($user->id)) {
            $post->likes()->detach($user->id);
        } else {
            $post->likes()->attach($user->id);

            // Menambahkan notifikasi
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
    }

    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $post = Post::findOrFail($id);
        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->user_id = Auth::id();
        $comment->content = $request->input('comment');
        $comment->save();

        // Menambahkan notifikasi
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
            'notifiable_type' => 'App\Models\User', // Sesuaikan dengan model yang relevan
            'notifiable_id' => $post->user_id,
        ]);

        return redirect()->back();
    }

    public function reply(Request $request, $post_id, $comment_id)
    {
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

        // Menambahkan notifikasi untuk balasan komentar
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
            'notifiable_type' => 'App\Models\User', // Sesuaikan dengan model yang relevan
            'notifiable_id' => $comment->user_id,
        ]);

        return redirect()->back()->with('success', 'Balasan berhasil ditambahkan!');
    }

    public function show($id)
    {
        $post = Post::with(['user', 'likes', 'comments.replies', 'comments.user', 'comments.replies.user'])
                    ->findOrFail($id);

        return view('posts.show', compact('post'));
    }

    public function dashboard()
    {
        $followingIds = Auth::user()->following->pluck('id')->toArray();

        $posts = Post::whereIn('user_id', $followingIds)
            ->with(['user', 'comments', 'likes'])
            ->get();

        return view('dashboard', compact('posts'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post->update($request->only('title', 'content'));

        if ($request->hasFile('image')) {
            $post->update(['image' => $request->file('image')->store('images', 'public')]);
        }

        return redirect()->route('dashboard')->with('success', 'Postingan berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('dashboard')->with('success', 'Postingan berhasil dihapus.');
    }

    public function hidePost($postId)
    {
        $userId = Auth::id();

        // Cek apakah post sudah disembunyikan sebelumnya
        $hiddenPost = HiddenPost::where('user_id', $userId)->where('post_id', $postId)->first();

        if (!$hiddenPost) {
            HiddenPost::create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
        }

        return redirect()->back()->with('success', 'Post telah disembunyikan.');
    }

    public function unhide($id)
    {
        $userId = Auth::id();

        // Hapus postingan dari tabel hidden_post
        HiddenPost::where('user_id', $userId)->where('post_id', $id)->delete();

        // Redirect kembali ke halaman hide-post
        return redirect()->route('hide-post')->with('success', 'Postingan telah ditampilkan kembali.');
    }
}
