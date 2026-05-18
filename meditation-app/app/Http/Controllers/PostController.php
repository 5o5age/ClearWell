<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $activeTag = $request->query('tag');

        $query = Post::with(['user', 'tags'])->latest();
        if ($activeTag) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $activeTag));
        }

        return view('pages.index', [
            'posts' => $query->get(),
            'tags' => Tag::orderBy('name')->get(),
            'activeTag' => $activeTag,
        ]);
    }

    public function show(Post $post)
    {
        $post->load('tags');
        return view('pages.show', ['post' => $post]);
    }

    public function create()
    {
        return view('admin.posts.create', [
            'tags' => Tag::orderBy('name')->get(),
            'selectedTagIds' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        $data['user_id'] = $request->user()->id;
        $tagIds = $data['tags'] ?? [];
        unset($data['tags']);

        $post = Post::create($data);
        $post->tags()->sync($tagIds);

        return redirect()
            ->route('pages.index')
            ->with('success', 'Raksts veiksmīgi publicēts.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', [
            'post' => $post,
            'tags' => Tag::orderBy('name')->get(),
            'selectedTagIds' => $post->tags()->pluck('tags.id')->all(),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        $tagIds = $data['tags'] ?? [];
        unset($data['tags']);

        $post->update($data);
        $post->tags()->sync($tagIds);

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
