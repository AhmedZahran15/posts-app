<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);

        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'flash' => [
                'message' => session('message')
            ],
        ]);
    }

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $post = Post::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => auth()->id()
        ]);
        $post->load('user');
        return response()->json([
            'success' => true,
            'post' => $post,
            'message' => 'Post created successfully'
        ], 201);
    }

    public function show(Post $post)
    {
        $post->load('user', 'comments.user');
        if (request()->wantsJson()) {
            return response()->json([
                'post' => $post,
                'comments' => $post->comments,
                'user' => $post->user,
                'message' => 'Post retrieved successfully'
            ])->setStatusCode(200, 'Post retrieved successfully');
        }
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();
        if (isset($validated['user_id'])) {
            unset($validated['user_id']);
        }
        $post->update($validated);
        return response()->json([
            'success' => true,
            'post' => $post->fresh()->load('user'),
            'message' => 'Post updated successfully'
        ]);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Post moved to trash'
            ]);
        }

        return redirect()->route('posts.index')
            ->with('message', 'Post moved to trash');
    }

    // public function trashed()
    // {
    //     $trashedPosts = Post::onlyTrashed()->with('user')->paginate(10);

    //     return Inertia::render('Posts/Trashed', [
    //         'posts' => $trashedPosts
    //     ]);
    // }

    // public function restore($id)
    // {
    //     $post = Post::onlyTrashed()->findOrFail($id);
    //     $post->restore();

    //     if (request()->wantsJson()) {
    //         return response()->json([
    //             'post' => $post->load('user'),
    //             'message' => 'Post restored successfully'
    //         ]);
    //     }

    //     return redirect()->route('posts.trashed')
    //         ->with('message', 'Post restored successfully');
    // }

    // public function forceDelete($id)
    // {
    //     $post = Post::onlyTrashed()->findOrFail($id);
    //     $post->forceDelete();

    //     if (request()->wantsJson()) {
    //         return response()->json([
    //             'message' => 'Post permanently deleted'
    //         ]);
    //     }

    //     return redirect()->route('posts.trashed')
    //         ->with('message', 'Post permanently deleted');
    // }
}
