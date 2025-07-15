<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id', // Add support for nested replies
        ]);

        // Create the comment (spam detection happens in the Comment model's boot())
        $comment = new Comment([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'parent_id' => $request->parent_id, // Handle nested replies
        ]);

        $comment->save(); // Spam check happens here via model events

        // Redirect with appropriate message
        if ($comment->is_spam) {
            return redirect()->back()
                ->with('error', 'Your comment was flagged as spam and will be reviewed.');
        }

        return redirect()->back()
            ->with('success', 'Comment added successfully!');
    }

    /**
     * Mark a comment as spam.
     */
    public function markAsSpam(Comment $comment)
    {
        // Verify the comment belongs to the authenticated user's post
        if ($comment->post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $comment->update([
            'is_spam' => true,
            'is_approved' => false,
        ]);

        return redirect()->back()->with('success', 'Comment marked as spam.');
    }

    /**
     * Approve a comment.
     */
    public function approve(Comment $comment)
    {
        // Verify the comment belongs to the authenticated user's post
        if ($comment->post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $comment->update([
            'is_spam' => false,
            'is_approved' => true,
        ]);

        return redirect()->back()->with('success', 'Comment approved.');
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment)
    {
        // Verify the comment belongs to the authenticated user's post
        if ($comment->post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    /**
     * Like or unlike a comment.
     */
    public function like(Comment $comment)
    {
        $user = Auth::user();

        // Check if the user has already liked the comment
        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // Unlike the comment
            $like->delete();
            return response()->json(['message' => 'Comment unliked', 'liked' => false]);
        } else {
            // Like the comment
            $comment->likes()->create(['user_id' => $user->id]);
            return response()->json(['message' => 'Comment liked', 'liked' => true]);
        }
    }

    /**
     * Toggle the visibility of replies.
     */
    public function toggleReplies(Comment $comment)
    {
        // Toggle the visibility of replies (handled via JS)
        return response()->json(['message' => 'Replies toggled']);
    }
}
