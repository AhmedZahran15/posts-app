<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = [
            [
                'id' => 1,
                'title' => 'Post 1',
                'description' => 'This is the first post',
                'posted_by' => [
                    'name' => 'John Doe',
                    'email' => 'john@gmail.com',
                    'created_at' => '2023-10-01 12:00:00',
                ]
            ]
            ,
            [
                'id' => 2,
                'title' => 'Post 2',
                'description' => 'This is the second post',
                'posted_by' => [
                    'name' => 'Jane Doe',
                    'email' => 'jane@gmail.com',
                    'created_at' => '2023-10-02 12:00:00',
                ]
            ]
            ,
            [
                'id' => 3,
                'title' => 'Post 3',
                'description' => 'This is the third post',
                'posted_by' => [
                    'name' => 'John Smith',
                    'email' => 'johnsmith@gmail.com',
                    'created_at' => '2023-10-03 12:00:00',
                ]
            ]
        ];
        return view('posts.index', ['posts' => $posts]);
    }
    public function create()
    {
        return view('posts.create');
    }

    public function show($id)
    {
        $post = [
            'id' => 1,
            'title' => 'Post 1',
            'description' => 'This is the first post',
            'posted_by' => [
                'name' => 'John Doe',
                'email' => 'john@gmail.com',
                'created_at' => '2023-10-01 12:00:00',
            ]
        ];
        return view('posts.show', ['post' => $post]);
    }
    public function store()
    {
        $title = request('title');
        $description = request('description');
        return to_route('posts.index');
    }
    public function destroy($id)
    {
        return to_route('posts.index');
    }
    public function edit($id)
    {
        $post = [
            'id' => 1,
            'title' => 'Post 1',
            'description' => 'This is the first post',
            'posted_by' => [
                'name' => 'John Doe',
                'email' => 'john@gmail.com'
                ,
                'created_at' => '2023-10-01 12:00:00',
            ]
        ];
        return view('posts.edit', ['post' => $post]);
    }
    public function update($id)
    {
        $title = request('title');
        $description = request('description');
        return to_route('posts.index');
    }
}
