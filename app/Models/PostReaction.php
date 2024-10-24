<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostReaction extends Model
{
    /** @use HasFactory<\Database\Factories\PostReactionFactory> */
    use HasFactory;
    protected $guarded =[];

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
