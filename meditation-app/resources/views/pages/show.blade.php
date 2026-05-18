<x-layouts.app :title="$post->title">
    <article class="max-w-3xl mx-auto px-5 py-16">
        <a href="{{ route('pages.index') }}" class="text-sm text-base-content/50 hover:text-primary">← Atpakaļ uz Rakstiem</a>

        <h1 class="brand-font text-4xl font-medium mt-6">{{ $post->title }}</h1>
        <p class="text-xs text-base-content/35 mt-2">
            Publicējis {{ $post->user->name }} · {{ $post->created_at->diffForHumans() }}
        </p>

        @if($post->tags->isNotEmpty())
            <div class="flex flex-wrap gap-1.5 mt-3">
                @foreach($post->tags as $tag)
                    <a href="{{ route('pages.index', ['tag' => $tag->slug]) }}"
                       class="badge badge-ghost badge-sm rounded-lg hover:badge-primary">{{ $tag->name }}</a>
                @endforeach
            </div>
        @endif

        <div class="prose mt-8 whitespace-pre-wrap text-base-content/80 leading-relaxed">
            {{ $post->body }}
        </div>

        @auth
            @if(auth()->user()->isAdmin())
                <div class="mt-10 flex gap-2">
                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-ghost btn-sm rounded-xl">Rediģēt</a>
                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                          onsubmit="return confirm('Dzēst šo rakstu?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-ghost btn-sm rounded-xl text-error">Dzēst</button>
                    </form>
                </div>
            @endif
        @endauth
    </article>
</x-layouts.app>
