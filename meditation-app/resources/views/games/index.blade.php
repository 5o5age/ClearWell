<x-layouts.app title="Games">

    @php
        // Temporary in-file list — easy to swap for a DB-backed model later.
        $games = [
            ['slug' => 'word-stream',     'title' => 'Word Stream',     'description' => 'Type the words as they drift by. No rush, no penalty.'],
            ['slug' => 'breath-flow',     'title' => 'Breath Flow',     'description' => 'A gentle breathing rhythm exercise.'],
            ['slug' => 'zen-garden',      'title' => 'Zen Garden',      'description' => 'Rake patterns into sand, slowly.'],
            ['slug' => 'focus-ripples',   'title' => 'Focus Ripples',   'description' => 'Tap to ripple, watch the water still.'],
            ['slug' => 'floating-leaves', 'title' => 'Floating Leaves', 'description' => 'Guide leaves along a quiet stream.'],
        ];
    @endphp

    <section class="max-w-5xl mx-auto px-5 py-16">

        {{-- Heading --}}
        <div class="mb-12">
            <p class="text-xs font-semibold uppercase tracking-widest text-primary/60 mb-3">Play gently</p>
            <h1 class="brand-font text-4xl sm:text-5xl font-medium leading-tight tracking-tight">Games</h1>
            <p class="mt-4 text-base text-base-content/50 max-w-xl leading-relaxed">
                Mindful, low-pressure exercises to train focus, calm, and presence through gentle interaction.
            </p>
        </div>

        {{-- 2-col grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach ($games as $game)
                <a href="{{ route('games.show', $game['slug']) }}"
                   class="group relative rounded-3xl border border-base-200/80 bg-base-100 p-7 min-h-[220px] flex flex-col hover:shadow-lg hover:shadow-base-300/40 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">

                    {{-- Soft hover glow --}}
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                         style="background: radial-gradient(ellipse at top left, oklch(var(--p)/0.06), transparent 65%);">
                    </div>

                    {{-- Image / thumbnail placeholder --}}
                    <div class="relative w-full aspect-[16/9] rounded-2xl bg-base-200/60 mb-5 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    {{-- Text --}}
                    <div class="relative flex-1 flex flex-col">
                        <h3 class="brand-font text-xl font-medium mb-1.5">{{ $game['title'] }}</h3>
                        <p class="text-sm text-base-content/45 leading-relaxed flex-1">
                            {{ $game['description'] }}
                        </p>

                        <div class="mt-5 flex items-center gap-1.5 text-xs font-medium text-primary/60 group-hover:text-primary transition-colors">
                            Play
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 translate-x-0 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

</x-layouts.app>
