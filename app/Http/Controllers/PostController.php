<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        return view('posts.index', ['posts' => $posts]);
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
}
