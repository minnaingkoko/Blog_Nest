<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    // Fillable fields
    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 
        'content', 'featured_image', 'status',
    ];

    // Casts
    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relationships

    // A post belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A post belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // A post can have many tags (many-to-many relationship)
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // A post can have many comments
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    // A post can have many likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Scopes

    // Scope to fetch published posts
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope to fetch draft posts
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Scope to fetch posts with a specific tag
    public function scopeWithTag($query, $tagSlug)
    {
        return $query->whereHas('tags', function ($query) use ($tagSlug) {
            $query->where('slug', $tagSlug);
        });
    }

    // Helper Methods

    // Check if the post is published
    public function isPublished()
    {
        return $this->status === 'published';
    }

    // Check if the post is a draft
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    // Get the featured image URL
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return null;
    }
}