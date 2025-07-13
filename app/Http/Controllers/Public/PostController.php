<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('home', [
            'posts' => $posts,
            'canLike' => auth()->check(),
            'canComment' => auth()->check()
        ]);
    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post,
            'comments' => $post->comments()->with('user')->latest()->get(),
            'canLike' => auth()->check(),
            'canComment' => auth()->check()
        ]);
    }
}
