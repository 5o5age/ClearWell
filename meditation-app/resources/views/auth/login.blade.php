<x-layouts.app title="Sign In">

    <section class="relative min-h-[calc(100vh-62px)] flex items-center justify-center px-5 py-16 overflow-hidden">

        {{-- Soft ambient background --}}
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true"
             style="background:
                radial-gradient(ellipse at 20% 10%, oklch(var(--p)/0.10) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 90%, oklch(var(--s)/0.08) 0%, transparent 55%);">
        </div>

        <div class="relative w-full max-w-md">

            {{-- Heading --}}
            <div class="text-center mb-8">
                <p class="text-xs font-semibold uppercase tracking-widest text-primary/60 mb-3">Welcome back</p>
                <h1 class="brand-font text-4xl sm:text-5xl font-medium leading-tight tracking-tight">
                    Sign in to
                    <span class="italic text-primary/80">Clearwell.</span>
                </h1>
                <p class="mt-4 text-sm text-base-content/50 leading-relaxed">
                    Return to your sanctuary. Your practice is waiting.
                </p>
            </div>

            {{-- Card --}}
            <div class="rounded-3xl border border-base-200/80 bg-base-100 shadow-sm shadow-base-300/30 p-8 sm:p-10">

                {{-- Session status (e.g. password reset sent) --}}
                @if (session('status'))
                    <div class="mb-6 rounded-2xl bg-success/10 border border-success/20 px-4 py-3 text-sm text-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-widest text-base-content/60 mb-2">
                            Email
                        </label>
                        <input id="email" name="email" type="email" required autofocus autocomplete="username"
                               value="{{ old('email') }}"
                               class="input input-bordered w-full rounded-2xl h-12 px-5 bg-base-100 border-base-200 focus:border-primary/60 focus:outline-none focus:ring-2 focus:ring-primary/15 transition-all text-sm placeholder:text-base-content/30"
                               placeholder="you@example.com">
                        @error('email')
                            <p class="mt-2 text-xs text-error/80">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-semibold uppercase tracking-widest text-base-content/60">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="text-xs text-primary/70 hover:text-primary transition-colors">
                                    Forgot?
                                </a>
                            @endif
                        </div>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                               class="input input-bordered w-full rounded-2xl h-12 px-5 bg-base-100 border-base-200 focus:border-primary/60 focus:outline-none focus:ring-2 focus:ring-primary/15 transition-all text-sm placeholder:text-base-content/30"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-xs text-error/80">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <label for="remember_me" class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input id="remember_me" name="remember" type="checkbox"
                               class="checkbox checkbox-sm checkbox-primary rounded-md">
                        <span class="text-sm text-base-content/60">Remember me on this device</span>
                    </label>

                    {{-- Submit --}}
                    <button type="submit"
                            class="btn btn-primary w-full rounded-2xl h-12 min-h-0 text-sm font-medium shadow-md shadow-primary/20 inline-flex items-center justify-center leading-none mt-2">
                        Sign In
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative my-7">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-base-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-base-100 px-3 text-xs uppercase tracking-widest text-base-content/35">New here</span>
                    </div>
                </div>

                {{-- Register link --}}
                <a href="{{ route('register') }}"
                   class="btn btn-ghost w-full rounded-2xl h-12 min-h-0 text-sm font-medium border border-base-200 hover:border-primary/30 hover:bg-primary/5 inline-flex items-center justify-center leading-none">
                    Create a free account
                </a>

            </div>

            {{-- Footer note --}}
            <p class="text-center mt-8 text-xs text-base-content/35">
                By signing in, you agree to our
                <a href="#" class="text-base-content/60 hover:text-primary transition-colors">Terms</a>
                and
                <a href="#" class="text-base-content/60 hover:text-primary transition-colors">Privacy Policy</a>.
            </p>

        </div>
    </section>

</x-layouts.app>
