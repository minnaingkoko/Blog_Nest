<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // Like or unlike a post or comment
    public function toggleLike(Request $request)
    {
        $request->validate([
            'likeable_id' => 'required|integer',
            'likeable_type' => 'required|in:App\Models\Post,App\Models\Comment',
        ]);

        $user = Auth::user();

        // Find the liked model
        $likeable = $request->likeable_type === 'App\Models\Post'
            ? Post::find($request->likeable_id)
            : Comment::find($request->likeable_id);

        if (!$likeable) {
            return response()->json(['message' => 'Post or Comment not found'], 404);
        }

        // Check if the user has already liked the model
        $like = $likeable->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // Unlike the model
            $like->delete();
            return response()->json(['message' => 'Unliked successfully', 'liked' => false]);
        } else {
            // Like the model
            $likeable->likes()->create(['user_id' => $user->id]);
            return response()->json(['message' => 'Liked successfully', 'liked' => true]);
        }
    }
}