<x-layouts.app :title="$title ?? 'Game'">

    <section class="max-w-4xl mx-auto px-5 py-16">

        {{-- Back link --}}
        <a href="{{ route('games.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-medium text-base-content/45 hover:text-primary transition-colors mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to games
        </a>

        {{-- Heading --}}
        <div class="mb-10">
            <p class="text-xs font-semibold uppercase tracking-widest text-primary/60 mb-3">Mindful play</p>
            <h1 class="brand-font text-4xl sm:text-5xl font-medium leading-tight tracking-tight">
                {{ $title ?? ucwords(str_replace('-', ' ', $slug)) }}
            </h1>
        </div>

        {{-- Game mount point. The JS bootstrap finds [data-game] and replaces the fallback. --}}
        <div id="game-root"
             data-game="{{ $slug }}"
             class="rounded-3xl border border-base-200/80 bg-base-200/30 min-h-[480px] flex items-center justify-center overflow-hidden">
            <p class="text-sm text-base-content/35">Game coming soon.</p>
        </div>

    </section>

</x-layouts.app>
