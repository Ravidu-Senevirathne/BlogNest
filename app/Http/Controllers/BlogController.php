<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog posts.
     */
    public function index()
    {
        $posts = Post::with(['user', 'category', 'tags'])
            ->published()
            ->latest()
            ->paginate(9);

        $categories = Category::withCount('posts')->orderBy('name')->get();
        $tags = Tag::withCount('posts')->orderBy('name')->get();

        return view('blog.index', compact('posts', 'categories', 'tags'));
    }

    /**
     * Display the specified blog post.
     */
    public function show($slug)
    {
        $post = Post::with(['user', 'category', 'tags', 'comments.user'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
