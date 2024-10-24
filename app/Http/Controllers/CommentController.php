<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    //

    public function store(Request $request)
    {
        try {

            $attribute = $request->validate([
                'post_id' => 'required|exists:posts,id', // Ensure post_id is valid
                'comment' => 'required|string', // Validate comment
            ]);
            $user = $request->user();
            $attribute['user_id'] = $user->id;
            // Create a new comment
            $comment = Comment::create($attribute);
            return response()->json([
                'message'=>'Comment Created Successfully',
                'comment'=> $comment
            ], 201); // 201 Created
        } catch (ValidationException $e) {
            return response([
                'message' => 'validation errors',
                'error' => $e->errors()
            ], 422);
        }
    }

    // Update a comment
    public function update(Request $request, $id)
    {
        // Find the comment by ID
        $comment = Comment::findOrFail($id);
        try {
            if ($comment->user_id !== Auth::id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            // Validate the incoming request data
            $attribute = $request->validate([
                'comment' => 'required|string', // Validate comment
            ]);

            // Update the comment
            $comment->update($attribute);

            // Return a response
            return response()->json([
                'message'=>'Comment Updated Successfully',
                'comment'=> $comment
            ]);
        } catch (ValidationException $e) {
            return response([
                'message' => 'validation errors',
                'error' => $e->errors()
            ], 422);
        }
    }

    // Delete a comment
    public function destroy($id)
    {

        // Find the comment by ID
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // Delete the comment
        $comment->delete();

        // Return a response
        return response()->json(['message' => 'Comment deleted successfully'], 200); // 204 No comment
    }

}