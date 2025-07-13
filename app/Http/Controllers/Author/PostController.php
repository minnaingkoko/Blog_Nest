<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('user_id', auth()->id())->latest()->paginate(10);
        return view('author.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('author.posts.create');
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->validated());
        return redirect()->route('author.posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        return view('author.posts.edit', compact('post'));
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