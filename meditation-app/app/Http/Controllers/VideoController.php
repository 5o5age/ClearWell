<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $activeTag = $request->query('tag');

        $query = Video::with(['user', 'tags'])->latest();
        if ($activeTag) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $activeTag));
        }

        return view('video.index', [
            'videos' => $query->get(),
            'tags' => Tag::orderBy('name')->get(),
            'activeTag' => $activeTag,
        ]);
    }

    public function create()
    {
        return view('admin.videos.create', [
            'tags' => Tag::orderBy('name')->get(),
            'selectedTagIds' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:mp4,webm,mov,mkv|max:204800',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        $data['file_path'] = $request->file('file')->store('videos', 'public');
        $data['user_id'] = $request->user()->id;
        $tagIds = $data['tags'] ?? [];
        unset($data['file'], $data['tags']);

        $video = Video::create($data);
        $video->tags()->sync($tagIds);

        return redirect()
            ->route('video.index')
            ->with('success', 'Video veiksmīgi publicēts.');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', [
            'video' => $video,
            'tags' => Tag::orderBy('name')->get(),
            'selectedTagIds' => $video->tags()->pluck('tags.id')->all(),
        ]);
    }

    public function update(Request $request, Video $video)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:mp4,webm,mov,mkv|max:204800',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($video->file_path);
            $data['file_path'] = $request->file('file')->store('videos', 'public');
        }
        $tagIds = $data['tags'] ?? [];
        unset($data['file'], $data['tags']);

        $video->update($data);
        $video->tags()->sync($tagIds);

        return redirect()
            ->route('video.index')
            ->with('success', 'Video atjaunināts.');
    }

    public function destroy(Video $video)
    {
        Storage::disk('public')->delete($video->file_path);
        $video->delete();

        return redirect()
            ->route('video.index')
            ->with('success', 'Video dzēsts.');
    }
}
