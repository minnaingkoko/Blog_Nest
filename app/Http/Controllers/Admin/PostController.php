<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Display a listing of the posts
    public function index()
    {
        $posts = Post::with(['user', 'category', 'tags'])->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    // Show the form for creating a new post
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    // Store a newly created post in the database
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:draft,published',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        // Assign the logged-in user
        $data['user_id'] = auth()->id();

        // Create the post
        $post = Post::create($data);

        // Sync tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully!');
    }

    // Display the specified post
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    // Show the form for editing the specified post
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    // Update the specified post in the database
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:draft,published',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete the old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        // Update the post
        $post->update($data);

        // Sync tags
        $post->tags()->sync($request->tags ?? []);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully!');
    }

    // Remove the specified post from the database
    public function destroy(Post $post)
    {
        // Delete the featured image
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();
        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}
