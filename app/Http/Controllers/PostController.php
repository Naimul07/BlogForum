<?php

namespace App\Http\Controllers;

use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    $Post = Post::latest()->get();
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
      return response()->json($post, 201);
    } catch (ValidationException $e) {
      return response()->json([
        'message' => "validation failure",
        'error' => $e->errors()
      ]);
    }
  }


  //show single post
  public function show($id)
  {
    $post = Post::with('user')->findOrFail($id);
    return response()->json($post, 201);
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
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
      ]);
      if ($request->hasFile('image')) {
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
      ]);
    }
  }


  //delete
  public function destroy($id) {
    $post = Post::findOrFail($id);
    if($post->user_id !== Auth::id())
    {
      return response()->json(['message' => 'Unauthorized'], 403);
    }
    if($post->image)
    {
      Storage::disk('public')->delete($post->image);
    }
    $post->delete();
    return response()->json(['message' => 'Post deleted successfully.'], 200);
  }
}
