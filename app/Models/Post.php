<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'category_id',
        'user_id',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that the post belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the tags associated with this post.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the comments for the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the users who liked this post.
     */
    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'likes')
            ->withTimestamps();
    }

    /**
     * Get the users who bookmarked this post.
     */
    public function bookmarkedBy()
    {
        return $this->belongsToMany(User::class, 'bookmarks')
            ->withTimestamps();
    }

    /**
     * Get all approved comments for the post.
     */
    public function approvedComments()
    {
        return $this->comments()->where('is_approved', true);
    }

    /**
     * Get the number of likes for the post.
     *
     * @return int
     */
    public function likesCount(): int
    {
        return $this->likedBy()->count();
    }

    /**
     * Get the URL for the post
     *
     * @return string
     */
    public function getUrl(): string
    {
        return route('blog.show', $this->slug);
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished($query)
    {
        // Update to include 'approved' status
        return $query->where('status', 'approved');
    }

    /**
     * Check if the post is published.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        // Update to check for 'approved' status
        return $this->status === 'approved';
    }

    /**
     * Get the user who authored the post (alias for user relationship)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
