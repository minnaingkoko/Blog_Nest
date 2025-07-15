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
                'status' => $i % 3 === 0 ? 'draft' : ($i % 5 === 0 ? 'pending' : 'published'),
                'featured_image' => $i % 4 === 0 ? 'posts/sample-image-' . $i . '.jpg' : null,
            ];
        }

        foreach ($posts as $post) {
            $newPost = Post::create($post);
            $randomTags = Tag::inRandomOrder()->limit(rand(2, 4))->pluck('id');
            $newPost->tags()->sync($randomTags);
        }

        // Create Comments (with nesting)
        $comments = [];
        foreach (Post::all() as $post) {
            for ($i = 1; $i <= 3; $i++) {
                $content = $this->generateCommentContent($i, $post->id);
                $isSpam = $this->isCommentSpam($content);

                $comment = [
                    'user_id' => User::inRandomOrder()->first()->id,
                    'post_id' => $post->id,
                    'content' => $content,
                    'is_approved' => !$isSpam,
                    'is_spam' => $isSpam,
                ];
                $comments[] = $comment;

                // Add nested replies to this comment
                if ($i === 1) {
                    $replyContent = $this->generateCommentContent($i, $post->id, true);
                    $isReplySpam = $this->isCommentSpam($replyContent);

                    $reply = [
                        'user_id' => User::inRandomOrder()->first()->id,
                        'post_id' => $post->id,
                        'parent_id' => count($comments),
                        'content' => $replyContent,
                        'is_approved' => !$isReplySpam,
                        'is_spam' => $isReplySpam,
                    ];
                    $comments[] = $reply;
                }
            }
        }

        foreach ($comments as $comment) {
            Comment::create($comment);
        }

        // Create Likes for Posts
        foreach (Post::all() as $post) {
            $randomUsers = User::inRandomOrder()->limit(rand(1, 5))->get();
            foreach ($randomUsers as $user) {
                $post->likes()->create([
                    'user_id' => $user->id,
                ]);
            }
        }

        // Create Likes for Comments
        foreach (Comment::all() as $comment) {
            if (rand(0, 1)) { // 50% chance to like each comment
                $user = User::inRandomOrder()->first();
                $comment->likes()->create([
                    'user_id' => $user->id,
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

    protected function generateCommentContent($commentIndex, $postId, $isReply = false): string
    {
        $template = $isReply
            ? 'This is a reply to comment ' . $commentIndex . ' on post ' . $postId . '.'
            : 'This is a sample comment ' . $commentIndex . ' on post ' . $postId . '.';

        if (rand(1, 10) <= 3) {
            $spamWords = ['BUY NOW', 'DISCOUNT CODE', 'FREE GIFT', '$$$', '!!!', 'CLICK HERE'];
            $template .= ' ' . $spamWords[array_rand($spamWords)];
        }

        return $template;
    }

    protected function isCommentSpam($content): bool
    {
        $spamKeywords = ['BUY NOW', 'DISCOUNT CODE', 'FREE GIFT', '$$$', '!!!', 'CLICK HERE'];
        foreach ($spamKeywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                return true;
            }
        }

        if (preg_match_all('/\b[A-Z]{3,}\b/', $content) > 2) {
            return true;
        }

        if (substr_count($content, '!') > 3 || substr_count($content, '$') > 2) {
            return true;
        }

        return false;
    }
}