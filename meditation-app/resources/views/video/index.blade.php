<x-layouts.app title="Video">
    <div class="max-w-7xl mx-auto px-5 py-16">
        <div class="flex items-start justify-between gap-6 flex-wrap">
            <div>
                <h1 class="brand-font text-3xl font-medium">Video</h1>
                <p class="mt-2 text-base-content/50">Vizuālas meditācijas un apzinātas sesijas.</p>
            </div>

            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary btn-sm rounded-xl">
                        + Jauns video
                    </a>
                @endif
            @endauth
        </div>

        @if($tags->isNotEmpty())
            <div class="mt-8 flex items-center gap-2 flex-wrap">
                <span class="text-xs uppercase tracking-widest text-base-content/45 mr-2">Filtrēt:</span>
                <a href="{{ route('video.index') }}"
                   class="btn btn-xs rounded-lg {{ !$activeTag ? 'btn-primary' : 'btn-ghost' }}">Visi</a>
                @foreach($tags as $tag)
                    <a href="{{ route('video.index', ['tag' => $tag->slug]) }}"
                       class="btn btn-xs rounded-lg {{ $activeTag === $tag->slug ? 'btn-primary' : 'btn-ghost' }}">{{ $tag->name }}</a>
                @endforeach
            </div>
        @endif

        @if($videos->isEmpty())
            <p class="mt-12 text-base-content/40 italic">
                @if($activeTag)
                    Šajā tagā vēl nav neviena video.
                @else
                    Vēl nav publicēts neviens video.
                @endif
            </p>
        @else
            <div class="mt-10 grid md:grid-cols-2 gap-6">
                @foreach($videos as $video)
                    <div class="p-5 rounded-2xl border border-base-200 bg-base-100">
                        <video controls class="w-full rounded-xl bg-black" src="{{ asset('storage/'.$video->file_path) }}"></video>
                        <div class="flex items-start justify-between gap-4 mt-4">
                            <div class="flex-1 min-w-0">
                                <h2 class="font-semibold text-lg">{{ $video->title }}</h2>
                                @if($video->description)
                                    <p class="text-sm text-base-content/60 mt-1">{{ $video->description }}</p>
                                @endif
                                @if($video->tags->isNotEmpty())
                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        @foreach($video->tags as $tag)
                                            <a href="{{ route('video.index', ['tag' => $tag->slug]) }}"
                                               class="badge badge-ghost badge-sm rounded-lg hover:badge-primary">{{ $tag->name }}</a>
                                        @endforeach
                                    </div>
                                @endif
                                <p class="text-xs text-base-content/35 mt-2">
                                    Publicējis {{ $video->user->name }} · {{ $video->created_at->diffForHumans() }}
                                </p>
                            </div>

                            @auth
                                @if(auth()->user()->isAdmin())
                                    <div class="flex flex-col gap-2 shrink-0">
                                        <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-ghost btn-xs rounded-lg">Rediģēt</a>
                                        <form method="POST" action="{{ route('admin.videos.destroy', $video) }}"
                                              onsubmit="return confirm('Dzēst šo video?');">
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
