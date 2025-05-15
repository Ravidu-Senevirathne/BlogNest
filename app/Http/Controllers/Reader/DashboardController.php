<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the reader dashboard.
     */
    public function index()
    {
        // Change status condition from 'published' to 'approved'
        $publishedPosts = Post::where('status', 'approved')
            ->with(['user', 'category', 'tags'])
            ->latest()
            ->paginate(10);

        return view('reader.dashboard', compact('publishedPosts'));
    }

    /**
     * Display the reader's bookmarked posts.
     */
    public function bookmarks()
    {
        $bookmarkedPosts = auth()->user()->bookmarkedPosts()
            ->with(['user', 'category', 'tags'])
            ->latest()
            ->paginate(10);

        return view('reader.bookmarks', compact('bookmarkedPosts'));
    }

    /**
     * Search and filter posts.
     */
    public function search(Request $request)
    {
        $query = Post::published()->with(['user', 'category', 'tags']);

        // Search by title or content
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('content', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        // Filter by tag
        if ($request->has('tag') && !empty($request->tag)) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        // Filter by author
        if ($request->has('author') && !empty($request->author)) {
            $query->where('user_id', $request->author);
        }

        $posts = $query->latest()->paginate(10);

        // Get categories, tags, and authors for filter dropdowns
        $categories = Category::all();
        $tags = Tag::all();
        $authors = User::role(['admin', 'editor'])->get();

        return view('reader.search', compact('posts', 'categories', 'tags', 'authors'));
    }
}
