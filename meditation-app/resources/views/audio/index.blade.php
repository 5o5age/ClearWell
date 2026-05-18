<x-layouts.app title="Audio">
    <div class="max-w-7xl mx-auto px-5 py-16">
        <div class="flex items-start justify-between gap-6 flex-wrap">
            <div>
                <h1 class="brand-font text-3xl font-medium">Audio</h1>
                <p class="mt-2 text-base-content/50">Nomierinošas skaņas un vadītas meditācijas.</p>
            </div>

            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.audios.create') }}" class="btn btn-primary btn-sm rounded-xl">
                        + Jauns audio
                    </a>
                @endif
            @endauth
        </div>

        @if($tags->isNotEmpty())
            <div class="mt-8 flex items-center gap-2 flex-wrap">
                <span class="text-xs uppercase tracking-widest text-base-content/45 mr-2">Filtrēt:</span>
                <a href="{{ route('audio.index') }}"
                   class="btn btn-xs rounded-lg {{ !$activeTag ? 'btn-primary' : 'btn-ghost' }}">Visi</a>
                @foreach($tags as $tag)
                    <a href="{{ route('audio.index', ['tag' => $tag->slug]) }}"
                       class="btn btn-xs rounded-lg {{ $activeTag === $tag->slug ? 'btn-primary' : 'btn-ghost' }}">{{ $tag->name }}</a>
                @endforeach
            </div>
        @endif

        @if($audios->isEmpty())
            <p class="mt-12 text-base-content/40 italic">
                @if($activeTag)
                    Šajā tagā vēl nav neviena audio.
                @else
                    Vēl nav publicēts neviens audio.
                @endif
            </p>
        @else
            <div class="mt-10 grid gap-5">
                @foreach($audios as $audio)
                    <div class="p-5 rounded-2xl border border-base-200 bg-base-100">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h2 class="font-semibold text-lg">{{ $audio->title }}</h2>
                                @if($audio->description)
                                    <p class="text-sm text-base-content/60 mt-1">{{ $audio->description }}</p>
                                @endif
                                @if($audio->tags->isNotEmpty())
                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        @foreach($audio->tags as $tag)
                                            <a href="{{ route('audio.index', ['tag' => $tag->slug]) }}"
                                               class="badge badge-ghost badge-sm rounded-lg hover:badge-primary">{{ $tag->name }}</a>
                                        @endforeach
                                    </div>
                                @endif
                                <p class="text-xs text-base-content/35 mt-2">
                                    Publicējis {{ $audio->user->name }} · {{ $audio->created_at->diffForHumans() }}
                                </p>
                                <audio controls class="mt-3 w-full" src="{{ asset('storage/'.$audio->file_path) }}"></audio>
                            </div>

                            @auth
                                @if(auth()->user()->isAdmin())
                                    <div class="flex flex-col gap-2 shrink-0">
                                        <a href="{{ route('admin.audios.edit', $audio) }}" class="btn btn-ghost btn-xs rounded-lg">Rediģēt</a>
                                        <form method="POST" action="{{ route('admin.audios.destroy', $audio) }}"
                                              onsubmit="return confirm('Dzēst šo audio?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-xs rounded-lg text-error">Dzēst</button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
