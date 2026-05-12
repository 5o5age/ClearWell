<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        return view('video.index', [
            'videos' => Video::with('user')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:mp4,webm,mov,mkv|max:204800',
        ]);

        $data['file_path'] = $request->file('file')->store('videos', 'public');
        $data['user_id'] = $request->user()->id;
        unset($data['file']);

        Video::create($data);

        return redirect()
            ->route('video.index')
            ->with('success', 'Video veiksmīgi publicēts.');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.edit', ['video' => $video]);
    }

    public function update(Request $request, Video $video)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:mp4,webm,mov,mkv|max:204800',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($video->file_path);
            $data['file_path'] = $request->file('file')->store('videos', 'public');
        }
        unset($data['file']);

        $video->update($data);

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
