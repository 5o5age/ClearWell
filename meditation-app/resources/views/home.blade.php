<x-layouts.app title="Sveicināts">

    {{-- Hero --}}
    <section class="relative overflow-hidden min-h-[88vh] flex items-center">

        <div class="absolute inset-0" aria-hidden="true">
            <img src="{{ asset('images/hero_gray_forest.jpg') }}"
                 data-hero-img
                 alt=""
                 class="w-full h-full object-cover"
                 style="filter: blur(3px); transform: scale(1.04);">
            <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(0,0,0,0.45) 80%, rgba(0,0,0,0.55) 90%, rgba(0,0,0,0.7) 100%);"></div>
        </div>

        <div class="relative w-full max-w-7xl mx-auto px-5 pt-28 pb-24 flex flex-col items-center text-center">

            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 text-white/80 text-xs font-medium tracking-widest uppercase mb-8 border border-white/15 backdrop-blur-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-white/60 inline-block"></span>
                Tava klusuma patvēruma vieta
            </div>

            <h1 class="brand-font text-5xl sm:text-6xl md:text-7xl font-medium leading-[1.08] tracking-tight max-w-3xl text-white drop-shadow-md">
                Atrodi mieru<br>
                <span class="italic text-white/70">trokšņa vidū.</span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-white/55 max-w-xl leading-relaxed font-light drop-shadow-sm">
                Clearwell piedāvā vadītu audio, nomierinošu video, apzinātu lasīšanu
                un maigas spēles — pilnīgu telpu, kur elpot, atjaunoties un atgriezties pie sevis.
            </p>

            <div class="mt-16 flex flex-col items-center gap-2 text-white/30 text-xs">
                <span>Ritini, lai izpētītu</span>
                <div class="w-px h-8 bg-white/20 rounded-full"></div>
            </div>
        </div>
    </section>


    <section class="max-w-7xl mx-auto px-5 pt-20 pb-24">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- Audio --}}
            <a href="{{ route('audio.index') }}"
               class="group relative rounded-3xl border border-base-200/80 bg-base-100 p-7 hover:shadow-lg hover:shadow-base-300/40 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                     style="background: radial-gradient(ellipse at top left, oklch(var(--p)/0.06), transparent 65%);">
                </div>
                <div class="w-11 h-11 rounded-2xl bg-primary/10 flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                    </svg>
                </div>
                <h3 class="brand-font text-xl font-medium mb-2">Audio</h3>
                <p class="text-sm text-base-content/45 leading-relaxed">
                    Dabas skaņas, elpošanas vadlīnijas un dziļas relaksācijas ieraksti, lai nomierinātu prātu.
                </p>
                <div class="mt-5 flex items-center gap-1.5 text-xs font-medium text-primary/60 group-hover:text-primary transition-colors">
                    Izpētīt
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 translate-x-0 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            {{-- Video --}}
            <a href="{{ route('video.index') }}"
               class="group relative rounded-3xl border border-base-200/80 bg-base-100 p-7 hover:shadow-lg hover:shadow-base-300/40 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                     style="background: radial-gradient(ellipse at top left, oklch(var(--s)/0.06), transparent 65%);">
                </div>
                <div class="w-11 h-11 rounded-2xl bg-secondary/15 flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.069A1 1 0 0121 8.845v6.31a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="brand-font text-xl font-medium mb-2">Video</h3>
                <p class="text-sm text-base-content/45 leading-relaxed">
                    Maigi vizuāli, vadītas kustības un iegremdējošas sesijas, lai noenkurotu tevi tagadnē.
                </p>
                <div class="mt-5 flex items-center gap-1.5 text-xs font-medium text-secondary/60 group-hover:text-secondary transition-colors">
                    Izpētīt
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 translate-x-0 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            {{-- Pages --}}
            <a href="{{ route('pages.index') }}"
               class="group relative rounded-3xl border border-base-200/80 bg-base-100 p-7 hover:shadow-lg hover:shadow-base-300/40 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                     style="background: radial-gradient(ellipse at top left, oklch(var(--a)/0.06), transparent 65%);">
                </div>
                <div class="w-11 h-11 rounded-2xl bg-accent/15 flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="brand-font text-xl font-medium mb-2">Raksti</h3>
                <p class="text-sm text-base-content/45 leading-relaxed">
                    Pārdomāti raksti, prakses un vadīta lasīšana, lai padziļinātu tavu apzinātības ceļu.
                </p>
                <div class="mt-5 flex items-center gap-1.5 text-xs font-medium text-accent/60 group-hover:text-accent transition-colors">
                    Izpētīt
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 translate-x-0 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            {{-- Games --}}
            <a href="{{ route('games.index') }}"
               class="group relative rounded-3xl border border-base-200/80 bg-base-100 p-7 hover:shadow-lg hover:shadow-base-300/40 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                     style="background: radial-gradient(ellipse at top left, oklch(var(--su)/0.06), transparent 65%);">
                </div>
                <div class="w-11 h-11 rounded-2xl bg-success/15 flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                    </svg>
                </div>
                <h3 class="brand-font text-xl font-medium mb-2">Spēles</h3>
                <p class="text-sm text-base-content/45 leading-relaxed">
                    Rotaļīgi, viegli vingrinājumi, kas trenē koncentrēšanos, mieru un klātbūtni maigā mijiedarbībā.
                </p>
                <div class="mt-5 flex items-center gap-1.5 text-xs font-medium text-success/60 group-hover:text-success transition-colors">
                    Izpētīt
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 translate-x-0 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

        </div>
    </section>


    <section class="border-y border-base-200/50 bg-base-200/20">
        <div class="max-w-3xl mx-auto px-5 py-20 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto mb-6 text-primary/25" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
            </svg>
            <blockquote class="brand-font text-2xl sm:text-3xl font-medium leading-snug text-base-content/75 italic">
                "Tev nav jākontrolē savas domas.<br class="hidden sm:block">
                Tev tikai jāpārstāj ļaut tām kontrolēt sevi."
            </blockquote>
            <p class="mt-5 text-sm text-base-content/35 tracking-wide">— Dan Millman</p>
        </div>
    </section>


    <section class="max-w-7xl mx-auto px-5 py-24">

        <div class="text-center mb-14">
            <p class="text-xs font-semibold uppercase tracking-widest text-primary/60 mb-3">Vienkārši pēc dizaina</p>
            <h2 class="brand-font text-3xl sm:text-4xl font-medium text-base-content">Kā Clearwell darbojas</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 relative">

            <div class="hidden md:block absolute top-8 left-[calc(16.66%+2rem)] right-[calc(50%+2rem)] h-px bg-base-200/80 pointer-events-none"></div>
            <div class="hidden md:block absolute top-8 left-[calc(50%+2rem)] right-[calc(16.66%+2rem)] h-px bg-base-200/80 pointer-events-none"></div>

            {{-- Step 1 --}}
            <div class="flex flex-col items-center text-center">
                <div class="relative w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-6 ring-4 ring-base-100 z-10">
                    <span class="brand-font text-2xl font-medium text-primary">1</span>
                </div>
                <h3 class="font-semibold text-base-content/80 mb-2">Izvēlies savu praksi</h3>
                <p class="text-sm text-base-content/45 leading-relaxed max-w-xs">
                    Pārlūko audio sesijas, video, rakstus vai apzinātās spēles — to, kas atbilst tavam noskaņojumam tagad.
                </p>
            </div>

            {{-- Step 2 --}}
            <div class="flex flex-col items-center text-center">
                <div class="relative w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-6 ring-4 ring-base-100 z-10">
                    <span class="brand-font text-2xl font-medium text-primary">2</span>
                </div>
                <h3 class="font-semibold text-base-content/80 mb-2">Atvēli sev brīdi</h3>
                <p class="text-sm text-base-content/45 leading-relaxed max-w-xs">
                    Pat piecas minūtes ir vērtīgas. Aizver cilni, noliec telefonu un dāvini šo brīdi sev.
                </p>
            </div>

            {{-- Step 3 --}}
            <div class="flex flex-col items-center text-center">
                <div class="relative w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mb-6 ring-4 ring-base-100 z-10">
                    <span class="brand-font text-2xl font-medium text-primary">3</span>
                </div>
                <h3 class="font-semibold text-base-content/80 mb-2">Atgriezies atjaunots</h3>
                <p class="text-sm text-base-content/45 leading-relaxed max-w-xs">
                    Atgriezies pie savas dienas ar dziļāku elpu, skaidrāku prātu un nedaudz vairāk telpu sevī.
                </p>
            </div>

        </div>
    </section>


    @guest
    <section class="max-w-7xl mx-auto px-5 pb-24">
        <div class="relative rounded-3xl overflow-hidden border border-primary/15 bg-primary/6 px-8 py-14 text-center">
            <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
                 style="background: radial-gradient(ellipse at 50% 0%, oklch(var(--p)/0.12) 0%, transparent 65%);">
            </div>
            <p class="relative text-xs font-semibold uppercase tracking-widest text-primary/60 mb-4">Bez maksas</p>
            <h2 class="relative brand-font text-3xl sm:text-4xl font-medium mb-4 text-base-content">
                Gatavs sākt?
            </h2>
            <p class="relative text-base text-base-content/50 max-w-md mx-auto mb-8 leading-relaxed">
                Izveido bezmaksas kontu, lai saglabātu iecienītākās sesijas, sekotu līdzi savai praksei un veidotu miera ieradumu.
            </p>
            <div class="relative flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('register') }}"
                   class="btn btn-primary rounded-2xl px-8 h-12 min-h-0 text-sm font-medium shadow-md shadow-primary/20">
                    Izveidot bezmaksas kontu
                </a>
                <a href="{{ route('login') }}"
                   class="btn btn-ghost rounded-2xl px-8 h-12 min-h-0 text-sm font-medium text-base-content/55">
                    Man jau ir konts
                </a>
            </div>
        </div>
    </section>
    @endguest

</x-layouts.app>
