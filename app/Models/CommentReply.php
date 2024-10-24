<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    /** @use HasFactory<\Database\Factories\CommentReplyFactory> */
    use HasFactory;
    protected $guarded =[];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
