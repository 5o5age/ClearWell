<x-layouts.app title="Lapa nav atrasta">

    <style>
        @keyframes clearwell-ripple {
            0%   { transform: scale(0.6); opacity: 0; }
            20%  { opacity: 0.55; }
            100% { transform: scale(2.2); opacity: 0; }
        }
        .ripple-ring {
            position: absolute;
            inset: 0;
            margin: auto;
            border-radius: 9999px;
            border: 1px solid oklch(var(--p) / 0.35);
            animation: clearwell-ripple 6s ease-out infinite;
        }
        .ripple-ring.delay-1 { animation-delay: 2s; }
        .ripple-ring.delay-2 { animation-delay: 4s; }

        @keyframes clearwell-drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%      { transform: translate(10px, -12px) scale(1.05); }
        }
        .drift-aura { animation: clearwell-drift 9s ease-in-out infinite; }

        @keyframes clearwell-float {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-6px); }
        }
        .float-slow { animation: clearwell-float 5s ease-in-out infinite; }
    </style>

    <section class="relative overflow-hidden min-h-[85vh] flex items-center bg-base-100">

        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[900px] rounded-full drift-aura"
                 style="background: radial-gradient(circle, oklch(var(--p)/0.12) 0%, transparent 60%);"></div>
            <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] rounded-full"
                 style="background: radial-gradient(circle, oklch(var(--s)/0.08) 0%, transparent 60%);"></div>
            <div class="absolute -top-20 -right-20 w-[500px] h-[500px] rounded-full"
                 style="background: radial-gradient(circle, oklch(var(--a)/0.07) 0%, transparent 60%);"></div>
        </div>

        <div class="absolute inset-0 pointer-events-none opacity-[0.35]"
             aria-hidden="true"
             style="background-image:
                 linear-gradient(oklch(var(--bc)/0.04) 1px, transparent 1px),
                 linear-gradient(90deg, oklch(var(--bc)/0.04) 1px, transparent 1px);
                 background-size: 48px 48px;
                 mask-image: radial-gradient(ellipse at center, black 30%, transparent 75%);
                 -webkit-mask-image: radial-gradient(ellipse at center, black 30%, transparent 75%);"></div>

        <div class="relative w-full max-w-3xl mx-auto px-5 py-24 flex flex-col items-center text-center">

            <div class="relative w-40 h-40 mb-8 flex items-center justify-center">
                <span class="ripple-ring"></span>
                <span class="ripple-ring delay-1"></span>
                <span class="ripple-ring delay-2"></span>

                <div class="relative w-20 h-20 rounded-full bg-primary/10 ring-1 ring-primary/25 flex items-center justify-center float-slow backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-primary/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3c2 3 2 6 0 9-2-3-2-6 0-9z"/>
                        <path d="M5 9c3 1 5 3 6 6-3-1-5-3-6-6z"/>
                        <path d="M19 9c-3 1-5 3-6 6 3-1 5-3 6-6z"/>
                        <path d="M4 16c3 0 6 1 8 3"/>
                        <path d="M20 16c-3 0-6 1-8 3"/>
                    </svg>
                </div>
            </div>

            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-base-200/60 text-base-content/55 text-[11px] font-medium tracking-[0.2em] uppercase mb-8 border border-base-200">
                <span class="w-1.5 h-1.5 rounded-full bg-primary/50 inline-block"></span>
                Kļūda 404 &nbsp;·&nbsp; Lapa nav atrasta
            </div>

            <h1 class="brand-font text-4xl sm:text-5xl font-medium leading-tight tracking-tight text-base-content mb-5">
                Šis ceļš ir <span class="italic text-primary/80">apklusis.</span>
            </h1>

            <p class="text-base sm:text-lg text-base-content/55 max-w-lg leading-relaxed font-light mb-10">
                Meklētā lapa šeit nav — varbūt tā ir aizpeldējusi prom, varbūt tās nekad nav bijis.
                Ieelpo dziļi, un atradīsim ceļu atpakaļ.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-3 mb-14">
                <a href="{{ route('home') }}"
                   class="btn btn-primary rounded-2xl px-7 h-12 min-h-0 text-sm font-medium shadow-md shadow-primary/20 inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Atgriezties sākumā
                </a>
                <a href="{{ route('audio.index') }}"
                   class="btn btn-ghost rounded-2xl px-6 h-12 min-h-0 text-sm font-medium text-base-content/65 hover:text-base-content border border-base-200 hover:bg-base-200/50">
                    Izpētīt sesijas
                </a>
            </div>

            <div class="w-full max-w-lg">
                <p class="text-[10px] font-semibold uppercase tracking-[0.25em] text-base-content/35 mb-4">Vai dodies uz</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <a href="{{ route('audio.index') }}"
                       class="group rounded-xl border border-base-200/80 bg-base-100 py-3 px-3 text-xs font-medium text-base-content/55 hover:text-primary hover:border-primary/30 hover:bg-primary/5 transition-all">
                        Audio
                    </a>
                    <a href="{{ route('video.index') }}"
                       class="group rounded-xl border border-base-200/80 bg-base-100 py-3 px-3 text-xs font-medium text-base-content/55 hover:text-secondary hover:border-secondary/30 hover:bg-secondary/5 transition-all">
                        Video
                    </a>
                    <a href="{{ route('pages.index') }}"
                       class="group rounded-xl border border-base-200/80 bg-base-100 py-3 px-3 text-xs font-medium text-base-content/55 hover:text-accent hover:border-accent/30 hover:bg-accent/5 transition-all">
                        Raksti
                    </a>
                    <a href="{{ route('games.index') }}"
                       class="group rounded-xl border border-base-200/80 bg-base-100 py-3 px-3 text-xs font-medium text-base-content/55 hover:text-success hover:border-success/30 hover:bg-success/5 transition-all">
                        Spēles
                    </a>
                </div>
            </div>

            <div class="mt-14 pt-8 border-t border-base-200/70 w-full max-w-md">
                <p class="brand-font italic text-sm text-base-content/40 leading-relaxed">
                    "Ne visi, kas klejo, ir apmaldījušies."
                </p>
            </div>

        </div>
    </section>

</x-layouts.app>
