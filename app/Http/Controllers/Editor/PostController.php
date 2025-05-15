<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Add Str facade

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = Post::where('user_id', auth()->id())->with('category', 'tags')->latest()->get(); // Eager load relationships
        return view('editor.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        // Potentially pass categories if they are dynamic and not hardcoded in blade
        // $categories = \App\Models\Category::all();
        // return view('editor.posts.create', compact('categories'));
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
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
            // 'status' is not in create form, so it will use default 'pending'
        ]);

        $post = new Post();
        $post->title = $validated['title'];
        $post->slug = $validated['slug'];
        $post->content = $validated['content'];

        $post->category_id = $validated['category_id'] ?? null;

        $post->user_id = auth()->id();
        $post->status = 'pending'; // Default status for new posts

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
                        ['slug' => Str::slug($tagName)] // Use Str::slug
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
        $post->load('category', 'tags', 'user'); // Eager load relationships
        return view('editor.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        // $categories = \App\Models\Category::all(); // Pass categories if needed for select dropdown
        // $post->load('tags'); // Ensure tags are loaded for the form
        // return view('editor.posts.edit', compact('post', 'categories'));
        $post->load('tags'); // Ensure tags are loaded for the form's tag input
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

            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
            'updated_at'=> 'nullable|string'
        ]);

        $post->title = $validated['title'];
        $post->slug = $validated['slug'];
        $post->content = $validated['content'];
        
        $post->category_id = $validated['category_id'] ?? null;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $imagePath = $request->file('image')->store('posts', 'public');
            $post->image = $imagePath;
        }

        $post->updated_at = now();
        $post->save();

        // Process tags if provided
        if (isset($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];

            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    // Create tag with auto-generated slug
                    $tag = Tag::firstOrCreate(
                        ['name' => $tagName],
                        ['slug' => Str::slug($tagName)] // Use Str::slug
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $post->tags()->sync($tagIds);
        } else {
            // If tags field is empty or not present, detach all tags
            $post->tags()->sync([]);
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
