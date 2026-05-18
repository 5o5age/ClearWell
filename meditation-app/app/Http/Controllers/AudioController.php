<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{
    public function index(Request $request)
    {
        $activeTag = $request->query('tag');

        $query = Audio::with(['user', 'tags'])->latest();
        if ($activeTag) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $activeTag));
        }

        return view('audio.index', [
            'audios' => $query->get(),
            'tags' => Tag::orderBy('name')->get(),
            'activeTag' => $activeTag,
        ]);
    }

    public function create()
    {
        return view('admin.audios.create', [
            'tags' => Tag::orderBy('name')->get(),
            'selectedTagIds' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:mp3,wav,ogg,m4a|max:51200',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        $data['file_path'] = $request->file('file')->store('audios', 'public');
        $data['user_id'] = $request->user()->id;
        $tagIds = $data['tags'] ?? [];
        unset($data['file'], $data['tags']);

        $audio = Audio::create($data);
        $audio->tags()->sync($tagIds);

        return redirect()
            ->route('audio.index')
            ->with('success', 'Audio veiksmīgi publicēts.');
    }

    public function edit(Audio $audio)
    {
        return view('admin.audios.edit', [
            'audio' => $audio,
            'tags' => Tag::orderBy('name')->get(),
            'selectedTagIds' => $audio->tags()->pluck('tags.id')->all(),
        ]);
    }

    public function update(Request $request, Audio $audio)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:51200',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($audio->file_path);
            $data['file_path'] = $request->file('file')->store('audios', 'public');
        }
        $tagIds = $data['tags'] ?? [];
        unset($data['file'], $data['tags']);

        $audio->update($data);
        $audio->tags()->sync($tagIds);

        return redirect()
            ->route('audio.index')
            ->with('success', 'Audio atjaunināts.');
    }

    public function destroy(Audio $audio)
    {
        Storage::disk('public')->delete($audio->file_path);
        $audio->delete();

        return redirect()
            ->route('audio.index')
            ->with('success', 'Audio dzēsts.');
    }
}
