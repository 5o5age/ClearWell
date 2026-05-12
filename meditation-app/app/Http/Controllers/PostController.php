<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('pages.index', [
            'posts' => Post::with('user')->latest()->get(),
        ]);
    }

    public function show(Post $post)
    {
        return view('pages.show', ['post' => $post]);
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $data['user_id'] = $request->user()->id;

        Post::create($data);

        return redirect()
            ->route('pages.index')
            ->with('success', 'Raksts veiksmīgi publicēts.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', ['post' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post->update($data);

        return redirect()
            ->route('pages.index')
            ->with('success', 'Raksts atjaunināts.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('pages.index')
            ->with('success', 'Raksts dzēsts.');
    }
}
