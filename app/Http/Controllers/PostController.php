<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\ReplyComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function index()
    {
        $posts = Post::with(['comments', 'comments.replies'])->get();
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image') ? $request->file('image')->store('images', 'public') : null;

        Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'image' => $path,
        ]);

        return redirect()->route('dashboard')->with('success', 'Postingan berhasil ditambahkan!');
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();

        if ($post->likes->contains($user->id)) {
            $post->likes()->detach($user->id);
        } else {
            $post->likes()->attach($user->id);
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
}
