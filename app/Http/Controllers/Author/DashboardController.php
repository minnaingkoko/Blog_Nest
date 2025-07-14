<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the author dashboard.
     */
    public function index()
    {
        $authorId = auth()->id();

        // Get author-specific statistics
        $stats = [
            'totalPosts' => Post::where('user_id', $authorId)->count(),
            'publishedPosts' => Post::where('user_id', $authorId)->where('status', 'published')->count(),
            'draftPosts' => Post::where('user_id', $authorId)->where('status', 'draft')->count(),
            'recentPosts' => Post::where('user_id', $authorId)->latest()->take(5)->get(),
        ];

        return view('author.dashboard', compact('stats'));
    }
}