<x-layouts.app title="Raksti">
    <div class="max-w-7xl mx-auto px-5 py-16">
        <div class="flex items-start justify-between gap-6 flex-wrap">
            <div>
                <h1 class="brand-font text-3xl font-medium">Raksti</h1>
                <p class="mt-2 text-base-content/50">Raksti, ceļveži un lasāmviela.</p>
            </div>

            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm rounded-xl">
                        + Jauns raksts
                    </a>
                @endif
            @endauth
        </div>

        @if($posts->isEmpty())
            <p class="mt-12 text-base-content/40 italic">Vēl nav publicēts neviens raksts.</p>
        @else
            <div class="mt-10 grid gap-5">
                @foreach($posts as $post)
                    <article class="relative p-5 rounded-2xl border border-base-200 bg-base-100 hover:border-primary/40 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h2 class="font-semibold text-xl">
                                    <a href="{{ route('pages.show', $post) }}"
                                       class="hover:text-primary transition-colors after:absolute after:inset-0 after:content-['']">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                <p class="text-xs text-base-content/35 mt-1">
                                    Publicējis {{ $post->user->name }} · {{ $post->created_at->diffForHumans() }}
                                </p>
                                <p class="text-sm text-base-content/60 mt-3 line-clamp-3">{{ \Illuminate\Support\Str::limit($post->body, 220) }}</p>
                            </div>

                            @auth
                                @if(auth()->user()->isAdmin())
                                    <div class="relative z-10 flex flex-col gap-2 shrink-0">
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-ghost btn-xs rounded-lg">Rediģēt</a>
                                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                                              onsubmit="return confirm('Dzēst šo rakstu?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-xs rounded-lg text-error">Dzēst</button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
