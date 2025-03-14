<?php

namespace App\Http\Controllers;

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

    public function create()
    {
        // This method won't be needed for SPA approach
        return Inertia::render('Posts/Create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
        ]);

        // Create the post
        $post = Post::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'user_id' => auth()->id()
        ]);

        // Load the user relationship for the response
        $post->load('user');

        // Return JSON response for axios requests
        return response()->json([
            'success' => true,
            'post' => $post,
            'message' => 'Post created successfully'
        ], 201); // 201 Created status code
    }

    public function show(Post $post)
    {
        $post->load('user', 'comments.user');

        if (request()->wantsJson()) {
            return response()->json([
                'post' => $post,
                'comments' => $post->comments,
                'message' => 'Post retrieved successfully'
            ])->setStatusCode(200, 'Post retrieved successfully');
        }

        return Inertia::render('Posts/Show', [
            'post' => $post
        ]);
    }

    public function edit(Post $post)
    {
        // This method won't be needed for SPA approach
        return Inertia::render('Posts/Edit', [
            'post' => $post
        ]);
    }

    public function update(Request $request, Post $post)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
        ]);

        // Update the post
        $post->update($validated);

        // Return appropriate response
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

    public function trashed()
    {
        $trashedPosts = Post::onlyTrashed()->with('user')->paginate(10);

        return Inertia::render('Posts/Trashed', [
            'posts' => $trashedPosts
        ]);
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();

        if (request()->wantsJson()) {
            return response()->json([
                'post' => $post->load('user'),
                'message' => 'Post restored successfully'
            ]);
        }

        return redirect()->route('posts.trashed')
            ->with('message', 'Post restored successfully');
    }

    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->forceDelete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Post permanently deleted'
            ]);
        }

        return redirect()->route('posts.trashed')
            ->with('message', 'Post permanently deleted');
    }
}
