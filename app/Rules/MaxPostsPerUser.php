<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Post;

class MaxPostsPerUser implements ValidationRule
{
    /**
     * Maximum allowed posts per user
     */
    protected int $maxPosts = 3;

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userId = auth()->id();
        $postCount = Post::where('user_id', $userId)->count();

        if ($postCount >= $this->maxPosts) {
            $fail("You cannot create more than {$this->maxPosts} posts. Please delete an existing post first.");
        }
    }
}
