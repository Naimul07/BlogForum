<?php

namespace App\Http\Controllers;

use App\Models\CommentReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentReplyController extends Controller
{

    public function store(Request $request)
    {
        try {
            $attribute = $request->validate([
                'comment_id' => ['required', 'exists:comments,id'],
                'post_id' => 'required|exists:posts,id', // Ensure post_id is valid
                'reply' => ['required', 'string']
            ]);
            $attribute['user_id'] = Auth::id();

            $commentReply = CommentReply::create($attribute);
            return response()->json([
                'message' => 'Reply Created Successfully',
                'reply' => $commentReply
            ], 201); // 201 Created
        } catch (ValidationException $e) {
            return response([
                'message' => 'validation errors',
                'error' => $e->errors()
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        $commentReply = CommentReply::findOrFail($id);
        if ($commentReply->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        try {
            $attribute = $request->validate([

                'reply' => ['required', 'string']
            ]);


            $commentReply->update($attribute);
            return response()->json([
                'message' => 'Reply Updated Successfully',
                'reply' => $commentReply
            ], 201); // 201 Created
        } catch (ValidationException $e) {
            return response([
                'message' => 'validation errors',
                'error' => $e->errors()
            ], 422);
        }
    }
    public function destroy($id)
    {

        // Find the comment by ID
        $reply = CommentReply::findOrFail($id);
        if ($reply->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        // Delete the comment
        $reply->delete();

        // Return a response
        return response()->json(['message' => 'reply deleted successfully'], 200); // 204 No comment
    }
}
