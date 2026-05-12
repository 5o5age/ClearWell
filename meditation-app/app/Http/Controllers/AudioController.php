<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AudioController extends Controller
{
    public function index()
    {
        return view('audio.index', [
            'audios' => Audio::with('user')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.audios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:mp3,wav,ogg,m4a|max:51200',
        ]);

        $data['file_path'] = $request->file('file')->store('audios', 'public');
        $data['user_id'] = $request->user()->id;
        unset($data['file']);

        Audio::create($data);

        return redirect()
            ->route('audio.index')
            ->with('success', 'Audio veiksmīgi publicēts.');
    }

    public function edit(Audio $audio)
    {
        return view('admin.audios.edit', ['audio' => $audio]);
    }

    public function update(Request $request, Audio $audio)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:51200',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($audio->file_path);
            $data['file_path'] = $request->file('file')->store('audios', 'public');
        }
        unset($data['file']);

        $audio->update($data);

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
