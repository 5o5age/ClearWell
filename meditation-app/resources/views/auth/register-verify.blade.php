<x-layouts.app title="Apstiprini e-pastu">

    <section class="relative min-h-[calc(100vh-62px)] flex items-center justify-center px-5 py-16 overflow-hidden">

        <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
             style="background:
                radial-gradient(ellipse at 80% 10%, oklch(var(--p)/0.10) 0%, transparent 55%),
                radial-gradient(ellipse at 20% 90%, oklch(var(--a)/0.08) 0%, transparent 55%);">
        </div>

        <div class="relative w-full max-w-md">

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary/15 ring-1 ring-primary/30 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="brand-font text-4xl font-medium leading-tight tracking-tight">
                    Pārbaudi savu
                    <span class="italic text-primary/80">iesūtni.</span>
                </h1>
                <p class="mt-4 text-sm text-base-content/60 leading-relaxed">
                    Nosūtījām 6 ciparu apstiprinājuma kodu uz
                    <span class="font-semibold text-base-content">{{ $email }}</span>.
                    Ievadi to zemāk, lai pabeigtu konta izveidi.
                </p>
            </div>

            @if (session('success'))
                <div class="alert alert-success rounded-2xl mb-4 text-sm">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error rounded-2xl mb-4 text-sm">{{ session('error') }}</div>
            @endif

            <div class="rounded-3xl border border-base-200/80 bg-base-100 shadow-sm shadow-base-300/30 p-8 sm:p-10">

                <form method="POST" action="{{ route('register.verify') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="code" class="block text-xs font-semibold uppercase tracking-widest text-base-content/60 mb-2">
                            Apstiprinājuma kods
                        </label>
                        <input id="code" name="code" type="text"
                               inputmode="numeric" pattern="[0-9]*" maxlength="6"
                               required autofocus autocomplete="one-time-code"
                               class="input input-bordered w-full rounded-2xl h-14 px-5 bg-base-100 border-base-200 focus:border-primary/60 focus:outline-none focus:ring-2 focus:ring-primary/15 transition-all text-center text-2xl font-mono tracking-[0.5em] placeholder:text-base-content/20"
                               placeholder="000000">
                        @error('code')
                            <p class="mt-2 text-xs text-error/80">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="btn btn-primary w-full rounded-2xl h-12 min-h-0 text-sm font-medium shadow-md shadow-primary/20 inline-flex items-center justify-center leading-none">
                        Apstiprināt un izveidot kontu
                    </button>
                </form>

                <form method="POST" action="{{ route('register.resend') }}" class="mt-5 text-center">
                    @csrf
                    <button type="submit" class="text-sm text-base-content/50 hover:text-primary transition-colors">
                        Nesaņēmi? Nosūtīt jaunu kodu
                    </button>
                </form>

            </div>

            <p class="text-center mt-6 text-xs text-base-content/40">
                Kods derīgs 10 minūtes.
            </p>
            <form method="POST" action="{{ route('register.cancel') }}" class="text-center mt-2">
                @csrf
                <button type="submit" class="text-xs text-base-content/60 hover:text-primary transition-colors">
                    Izmantot citu e-pastu
                </button>
            </form>

        </div>
    </section>

</x-layouts.app>
