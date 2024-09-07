<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplyComment extends Model
{
    protected $fillable = ['user_id', 'post_id', 'comment_id', 'content'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
