<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostReaction extends Model
{
    /** @use HasFactory<\Database\Factories\PostReactionFactory> */
    use HasFactory;
    protected $guarded =[];

    public function post(){
        return $this->belongsTo(Post::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

}
