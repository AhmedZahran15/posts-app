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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => auth()->id()
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'post' => $post->load('user'),
                'message' => 'Post created successfully'
            ]);
        }

        return redirect()->route('posts.index')
            ->with('message', 'Post created successfully');
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $post->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'post' => $post->fresh()->load('user'),
                'message' => 'Post updated successfully'
            ]);
        }

        return redirect()->route('posts.show', $post)
            ->with('message', 'Post updated successfully');
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
