<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Show all posts (with optional category/tag filters)
    public function index(Request $request)
    {
        $posts = Post::query()->published();

        // Filter by category
        if ($request->has('category')) {
            $posts->whereHas('category', function ($query) use ($request) {
                $query->where('slug', $request->category);
            });
        }

        // Filter by tag
        if ($request->has('tag')) {
            $posts->whereHas('tags', function ($query) use ($request) {
                $query->where('slug', $request->tag);
            });
        }

        $posts = $posts->paginate(10);

        // Get all categories and tags for the filter sidebar
        $categories = Category::all();
        $tags = Tag::all();

        return view('public.posts.index', compact('posts', 'categories', 'tags'));
    }

    // Show a single post
    public function show(Post $post)
    {
        // Fetch the post with its relationships
        $post->load('user', 'tags', 'comments.user');

        // Check if the authenticated user can comment
        $canComment = auth()->check(); // Only logged-in users can comment

        return view('public.posts.show', compact('post', 'canComment'));
    }
}
