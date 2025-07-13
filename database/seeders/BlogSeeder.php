<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BlogSeeder extends Seeder
{
    public function run()
    {
        // Create Roles first (unchanged)
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator'],
            ['name' => 'author', 'description' => 'Content Author'],
            ['name' => 'user', 'description' => 'Regular User'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Get role IDs
        $adminRoleId = Role::where('name', 'admin')->first()->id;
        $authorRoleId = Role::where('name', 'author')->first()->id;
        $userRoleId = Role::where('name', 'user')->first()->id;

        // Create Admin User (assign role_id directly)
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role_id' => $adminRoleId, // Set role_id here
        ]);

        // Create Author User
        $author = User::create([
            'name' => 'Blog Author',
            'email' => 'author@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role_id' => $authorRoleId, // Set role_id here
        ]);

        // Create Regular User
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role_id' => $userRoleId, // Set role_id here
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Business', 'slug' => 'business'],
            ['name' => 'Health', 'slug' => 'health'],
            ['name' => 'Travel', 'slug' => 'travel'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create Tags
        $tags = [
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'PHP', 'slug' => 'php'],
            ['name' => 'JavaScript', 'slug' => 'javascript'],
            ['name' => 'Vue', 'slug' => 'vue'],
            ['name' => 'React', 'slug' => 'react'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }

        // Create Posts
        $posts = [
            [
                'user_id' => $author->id,
                'category_id' => Category::where('slug', 'technology')->first()->id,
                'title' => 'Getting Started with Laravel',
                'slug' => 'getting-started-with-laravel',
                'excerpt' => 'Learn how to start a new Laravel project',
                'content' => 'This is the full content of the Laravel tutorial post...',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'user_id' => $author->id,
                'category_id' => Category::where('slug', 'technology')->first()->id,
                'title' => 'Advanced PHP Techniques',
                'slug' => 'advanced-php-techniques',
                'excerpt' => 'Take your PHP skills to the next level',
                'content' => 'This post covers advanced PHP concepts...',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'user_id' => $author->id,
                'category_id' => Category::where('slug', 'business')->first()->id,
                'title' => 'Startup Funding Strategies',
                'slug' => 'startup-funding-strategies',
                'excerpt' => 'How to fund your new business venture',
                'content' => 'This post explores various funding options...',
                'status' => 'draft',
            ],
        ];

        foreach ($posts as $post) {
            $newPost = Post::create($post);

            // Attach random tags
            $randomTags = Tag::inRandomOrder()->limit(2)->pluck('id');
            $newPost->tags()->attach($randomTags);
        }

        // Create Comments
        $comments = [
            [
                'user_id' => $user->id,
                'post_id' => Post::first()->id,
                'content' => 'Great post! Very helpful for beginners.',
                'is_approved' => true,
            ],
            [
                'user_id' => $admin->id,
                'post_id' => Post::first()->id,
                'content' => 'Thanks for sharing this tutorial.',
                'is_approved' => true,
            ],
            [
                'user_id' => $user->id,
                'post_id' => Post::first()->id,
                'parent_id' => 1,
                'content' => 'I agree, this was really useful!',
                'is_approved' => true,
            ],
        ];

        foreach ($comments as $comment) {
            Comment::create($comment);
        }

        // Create Likes
        $likes = [
            ['user_id' => $user->id, 'post_id' => Post::first()->id],
            ['user_id' => $admin->id, 'post_id' => Post::first()->id],
            ['user_id' => $user->id, 'post_id' => Post::skip(1)->first()->id],
        ];

        foreach ($likes as $like) {
            Like::create($like);
        }

        // Create Settings
        Setting::create([
            'site_name' => 'My Awesome Blog',
            'logo_path' => 'storage/logo.png',
            'owner_email' => 'admin@example.com',
            'social_links' => [
                'twitter' => 'https://twitter.com/yourblog',
                'facebook' => 'https://facebook.com/yourblog'
            ]
        ]);
    }
}
