<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $view = request()->query('view', 'active');

        if ($view === 'trashed') {
            $posts = Post::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);
        } else {
            $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        }

        return view('posts.index', [
            'posts' => $posts,
            'currentView' => $view
        ]);
    }

    public function create()
    {
        // No need to pass users anymore since we don't show the selector
        return view('posts.create');
    }

    public function store()
    {
        $title = request('title');
        $description = request('description');

        $post = Post::create([
            'title' => $title,
            'description' => $description,
            'user_id' => Auth::id(), // Use the currently authenticated user's ID
        ]);
        return to_route('posts.show', ['post' => $post->id]);
    }

    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show', ['post' => $post]);
    }
    public function destroy($id)
    {
        $post = Post::find($id);

        // Check if user is authorized to delete this post
        if (Auth::id() !== $post->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to delete this post.');
        }

        Post::destroy($id);
        return to_route('posts.index');
    }
    public function edit($id)
    {
        $post = Post::find($id);

        // Check if user is authorized to edit this post
        if (Auth::id() !== $post->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to edit this post.');
        }

        // No need to pass users anymore since we don't show the selector
        return view('posts.edit', ['post' => $post]);
    }
    public function update($id)
    {
        $post = Post::find($id);

        // Check if user is authorized to update this post
        if (Auth::id() !== $post->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to update this post.');
        }

        $post->update([
            'title' => request('title'),
            'description' => request('description'),
            // Don't change the user_id - keep the original author
        ]);
        return to_route('posts.index');
    }

    /**
     * Display a listing of trashed posts.
     */
    public function trashed()
    {
        // Redirect to the index with a view parameter
        return redirect()->route('posts.index', ['view' => 'trashed']);
    }

    /**
     * Restore a soft-deleted post.
     */
    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);

        // Get count of trashed comments before restoration
        $trashedCommentsCount = $post->comments()->onlyTrashed()->count();

        // Restore the post (the observer will handle restoring comments)
        $post->restore();

        $message = 'Post restored successfully with ' . $trashedCommentsCount . ' comment(s) also restored.';

        return redirect()->route('posts.index')->with('success', $message);
    }

    /**
     * Permanently delete a soft-deleted post.
     */
    public function forceDelete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);

        // Check if user is authorized to force delete this post
        if (Auth::id() !== $post->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to permanently delete this post.');
        }

        $post->forceDelete();
        return redirect()->route('posts.trashed')->with('success', 'Post permanently deleted');
    }
}
