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
        if (Auth::id() !== $post->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to edit this post.');
        }
        return view('posts.edit', ['post' => $post]);
    }
    public function update($id)
    {
        $post = Post::find($id);
        if (Auth::id() !== $post->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to update this post.');
        }
        $post->update([
            'title' => request('title'),
            'description' => request('description'),
        ]);
        return to_route('posts.index');
    }

    public function trashed()
    {
        return redirect()->route('posts.index', ['view' => 'trashed']);
    }

    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $trashedCommentsCount = $post->comments()->onlyTrashed()->count();
        $post->restore();
        $message = 'Post restored successfully with ' . $trashedCommentsCount . ' comment(s) also restored.';
        return redirect()->route('posts.index')->with('success', $message);
    }

    public function forceDelete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        if (Auth::id() !== $post->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to permanently delete this post.');
        }
        $post->comments()->forceDelete();
        $post->forceDelete();
        return redirect()->route('posts.trashed')->with('success', 'Post permanently deleted');
    }

    public function getPostData($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'user_name' => $post->user->name,
            'user_email' => $post->user->email,
            'created_at' => $post->created_at->format('M d, Y')
        ], 200);
    }

}
