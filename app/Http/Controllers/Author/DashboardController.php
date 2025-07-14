<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the author dashboard.
     */
    public function index()
    {
        $stats = [
            'totalPosts' => Post::where('user_id', auth()->id())->count(),
            'publishedPosts' => Post::where('user_id', auth()->id())->where('status', 'published')->count(),
            'draftPosts' => Post::where('user_id', auth()->id())->where('status', 'draft')->count(),
            'recentPosts' => Post::where('user_id', auth()->id())->latest()->take(5)->get(),
            'recentComments' => Comment::whereHas('post', function ($query) {
                $query->where('user_id', auth()->id());
            })->latest()->take(5)->get(),
        ];

        return view('author.dashboard', compact('stats'));
    }
}
