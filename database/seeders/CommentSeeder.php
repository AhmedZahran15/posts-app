<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get active posts
        $posts = Post::withoutTrashed()->get();

        // Get all user IDs
        $userIds = User::pluck('id')->toArray();

        // Add comments to posts
        foreach ($posts as $post) {
            // Add 0-5 comments per post
            $commentCount = rand(0, 5);

            for ($i = 0; $i < $commentCount; $i++) {
                Comment::create([
                    'content' => fake()->paragraph(rand(1, 3)),
                    'post_id' => $post->id,
                    'user_id' => fake()->randomElement($userIds),
                ]);
            }
        }
    }
}
