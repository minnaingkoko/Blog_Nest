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
        // Create Roles
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

        // Create Admin Users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role_id' => $adminRoleId,
        ]);

        // Create Author Users
        $author = User::create([
            'name' => 'Blog Author',
            'email' => 'author@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role_id' => $authorRoleId,
        ]);

        // Create Regular Users
        for ($i = 1; $i <= 13; $i++) {
            User::create([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role_id' => $userRoleId,
            ]);
        }

        // Create Categories
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Business', 'slug' => 'business'],
            ['name' => 'Health', 'slug' => 'health'],
            ['name' => 'Travel', 'slug' => 'travel'],
            ['name' => 'Education', 'slug' => 'education'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle'],
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
            ['name' => 'AI', 'slug' => 'ai'],
            ['name' => 'Programming', 'slug' => 'programming'],
            ['name' => 'Startup', 'slug' => 'startup'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }

        // Create Posts
        $posts = [];
        for ($i = 1; $i <= 15; $i++) {
            $posts[] = [
                'user_id' => $author->id,
                'category_id' => Category::inRandomOrder()->first()->id,
                'title' => 'Post Title ' . $i,
                'slug' => 'post-title-' . $i,
                'content' => 'This is the full content of post ' . $i . '. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'status' => $i % 3 === 0 ? 'draft' : ($i % 5 === 0 ? 'pending' : 'published'), // Vary statuses
                'featured_image' => $i % 4 === 0 ? 'posts/sample-image-' . $i . '.jpg' : null,
            ];
        }

        foreach ($posts as $post) {
            $newPost = Post::create($post);

            // Attach random tags (2-4 tags per post)
            $randomTags = Tag::inRandomOrder()->limit(rand(2, 4))->pluck('id');
            $newPost->tags()->sync($randomTags);
        }

        // Create Comments (with nesting)
        $comments = [];
        foreach (Post::all() as $post) {
            for ($i = 1; $i <= 3; $i++) {
                $comment = [
                    'user_id' => User::inRandomOrder()->first()->id,
                    'post_id' => $post->id,
                    'content' => 'This is a sample comment ' . $i . ' on post ' . $post->id . '.',
                    'is_approved' => rand(0, 1),
                ];
                $comments[] = $comment;

                // Add nested replies to this comment
                if ($i === 1) {
                    $reply = [
                        'user_id' => User::inRandomOrder()->first()->id,
                        'post_id' => $post->id,
                        'parent_id' => count($comments), // Reply to the parent comment
                        'content' => 'This is a reply to comment ' . $i . ' on post ' . $post->id . '.',
                        'is_approved' => rand(0, 1),
                    ];
                    $comments[] = $reply;
                }
            }
        }

        foreach ($comments as $comment) {
            Comment::create($comment);
        }

        // Create Likes
        foreach (Post::all() as $post) {
            $randomUsers = User::inRandomOrder()->limit(rand(1, 5))->get();
            foreach ($randomUsers as $user) {
                Like::create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                ]);
            }
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