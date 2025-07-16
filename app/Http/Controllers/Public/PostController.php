<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    // Show all posts (with optional category/tag filters)
    public function index(Request $request)
    {
        // Validate filter parameters
        $request->validate([
            'category' => 'nullable|exists:categories,slug',
            'tag' => 'nullable|exists:tags,slug',
        ]);

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

        // Sorting options
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $posts->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $posts->orderBy('created_at', 'asc');
                    break;
                case 'most_commented':
                    $posts->withCount('comments')->orderBy('comments_count', 'desc');
                    break;
            }
        }

        $posts = $posts->paginate(10);

        // Cache categories and tags for the filter sidebar
        $categories = Cache::remember('public.categories', 3600, function () {
            return Category::all();
        });
        $tags = Cache::remember('public.tags', 3600, function () {
            return Tag::all();
        });

        return view('public.posts.index', compact('posts', 'categories', 'tags'));
    }

    // Show a single post
    public function show(Post $post)
    {
        // Ensure the post is published
        $post = Post::published()->findOrFail($post->id);

        // Eager load relationships with optimized queries
        $post->load([
            'user',
            'tags',
            'category', // Added for SEO and display purposes
            'comments' => function ($query) {
                $query->where('is_approved', true)
                    ->where('is_spam', false)
                    ->whereNull('parent_id')
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

        // Count approved comments
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