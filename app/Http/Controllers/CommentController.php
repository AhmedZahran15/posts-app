<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function store(Request $request, Post $post)
    {
        // Validate the request
        $request->validate([
            'content' => 'required|string|min:3'
        ]);

        // Create the comment
        $comment = new Comment([
            'content' => $request->content,
            'post_id' => $post->id,
            'user_id' => Auth::id() // Use the currently authenticated user's ID
        ]);
        $comment->save();
        return redirect()->route('posts.show', $post)->with('success', 'Comment added successfully!');
    }

    public function edit(Post $post, Comment $comment)
    {
        // Check if user is authorized to edit this comment
        if (Auth::id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to edit this comment.');
        }

        return view('comments.edit', compact('post', 'comment'));
    }

    public function update(Request $request, Post $post, Comment $comment)
    {
        // Check if user is authorized to update this comment
        if (Auth::id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to update this comment.');
        }

        $comment->update([
            'content' => $request->content,
        ]);
        return redirect()->route('posts.show', $post)->with('success', 'Comment updated successfully!');
    }

    public function destroy(Post $post, Comment $comment)
    {
        // Check if user is authorized to delete this comment
        if (Auth::id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
        }

        // Use forceDelete() instead of delete() to permanently remove the comment
        $comment->forceDelete();

        return redirect()->route('posts.show', $post)->with('success', 'Comment deleted successfully!');
    }
}
