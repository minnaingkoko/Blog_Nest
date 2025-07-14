<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('user_id', auth()->id())->latest()->paginate(10);
        return view('author.posts.index', compact('posts'));
    }

    public function create()
    {
        // Fetch categories from the database
        $categories = Category::all();

        // Fetch tags from the database
        $tags = Tag::all();

        // Pass categories and tags to the view
        return view('author.posts.create', compact('categories', 'tags'));
    }

    public function store(StorePostRequest $request)
    {
        // Validate and get the request data
        $data = $request->validated();

        // Set the user ID to the authenticated user
        $data['user_id'] = auth()->id();

        // Handle file upload for featured image
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        // Create the post
        $post = Post::create($data);

        // Sync tags if provided
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        // Redirect to the author posts index with success message
        return redirect()->route('author.posts.index')
            ->with('success', 'Post created successfully!');
    }

    public function edit(Post $post)
    {
        // Ensure the author can only edit their own posts
        abort_if($post->user_id !== auth()->id(), 403);

        // Fetch categories from the database
        $categories = Category::all();

        // Fetch tags from the database
        $tags = Tag::all();

        // Pass post, categories, and tags to the view
        return view('author.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());
        return redirect()->route('author.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('author.posts.index')->with('success', 'Post deleted successfully.');
    }
}
