<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use GuzzleHttp\Psr7\Request;

abstract class Controller
{
    //
    public function profile(Request $request,$id) {
        $profile = User::with('posts','comments','comments.replies')->findOrFail($id);
        return response()->json($profile);  
    }
    public function popular(Request $request)
    {
        $Post = Post::withCount(['comments', 'reactions'])
        ->orderBy('views','desc')
        ->orderBy('reactions_count','desc')
        ->orderBy('comments_count','desc')
        ->with('user')->paginate(15);

    } 
    public function explore(Request $request)
    {
        $Post = Post::withCount(['comments', 'reactions'])  
        ->orderByRaw('RAND() * views + comments_count + reactions_count DESC') 
        ->with('user')->paginate(15);

    } 
}
