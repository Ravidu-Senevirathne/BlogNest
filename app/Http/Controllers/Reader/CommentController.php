<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a new comment.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'user' => auth()->user(),
            ]);
        }

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment)
    {
        // Check if the user is the owner of the comment
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }

        $postId = $comment->post_id;
        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'post_id' => $postId
            ]);
        }

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}
