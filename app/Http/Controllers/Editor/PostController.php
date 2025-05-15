<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = Post::where('user_id', auth()->id())->latest()->get();
        return view('editor.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('editor.posts.create');
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts',
            'content' => 'required|string',
            'summary' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',

        ]);

        $post = new Post();
        $post->title = $validated['title'];
        $post->slug = $validated['slug'];
        $post->content = $validated['content'];
        $post->summary = $validated['summary'] ?? null;
        $post->category_id = $validated['category_id'] ?? null;

        $post->user_id = auth()->id();
        $post->status = $request->input('status', 'pending'); // Set status to pending by default

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
            $post->image = $imagePath;
        }

        $post->save();

        // Process tags if provided
        if (!empty($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];

            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    // Create tag with auto-generated slug
                    $tag = Tag::firstOrCreate(
                        ['name' => $tagName],
                        ['slug' => \Illuminate\Support\Str::slug($tagName)]
                    );
                    $tagIds[] = $tag->id;
                }
            }

            $post->tags()->sync($tagIds);
        }

        return redirect()->route('editor.posts.index')
            ->with('success', 'Post created successfully');
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return view('editor.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('editor.posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'content' => 'required|string',
            'summary' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $post->title = $validated['title'];
        $post->slug = $validated['slug'];
        $post->content = $validated['content'];
        $post->summary = $validated['summary'] ?? null;
        $post->category_id = $validated['category_id'] ?? null;
        $post->status = $validated['status'];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $imagePath = $request->file('image')->store('posts', 'public');
            $post->image = $imagePath;
        }

        $post->save();

        // Process tags if provided
        if (isset($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];

            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }

            $post->tags()->sync($tagIds);
        }

        return redirect()->route('editor.posts.index')
            ->with('success', 'Post updated successfully');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // Delete the post image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('editor.posts.index')
            ->with('success', 'Post deleted successfully');
    }
}
