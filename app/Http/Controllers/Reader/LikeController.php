<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle like status for a post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function toggle(Post $post)
    {
        $user = auth()->user();
        $liked = $user->hasLiked($post->id);

        if ($liked) {
            // Unlike
            $user->likes()->where('post_id', $post->id)->delete();
            $status = false;
        } else {
            // Like
            $user->likes()->create([
                'post_id' => $post->id,
            ]);
            $status = true;
        }

        if (request()->expectsJson()) {
            return response()->json([
                'liked' => $status,
                'count' => $post->likesCount()
            ]);
        }

        return redirect()->back();
    }
}
