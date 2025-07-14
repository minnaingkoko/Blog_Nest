<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class AuthorCommentController extends Controller
{
    /**
     * Display a listing of comments for the author's posts.
     */
    public function index(Request $request)
    {
        // Fetch comments only for the author's posts
        $comments = Comment::whereHas('post', function ($query) {
            $query->where('user_id', auth()->id());
        });

        // Apply status filter if provided
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'approved':
                    $comments->where('is_approved', true);
                    break;
                case 'spam':
                    $comments->where('is_spam', true);
                    break;
                default:
                    // No filter applied
                    break;
            }
        }

        // Order by latest and paginate
        $comments = $comments->latest()->paginate(10);

        return view('author.comments.index', compact('comments'));
    }

    public function edit(Comment $comment)
    {
        // Verify the comment belongs to the author's post
        if ($comment->post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('author.comments.edit', compact('comment'));
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        // Verify the comment belongs to the author's post
        if ($comment->post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Validate the request
        $request->validate([
            'content' => 'required|string|max:500',
            'is_spam' => 'sometimes|boolean',
        ]);

        // Update the comment
        $comment->update([
            'content' => $request->content,
            'is_spam' => $request->is_spam ?? $comment->is_spam, // Keep existing value if not provided
            'is_approved' => !($request->is_spam ?? $comment->is_spam), // Auto-set approval status
        ]);

        return redirect()->route('author.comments.index')->with('success', 'Comment updated successfully.');
    }

    /**
     * Approve a spam comment.
     */
    public function approve(Comment $comment)
    {
        // Verify the comment belongs to the author's post
        if ($comment->post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Mark the comment as approved
        $comment->update([
            'is_spam' => false,
            'is_approved' => true,
        ]);

        return redirect()->back()->with('success', 'Comment approved successfully.');
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment)
    {
        // Verify the comment belongs to the author's post
        if ($comment->post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

    /**
     * Delete all spam comments.
     */
    public function deleteAllSpam()
    {
        // Delete all spam comments for the author's posts
        Comment::whereHas('post', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('is_spam', true)->delete();

        return redirect()->back()->with('success', 'All spam comments deleted successfully.');
    }
}
