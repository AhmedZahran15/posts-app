<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all user IDs
        $userIds = User::pluck('id')->toArray();

        // Create 30 posts
        for ($i = 0; $i < 30; $i++) {
            Post::create([
                'title' => fake()->sentence(rand(4, 8)),
                'description' => fake()->paragraphs(rand(3, 7), true),
                'user_id' => fake()->randomElement($userIds),
            ]);
        }

        // Optionally, create some trashed posts
        for ($i = 0; $i < 5; $i++) {
            Post::create([
                'title' => fake()->sentence(rand(4, 8)),
                'description' => fake()->paragraphs(rand(3, 7), true),
                'user_id' => fake()->randomElement($userIds),
            ])->delete();
        }
    }
}
