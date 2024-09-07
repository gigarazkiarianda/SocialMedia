<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReplyComment;
use App\Models\Comment;

class ReplyCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'comment_id' => 'required|exists:comments,id',
            'post_id' => 'required|exists:posts,id',
        ]);

        ReplyComment::create([
            'user_id' => auth()->id(),
            'comment_id' => $request->comment_id,
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Balasan berhasil ditambahkan.');
    }
}

