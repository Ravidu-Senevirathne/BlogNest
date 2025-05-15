<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog posts.
     */
    public function index()
    {
        $posts = Post::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $categories = Category::all();

        return view('blog.index', compact('posts', 'categories'));
    }

    /**
     * Display the specified blog post.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
