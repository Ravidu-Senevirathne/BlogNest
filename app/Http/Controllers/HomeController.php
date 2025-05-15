<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured posts that are approved/published
        $featuredPosts = Post::where('is_featured', true)
            ->where('status', 'approved')
            ->with(['category', 'user'])  // Changed 'author' to 'user'
            ->latest()
            ->take(3)  // Limit to 3 posts for the homepage
            ->get();

        return view('welcome', [
            'featuredPosts' => $featuredPosts
        ]);
    }
}
