<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\MetaService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display the post details.
     */
    public function show(string $slug, MetaService $metaService)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        // Only allow accessing published posts unless user is admin or editor or the author
        if ($post->status !== 'published') {
            if (
                !auth()->check() ||
                (auth()->user()->cannot('edit posts') && auth()->id() !== $post->user_id)
            ) {
                abort(404);
            }
        }

        // Set meta tags for SEO
        $metaService->setTitle($post->title)
            ->setDescription(substr(strip_tags($post->content), 0, 160))
            ->setType('article')
            ->setAuthor($post->user->name);

        if ($post->image) {
            $metaService->setImage(asset('storage/' . $post->image));
        }

        if ($post->tags->count() > 0) {
            $metaService->setKeywords($post->tags->pluck('name')->toArray());
        }

        $metaService->setArticleMeta(
            $post->user->name,
            $post->created_at->toIso8601String(),
            $post->updated_at->toIso8601String(),
            $post->tags->pluck('name')->toArray(),
            $post->category->name
        );

        return view('posts.show', compact('post'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('posts.create', compact('categories'));
    }
}
