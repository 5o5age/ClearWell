@php
    use App\Models\Audio;
    use App\Models\Video;
    use App\Models\Post;

    $user = auth()->user();
    $audioCount = Audio::count();
    $videoCount = Video::count();
    $postCount  = Post::count();
    $recentAudios = Audio::latest()->take(3)->get();
    $recentVideos = Video::latest()->take(3)->get();
    $recentPosts  = Post::with('user')->latest()->take(3)->get();
@endphp

<x-layouts.app title="Vadības panelis">
    <div class="max-w-7xl mx-auto px-5 py-16">

        <div class="flex items-start justify-between gap-6 flex-wrap">
            <div>
                <p class="text-sm text-base-content/45 uppercase tracking-widest font-medium">
                    @if($user->isAdmin()) Administrators @else Tava telpa @endif
                </p>
                <h1 class="brand-font text-4xl font-medium mt-2">
                    Sveicināts atpakaļ, {{ $user->name }}.
                </h1>
                <p class="mt-2 text-base-content/50">
                    @if($user->isAdmin())
                        Pārvaldi saturu, publicē jaunu audio, video vai rakstus un uzraugi vietni.
                    @else
                        Atrodi savu mieru. Apskati jaunākos audio, video un rakstus zemāk.
                    @endif
                </p>
            </div>

            @if($user->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary rounded-xl">
                    Administratora panelis →
                </a>
            @endif
        </div>

        <div class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl border border-base-200 bg-base-100">
                <p class="text-xs uppercase tracking-widest text-base-content/45 font-medium">Audio</p>
                <p class="text-3xl brand-font font-medium mt-2">{{ $audioCount }}</p>
            </div>
            <div class="p-5 rounded-2xl border border-base-200 bg-base-100">
                <p class="text-xs uppercase tracking-widest text-base-content/45 font-medium">Video</p>
                <p class="text-3xl brand-font font-medium mt-2">{{ $videoCount }}</p>
            </div>
            <div class="p-5 rounded-2xl border border-base-200 bg-base-100">
                <p class="text-xs uppercase tracking-widest text-base-content/45 font-medium">Raksti</p>
                <p class="text-3xl brand-font font-medium mt-2">{{ $postCount }}</p>
            </div>
            <div class="p-5 rounded-2xl border border-base-200 bg-base-100">
                <p class="text-xs uppercase tracking-widest text-base-content/45 font-medium">Loma</p>
                <p class="text-lg font-semibold mt-3 capitalize">
                    @if($user->isAdmin())
                        <span class="text-primary">administrators</span>
                    @else
                        lietotājs
                    @endif
                </p>
            </div>
        </div>

        <h2 class="brand-font text-2xl font-medium mt-14">Ātrās darbības</h2>
        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @if($user->isAdmin())
                <a href="{{ route('admin.audios.create') }}"
                   class="p-5 rounded-2xl border border-base-200 hover:border-primary/40 hover:bg-base-200/30 transition">
                    <p class="font-semibold">+ Publicēt audio</p>
                    <p class="text-sm text-base-content/50 mt-1">Augšupielādēt jaunu audio.</p>
                </a>
                <a href="{{ route('admin.videos.create') }}"
                   class="p-5 rounded-2xl border border-base-200 hover:border-primary/40 hover:bg-base-200/30 transition">
                    <p class="font-semibold">+ Publicēt video</p>
                    <p class="text-sm text-base-content/50 mt-1">Augšupielādēt jaunu video.</p>
                </a>
                <a href="{{ route('admin.posts.create') }}"
                   class="p-5 rounded-2xl border border-base-200 hover:border-primary/40 hover:bg-base-200/30 transition">
                    <p class="font-semibold">+ Publicēt rakstu</p>
                    <p class="text-sm text-base-content/50 mt-1">Uzraksti rakstu vai lapu.</p>
                </a>
            @endif

            <a href="{{ route('audio.index') }}"
               class="p-5 rounded-2xl border border-base-200 hover:border-primary/40 hover:bg-base-200/30 transition">
                <p class="font-semibold">Pārlūkot audio</p>
                <p class="text-sm text-base-content/50 mt-1">Nomierinošas skaņas un meditācijas.</p>
            </a>
            <a href="{{ route('video.index') }}"
               class="p-5 rounded-2xl border border-base-200 hover:border-primary/40 hover:bg-base-200/30 transition">
                <p class="font-semibold">Pārlūkot video</p>
                <p class="text-sm text-base-content/50 mt-1">Vizuālas meditācijas.</p>
            </a>
            <a href="{{ route('pages.index') }}"
               class="p-5 rounded-2xl border border-base-200 hover:border-primary/40 hover:bg-base-200/30 transition">
                <p class="font-semibold">Pārlūkot rakstus</p>
                <p class="text-sm text-base-content/50 mt-1">Raksti un ceļveži.</p>
            </a>
            <a href="{{ route('profile.edit') }}"
               class="p-5 rounded-2xl border border-base-200 hover:border-primary/40 hover:bg-base-200/30 transition">
                <p class="font-semibold">Profila iestatījumi</p>
                <p class="text-sm text-base-content/50 mt-1">Atjaunini vārdu, e-pastu un paroli.</p>
            </a>
        </div>

        @if($recentPosts->isNotEmpty() || $recentAudios->isNotEmpty() || $recentVideos->isNotEmpty())
            <h2 class="brand-font text-2xl font-medium mt-14">Nesen publicēts</h2>
            <div class="mt-5 grid md:grid-cols-2 gap-4">

                @foreach($recentPosts as $post)
                    <a href="{{ route('pages.show', $post) }}"
                       class="p-5 rounded-2xl border border-base-200 bg-base-100 hover:border-primary/40 transition">
                        <p class="text-xs text-base-content/40 uppercase tracking-widest font-medium">Raksts</p>
                        <p class="font-semibold mt-1">{{ $post->title }}</p>
                        <p class="text-sm text-base-content/55 mt-2 line-clamp-2">{{ \Illuminate\Support\Str::limit($post->body, 140) }}</p>
                        <p class="text-xs text-base-content/35 mt-3">{{ $post->created_at->diffForHumans() }}</p>
                    </a>
                @endforeach

                @foreach($recentAudios as $audio)
                    <a href="{{ route('audio.index') }}"
                       class="p-5 rounded-2xl border border-base-200 bg-base-100 hover:border-primary/40 transition">
                        <p class="text-xs text-base-content/40 uppercase tracking-widest font-medium">Audio</p>
                        <p class="font-semibold mt-1">{{ $audio->title }}</p>
                        @if($audio->description)
                            <p class="text-sm text-base-content/55 mt-2 line-clamp-2">{{ $audio->description }}</p>
                        @endif
                        <p class="text-xs text-base-content/35 mt-3">{{ $audio->created_at->diffForHumans() }}</p>
                    </a>
                @endforeach

                @foreach($recentVideos as $video)
                    <a href="{{ route('video.index') }}"
                       class="p-5 rounded-2xl border border-base-200 bg-base-100 hover:border-primary/40 transition">
                        <p class="text-xs text-base-content/40 uppercase tracking-widest font-medium">Video</p>
                        <p class="font-semibold mt-1">{{ $video->title }}</p>
                        @if($video->description)
                            <p class="text-sm text-base-content/55 mt-2 line-clamp-2">{{ $video->description }}</p>
                        @endif
                        <p class="text-xs text-base-content/35 mt-3">{{ $video->created_at->diffForHumans() }}</p>
                    </a>
                @endforeach

            </div>
        @endif

    </div>
</x-layouts.app>
