<x-layouts.app title="Izveidot kontu">

    <section class="relative min-h-[calc(100vh-62px)] flex items-center justify-center px-5 py-16 overflow-hidden">

        <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
             style="background:
                radial-gradient(ellipse at 80% 10%, oklch(var(--p)/0.10) 0%, transparent 55%),
                radial-gradient(ellipse at 20% 90%, oklch(var(--a)/0.08) 0%, transparent 55%);">
        </div>

        <div class="relative w-full max-w-md">

            <div class="text-center mb-8">
                <p class="text-xs font-semibold uppercase tracking-widest text-primary/60 mb-3">Sāc savu ceļu</p>
                <h1 class="brand-font text-4xl sm:text-5xl font-medium leading-tight tracking-tight">
                    Izveido savu
                    <span class="italic text-primary/80">telpu.</span>
                </h1>
                <p class="mt-4 text-sm text-base-content/50 leading-relaxed">
                    Saglabā iecienītākās sesijas, seko savai praksei un veido miera ieradumu.
                </p>
            </div>

            <div class="rounded-3xl border border-base-200/80 bg-base-100 shadow-sm shadow-base-300/30 p-8 sm:p-10">

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-semibold uppercase tracking-widest text-base-content/60 mb-2">
                            Vārds
                        </label>
                        <input id="name" name="name" type="text" required autofocus autocomplete="name"
                               value="{{ old('name') }}"
                               class="input input-bordered w-full rounded-2xl h-12 px-5 bg-base-100 border-base-200 focus:border-primary/60 focus:outline-none focus:ring-2 focus:ring-primary/15 transition-all text-sm placeholder:text-base-content/30"
                               placeholder="Tavs vārds">
                        @error('name')
                            <p class="mt-2 text-xs text-error/80">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-widest text-base-content/60 mb-2">
                            E-pasts
                        </label>
                        <input id="email" name="email" type="email" required autocomplete="username"
                               value="{{ old('email') }}"
                               class="input input-bordered w-full rounded-2xl h-12 px-5 bg-base-100 border-base-200 focus:border-primary/60 focus:outline-none focus:ring-2 focus:ring-primary/15 transition-all text-sm placeholder:text-base-content/30"
                               placeholder="tu@piemērs.lv">
                        @error('email')
                            <p class="mt-2 text-xs text-error/80">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase tracking-widest text-base-content/60 mb-2">
                            Parole
                        </label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                               class="input input-bordered w-full rounded-2xl h-12 px-5 bg-base-100 border-base-200 focus:border-primary/60 focus:outline-none focus:ring-2 focus:ring-primary/15 transition-all text-sm placeholder:text-base-content/30"
                               placeholder="Vismaz 8 rakstzīmes">
                        @error('password')
                            <p class="mt-2 text-xs text-error/80">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-widest text-base-content/60 mb-2">
                            Apstiprini paroli
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                               class="input input-bordered w-full rounded-2xl h-12 px-5 bg-base-100 border-base-200 focus:border-primary/60 focus:outline-none focus:ring-2 focus:ring-primary/15 transition-all text-sm placeholder:text-base-content/30"
                               placeholder="Atkārto paroli">
                        @error('password_confirmation')
                            <p class="mt-2 text-xs text-error/80">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="btn btn-primary w-full rounded-2xl h-12 min-h-0 text-sm font-medium shadow-md shadow-primary/20 inline-flex items-center justify-center leading-none mt-2">
                        Izveidot kontu
                    </button>
                </form>

                <div class="relative my-7">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-base-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-base-100 px-3 text-xs uppercase tracking-widest text-base-content/35">Jau pievienojies</span>
                    </div>
                </div>

                <a href="{{ route('login') }}"
                   class="btn btn-ghost w-full rounded-2xl h-12 min-h-0 text-sm font-medium border border-base-200 hover:border-primary/30 hover:bg-primary/5 inline-flex items-center justify-center leading-none">
                    Pieteikties
                </a>

            </div>

            <p class="text-center mt-8 text-xs text-base-content/35">
                Veidojot kontu, tu piekrīti mūsu
                <a href="#" class="text-base-content/60 hover:text-primary transition-colors">Noteikumiem</a>
                un
                <a href="#" class="text-base-content/60 hover:text-primary transition-colors">Privātuma politikai</a>.
            </p>

        </div>
    </section>

</x-layouts.app>
