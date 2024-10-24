<?php

namespace App\Http\Controllers;

use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
  /*
  GET|HEAD      api/post ........................................... post.index › PostController@index
  POST            api/post ........................................... post.store › PostController@store
  GET|HEAD        api/post/{post} ...................................... post.show › PostController@show
  PUT|PATCH       api/post/{post} .................................. post.update › PostController@update
  DELETE          api/post/{post} ................................ post.destroy › PostController@destroy

  */
  //return the posts
  public function index()
  {
    // $Post = Post::latest()->with('user')->paginate(15);
    $Post = Post::withCount(['comments', 'reactions','replies'])->latest()->with('user')->paginate(15);
    /* $posts = DB::table('posts')
    ->join('users', 'posts.user_id', '=', 'users.id') // Join users table
    ->leftJoin('comments', 'posts.id', '=', 'comments.post_id') // Left join comments table
    ->leftJoin('post_reactions', 'posts.id', '=', 'post_reactions.post_id')
    ->select('posts.id',DB::raw('COUNT(comments.id) as comment_count'))->groupBy('posts.id')->paginate(15); */

    /* $Post = $posts = DB::table('posts')
      ->select(
        'posts.*',
        // DB::raw('(SELECT * FROM users WHERE posts.user_id = users.id) as user_details'),
        DB::raw('(SELECT COUNT(*) FROM comments WHERE posts.id = comments.post_id) as comments_count'),
        DB::raw('(SELECT COUNT(*) FROM post_reactions WHERE posts.id = post_reactions.post_id) as reactions_count')
      )
      ->orderBy('posts.created_at','desc')
      ->join('users', 'users.id','=','posts.user_id'); */
      
   
  


    return response()->json($Post);
  }

  // store the post
  public function store(Request $request)
  {
    try {
      $attribute = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
      ]);

      $attribute['user_id'] = Auth::id();
      $post = Post::create($attribute);

      if ($request->hasFile('image')) {

        $imagePath = $request->file('image')->store('images', 'public');
        $post->image = $imagePath;
        $post->save();
      }
      return response()->json([
        'post' => $post,
        'message' => 'Post created Successfully',
      ], 201);
    } catch (ValidationException $e) {

      return response()->json([
        'message' => "validation failure",
        'error' => $e->errors()
      ], 422);
    }
  }


  //show single post
  public function show($id)
  {
    $post = Post::with('user','comments','comments.replies')->findOrFail($id);

    if (!$post)
      /* return response()->json([
      'message'=>'Post Not found',
    ],404); */
      $post->increment('views');
    return response()->json($post);
  }



  //update post
  public function update(Request $request, $id)
  {
    $post = Post::findOrFail($id);
    if (Auth::id() !== $post->user_id) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }

    try {

      $attribute = $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
      ]);


      if ($request->hasFile('image')) {
        Storage::disk('public')->delete($post->image);
        $imagePath = $request->file('image')->store('images', 'public');
        $attribute['image'] = $imagePath;
      }

      $post->update($attribute);


      return response()->json([
        'message' => "post updated successfully",
        'post' => $post,
      ]);
    } catch (ValidationException $e) {
      return response()->json([
        'message' => "validation failure",
        'error' => $e->errors()
      ], 422);
    }
  }


  //delete
  public function destroy($id)
  {
    $post = Post::findOrFail($id);
    if ($post->user_id !== Auth::id()) {
      return response()->json(['message' => 'Unauthorized'], 403);
    }
    if ($post->image) {
      Storage::disk('public')->delete($post->image);
    }
    $post->delete();
    return response()->json(['message' => 'Post deleted successfully.'], 200);
  }
}
