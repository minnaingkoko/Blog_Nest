<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10); // Fetch latest posts
        return view('public.posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('public.posts.show', compact('post'));
    }
}