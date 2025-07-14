<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Blaspsoft\Blasp\Facades\Blasp;

class Comment extends Model
{
    protected $fillable = ['content', 'user_id', 'post_id', 'parent_id', 'is_approved', 'is_spam'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            // Check for bad words
            $blasp = Blasp::check($comment->content);
            $hasBadWords = $blasp->hasProfanity();

            // Custom spam rules
            $isSpam = $comment->isSpam($comment->content);

            // Auto-mark as spam if bad words or spam rules are triggered
            $comment->is_spam = $hasBadWords || $isSpam;
            $comment->is_approved = !$comment->is_spam;

            // Mask profanities in the comment content
            if ($hasBadWords) {
                $comment->content = $blasp->getCleanString();
            }
        });
    }

    /**
     * Custom spam detection rules.
     */
    protected function isSpam($content): bool
    {
        // Rule 1: Too many ALL-CAPS words
        if (preg_match_all('/\b[A-Z]{4,}\b/', $content) > 3) {
            return true;
        }

        // Rule 2: Excessive links
        if (substr_count($content, 'http') > 2) {
            return true;
        }

        // Rule 3: Suspicious patterns (e.g., "$$$", "!!!")
        if (preg_match('/\$\$\$|!!!|\b(?:cheap|discount|free)\b/i', $content)) {
            return true;
        }

        return false; // Not spam
    }

    // Relationship to Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}