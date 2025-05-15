<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        $data = [
            'totalUsers' => User::count(),
            'totalPosts' => Post::count(),
            'totalComments' => Comment::count(),
            'pendingPosts' => Post::where('status', 'pending')->count(),
            'recentPosts' => Post::with('user')->latest()->take(5)->get(),
            'recentUsers' => User::with('roles')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
