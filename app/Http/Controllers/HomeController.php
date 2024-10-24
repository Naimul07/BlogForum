<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostReaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    
    public function profile(Request $request,$id) {
        $profile = User::with('posts','comments','replies')->findOrFail($id);
        return response()->json($profile);  
    }
    public function popular(Request $request)
    {
        $Post = Post::withCount(['comments', 'reactions','replies'])
        ->orderBy('views','desc')
        ->orderBy('reactions_count','desc')
        ->orderBy('comments_count','desc')
        ->with('user')->paginate(15);
        
        return response()->json($Post);  

    } 
    public function explore(Request $request)
    {
        $Post = Post::withCount(['comments', 'reactions'])  
        ->orderByRaw('RAND() * views + comments_count + reactions_count DESC') 
        ->with('user')->paginate(15);
        return response()->json($Post);  

    } 
    public function reactionStore(Request $request){
        // dd($request);
        try{
             $attribute = $request->validate([
            'post_id'=>['required'],
        ]);
        $attribute['user_id']=Auth::id();
        $reaction=PostReaction::create($attribute);
        return response()->json(['message' => 'Reaction updated', 'reaction' => $reaction]);
        }
        catch(ValidationException $e)
        {
            return response()->json([
                'error'=>$e->errors(),
            ]);
        }
       
    }
    public function reactionDestroy(Request $request,$id){
        // dd($request);
        $reaction = PostReaction::findOrFail($id);
        $reaction->delete();
        return response()->json(['message' => 'Reaction removed']);
    }
}
