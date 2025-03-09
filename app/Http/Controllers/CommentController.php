<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function store(Request $request, Post $post)
    {
        $comment = new Comment([
            'content' => $request->content,
            'post_id' => $post->id,
            'user_id' => 1
        ]);
        $comment->save();
        return redirect()->route('posts.show', $post)->with('success', 'Comment added successfully!');
    }


    public function edit(Post $post, Comment $comment)
    {
        return view('comments.edit', compact('post', 'comment'));
    }

    public function update(Request $request, Post $post, Comment $comment)
    {
        $comment->update([
            'content' => $request->content,
        ]);
        return redirect()->route('posts.show', $post)->with('success', 'Comment updated successfully!');
    }

    public function destroy(Post $post, Comment $comment)
    {
        $comment->delete();

        return redirect()->route('posts.show', $post)->with('success', 'Comment deleted successfully!');
    }
}
