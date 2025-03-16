<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    // API endpoint to get all posts with pagination
    public function index()
    {
        $posts = Post::with(['user','comments.user'])
            ->latest()
            ->paginate(10);

        return PostResource::collection($posts);
    }

    //  endpoint to get a specific post
    public function show(Post $post)
    {
        $post->load('user', 'comments.user');
        return new PostResource($post);
    }

    //  endpoint to create a post
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $post = Post::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => auth()->id(),
            'image' => $request->hasFile('image') ? $request->file('image') : null,
        ]);

        $post->load('user');

        return new PostResource($post);
    }

}
