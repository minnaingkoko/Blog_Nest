<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Role;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard with statistics
     */
    public function index()
    {
        $stats = [
            'userCount' => User::count(),
            'adminCount' => User::where('role_id', 1)->count(),
            'authorCount' => User::where('role_id', 2)->count(),
            'regularUserCount' => User::where('role_id', 3)->count(),
            'postCount' => Post::count(),
            'publishedPostCount' => Post::where('status', 'published')->count(),
            'draftPostCount' => Post::where('status', 'draft')->count(),
            'recentUsers' => User::with('role')->latest()->take(5)->get(),
            'recentPosts' => Post::latest()->take(5)->get()
        ];

        return view('admin.dashboard', compact('stats'));
    }
}