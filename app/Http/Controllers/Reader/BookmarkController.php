<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * Toggle bookmark status for a post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function toggle(Post $post)
    {
        $user = auth()->user();
        $bookmarked = $user->hasBookmarked($post->id);

        if ($bookmarked) {
            // Remove bookmark
            $user->bookmarkedPosts()->detach($post->id);
            $status = false;
        } else {
            // Add bookmark
            $user->bookmarkedPosts()->attach($post->id);
            $status = true;
        }

        if (request()->expectsJson()) {
            return response()->json([
                'bookmarked' => $status
            ]);
        }

        return redirect()->back();
    }
}
