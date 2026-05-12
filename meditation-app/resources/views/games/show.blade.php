@php
    $titles = [
        'word-stream'     => 'Vārdu plūsma',
        'breath-flow'     => 'Elpas plūsma',
        'zen-garden'      => 'Zen dārzs',
        'focus-ripples'   => 'Koncentrēšanās viļņi',
        'floating-leaves' => 'Peldošās lapas',
    ];
    $gameTitle = $titles[$slug] ?? ucwords(str_replace('-', ' ', $slug));
@endphp

<x-layouts.app :title="$gameTitle">

    <section class="max-w-4xl mx-auto px-5 py-16">

        <a href="{{ route('games.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-medium text-base-content/45 hover:text-primary transition-colors mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Atpakaļ uz spēlēm
        </a>

        <div class="mb-10">
            <p class="text-xs font-semibold uppercase tracking-widest text-primary/60 mb-3">Apzināta spēle</p>
            <h1 class="brand-font text-4xl sm:text-5xl font-medium leading-tight tracking-tight">
                {{ $gameTitle }}
            </h1>
        </div>

        <div id="game-root"
             data-game="{{ $slug }}"
             class="rounded-3xl border border-base-200/80 bg-base-200/30 min-h-[480px] flex items-center justify-center overflow-hidden">
            <p class="text-sm text-base-content/35">Spēle drīzumā.</p>
        </div>

    </section>

</x-layouts.app>
