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

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $posts->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

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

    // Show a single post with approved comments and nested replies
    public function show(Post $post)
    {
        // Eager load relationships with optimized queries
        $post->load([
            'user',
            'tags',
            'comments' => function ($query) {
                $query->where('is_approved', true)
                    ->where('is_spam', false)
                    ->whereNull('parent_id') // Only top-level comments
                    ->with([
                        'user',
                        'replies' => function ($query) {
                            $query->where('is_approved', true)
                                ->where('is_spam', false)
                                ->with('user');
                        }
                    ]);
            }
        ]);

        // Count approved comments (excluding spam and replies)
        $commentCount = $post->comments()
            ->where('is_approved', true)
            ->where('is_spam', false)
            ->whereNull('parent_id')
            ->count();

        // Check if the authenticated user can comment
        $canComment = auth()->check();

        return view('public.posts.show', [
            'post' => $post,
            'canComment' => $canComment,
            'commentCount' => $commentCount
        ]);
    }
}
