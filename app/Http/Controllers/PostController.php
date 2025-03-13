<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;

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
        $users = User::all();
        return view('posts.create', ['users' => $users]);
    }
    public function store()
    {
        $title = request('title');
        $description = request('description');
        $user_id = request('post_creator');
        $post = Post::create([
            'title' => $title,
            'description' => $description,
            'user_id' => $user_id,
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
        Post::destroy($id);
        return to_route('posts.index');
    }
    public function edit($id)
    {
        $post = Post::find($id);
        $users = User::all();
        return view('posts.edit', ['post' => $post, 'users' => $users]);
    }
    public function update($id)
    {
        $post = Post::find($id);
        $post->update([
            'title' => request('title'),
            'description' => request('description'),
            'user_id' => request('post_creator')
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
        $post->forceDelete();
        return redirect()->route('posts.trashed')->with('success', 'Post permanently deleted');
    }
}
