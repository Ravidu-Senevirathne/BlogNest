<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the post.
     */
    public function view(User $user, Post $post): bool
    {
        // Editor can view their own posts or published posts
        if ($user->hasRole('editor')) {
            return $user->id === $post->user_id || $post->status === 'approved';
        }

        // Admins can view any post
        if ($user->hasRole('admin')) {
            return true;
        }

        // Readers can only view approved posts
        return $post->status === 'approved';
    }

    /**
     * Determine whether the user can update the post.
     */
    public function update(User $user, Post $post): bool
    {
        // Only the post owner (editor who created it) or admin can update
        return $user->id === $post->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the post.
     */
    public function delete(User $user, Post $post): bool
    {
        // Only the post owner (editor who created it) or admin can delete
        return $user->id === $post->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create posts.
     */
    public function create(User $user): bool
    {
        // Only editors and admins can create posts
        return $user->hasRole('editor') || $user->hasRole('admin');
    }
}
