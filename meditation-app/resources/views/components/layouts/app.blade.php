<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Theme bootstrap — runs before body paints so there's no flash --}}
    <script>
        window.clearwellThemes = {
            forest:   { label: 'Dark Forest',   hero: 'hero_dark_forest.jpg' },
            retro:    { label: 'Light Sunrise', hero: 'hero_light_sunrise.jpg' },
            lemonade: { label: 'Light Plains',  hero: 'hero_light_plains.jpg' },
            night:    { label: 'Blue Mountains',hero: 'hero_dark_blue_mountains.jpg' },
        };
        window.setClearwellTheme = function(name) {
            if (!window.clearwellThemes[name]) name = 'forest';
            document.documentElement.setAttribute('data-theme', name);
            localStorage.setItem('clearwell-theme', name);
            document.querySelectorAll('[data-hero-img]').forEach(function(el){
                el.src = '/images/' + window.clearwellThemes[name].hero;
            });
            window.dispatchEvent(new CustomEvent('clearwell-theme-changed', { detail: { theme: name } }));
        };
        (function(){
            var saved = localStorage.getItem('clearwell-theme');
            if (!saved || !window.clearwellThemes[saved]) saved = 'forest';
            document.documentElement.setAttribute('data-theme', saved);
            document.addEventListener('DOMContentLoaded', function(){
                window.setClearwellTheme(saved);
            });
        })();
    </script>

    <title>{{ config('app.name', 'Clearwell') }} — {{ $title ?? 'Find Your Calm' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .brand-font { font-family: 'Playfair Display', serif; }

        /* Frosted-glass navbar */
        .nav-blur {
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            background-color: oklch(var(--b1) / 0.82);
        }

        /* Animated underline nav links */
        .nav-link {
            position: relative;
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.025em;
            color: oklch(var(--bc) / 0.65);
            transition: color 0.2s ease;
            padding-bottom: 2px;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 1.5px;
            border-radius: 2px;
            background-color: oklch(var(--p));
            transition: width 0.25s ease;
        }
        .nav-link:hover { color: oklch(var(--bc)); }
        .nav-link:hover::after { width: 100%; }
        .nav-link.active { color: oklch(var(--p)); }
        .nav-link.active::after { width: 100%; }

        /* Footer */
        .footer-divider { border-color: oklch(var(--bc) / 0.08); }

        /* Smooth page feel */
        * { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-base-100 text-base-content">

    {{-- ═══════════════════════════════════════════════════════
         NAVBAR
    ═══════════════════════════════════════════════════════ --}}
    <header class="sticky top-0 z-50 nav-blur">
        <div class="max-w-7xl mx-auto px-5 h-[62px] grid grid-cols-[1fr_auto_1fr] items-center">

            {{-- ── Logo (far left) ─────────────────────────────── --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-8 h-8 rounded-full bg-primary/15 flex items-center justify-center ring-1 ring-primary/20 group-hover:ring-primary/40 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[17px] h-[17px] text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="6"/>
                            <circle cx="12" cy="12" r="2"/>
                            <line x1="12" y1="2" x2="12" y2="6"/>
                            <line x1="12" y1="18" x2="12" y2="22"/>
                            <line x1="2" y1="12" x2="6" y2="12"/>
                            <line x1="18" y1="12" x2="22" y2="12"/>
                        </svg>
                    </div>
                    <span class="brand-font text-[1.15rem] font-medium tracking-wide leading-none">Clearwell</span>
                </a>
            </div>

            {{-- ── Centre nav (desktop only) ───────────────────── --}}
            <nav class="hidden md:flex items-center gap-7">
                <a href="{{ route('home') }}"
                   class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    Home
                </a>
                <a href="{{ route('audio.index') }}"
                   class="nav-link {{ request()->routeIs('audio.*') ? 'active' : '' }}">
                    Audio
                </a>
                <a href="{{ route('video.index') }}"
                   class="nav-link {{ request()->routeIs('video.*') ? 'active' : '' }}">
                    Video
                </a>
                <a href="{{ route('pages.index') }}"
                   class="nav-link {{ request()->routeIs('pages.*') ? 'active' : '' }}">
                    Pages
                </a>
                <a href="{{ route('games.index') }}"
                   class="nav-link {{ request()->routeIs('games.*') ? 'active' : '' }}">
                    Games
                </a>
            </nav>

            {{-- ── Profile / Auth (far right) ──────────────────── --}}
            <div class="flex items-center justify-end gap-2">

                {{-- Theme switcher --}}
                <div x-data="{
                        current: (localStorage.getItem('clearwell-theme') || 'forest'),
                        themes: Object.keys(window.clearwellThemes),
                        labelFor(name){ return window.clearwellThemes[name].label; },
                        pick(name){ this.current = name; window.setClearwellTheme(name); document.activeElement && document.activeElement.blur(); },
                     }"
                     x-init="window.addEventListener('clearwell-theme-changed', e => current = e.detail.theme)"
                     class="dropdown dropdown-end">
                    <div tabindex="0" role="button"
                         class="btn btn-ghost btn-sm rounded-xl h-9 min-h-0 px-2.5 inline-flex items-center gap-1.5"
                         aria-label="Change theme">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-[17px] h-[17px] opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 opacity-40 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                    <ul tabindex="0" class="dropdown-content menu menu-sm bg-base-100 rounded-2xl shadow-xl border border-base-200/80 z-50 mt-3 w-56 p-2 gap-0.5">
                        <li class="px-3 pt-2 pb-2 border-b border-base-200/60 mb-1">
                            <span class="text-xs text-base-content/45 font-medium uppercase tracking-widest">Theme</span>
                        </li>
                        <template x-for="name in themes" :key="name">
                            <li>
                                <button type="button" @click="pick(name)"
                                        class="rounded-xl text-sm py-2 flex items-center justify-between w-full"
                                        :class="current === name ? 'bg-primary/10 text-primary' : ''">
                                    <span x-text="labelFor(name)"></span>
                                    <svg x-show="current === name" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>

                @auth
                    {{-- Logged-in: avatar + name dropdown --}}
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button"
                             class="btn btn-ghost btn-sm rounded-xl flex items-center gap-2 pl-2 pr-3 h-9 min-h-0">
                            <div class="w-7 h-7 rounded-full bg-primary/20 flex items-center justify-center text-primary font-semibold text-xs shrink-0 ring-1 ring-primary/30">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium hidden sm:inline max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 opacity-40 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <ul tabindex="0" class="dropdown-content menu menu-sm bg-base-100 rounded-2xl shadow-xl border border-base-200/80 z-50 mt-3 w-56 p-2 gap-0.5">
                            <li class="px-3 pt-2 pb-2.5 border-b border-base-200/60 mb-1">
                                <span class="text-xs text-base-content/45 font-medium block">Signed in as</span>
                                <span class="text-sm font-semibold text-base-content/80 block truncate">{{ auth()->user()->name }}</span>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}" class="rounded-xl text-sm py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile Settings
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard') }}" class="rounded-xl text-sm py-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Dashboard
                                </a>
                            </li>
                            <li class="border-t border-base-200/60 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left rounded-xl text-sm py-2 text-error/70 hover:text-error hover:bg-error/8 transition-colors flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                @else
                    {{-- Guest: Sign In + Register --}}
                    <a href="{{ route('login') }}"
                       class="btn btn-ghost btn-sm rounded-xl font-medium text-sm h-9 min-h-0 hidden sm:inline-flex items-center justify-center leading-none">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       class="btn btn-primary btn-sm rounded-xl font-medium text-sm px-5 h-9 min-h-0 shadow-sm inline-flex items-center justify-center leading-none">
                        Register
                    </a>
                @endauth

                {{-- Mobile hamburger --}}
                <div class="dropdown dropdown-end md:hidden ml-0.5">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                    </div>
                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-2xl shadow-xl border border-base-200/80 z-50 mt-3 w-60 p-2 gap-0.5">
                        <li><a href="{{ route('home') }}" class="rounded-xl">Home</a></li>
                        <li><a href="{{ route('audio.index') }}" class="rounded-xl">Audio</a></li>
                        <li><a href="{{ route('video.index') }}" class="rounded-xl">Video</a></li>
                        <li><a href="{{ route('pages.index') }}" class="rounded-xl">Pages</a></li>
                        <li><a href="{{ route('games.index') }}" class="rounded-xl">Games</a></li>
                        <li class="divider my-1"></li>
                        @auth
                            <li><a href="{{ route('profile.edit') }}" class="rounded-xl">Profile</a></li>
                            <li><a href="{{ route('dashboard') }}" class="rounded-xl">Dashboard</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left rounded-xl text-error/70">Sign Out</button>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}" class="rounded-xl">Sign In</a></li>
                            <li><a href="{{ route('register') }}" class="rounded-xl font-medium text-primary">Register</a></li>
                        @endauth
                    </ul>
                </div>

            </div>{{-- /auth-right --}}

        </div>{{-- /grid --}}
    </header>


    {{-- ═══════════════════════════════════════════════════════
         FLASH MESSAGES
    ═══════════════════════════════════════════════════════ --}}
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-5 pt-4 w-full">
            <div class="alert alert-success rounded-2xl shadow-sm text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-5 pt-4 w-full">
            <div class="alert alert-error rounded-2xl shadow-sm text-sm">
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif


    {{-- ═══════════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════════ --}}
    <main class="flex-1">
        {{ $slot }}
    </main>


    {{-- ═══════════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════════ --}}
    <footer class="border-t border-base-200/50 bg-base-200/25 mt-auto">
        <div class="max-w-7xl mx-auto px-5 py-14">

            {{-- Top row --}}
            <div class="flex flex-col md:flex-row justify-between gap-12">

                {{-- Brand blurb --}}
                <div class="max-w-[280px]">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-4">
                        <div class="w-7 h-7 rounded-full bg-primary/15 flex items-center justify-center ring-1 ring-primary/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-[14px] h-[14px] text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="12" cy="12" r="6"/>
                                <circle cx="12" cy="12" r="2"/>
                                <line x1="12" y1="2" x2="12" y2="6"/>
                                <line x1="12" y1="18" x2="12" y2="22"/>
                                <line x1="2" y1="12" x2="6" y2="12"/>
                                <line x1="18" y1="12" x2="22" y2="12"/>
                            </svg>
                        </div>
                        <span class="brand-font text-[1.05rem] font-medium">Clearwell</span>
                    </a>
                    <p class="text-sm text-base-content/45 leading-relaxed">
                        A quiet space for the mind — gentle audio, calming video, and mindful play, whenever you need stillness.
                    </p>
                </div>

                {{-- Link columns --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-8 text-sm">

                    <div>
                        <p class="font-semibold text-base-content/70 mb-3 text-xs uppercase tracking-widest">Explore</p>
                        <ul class="space-y-2.5 text-base-content/45">
                            <li><a href="{{ route('audio.index') }}" class="hover:text-base-content transition-colors duration-150">Audio</a></li>
                            <li><a href="{{ route('video.index') }}" class="hover:text-base-content transition-colors duration-150">Video</a></li>
                            <li><a href="{{ route('pages.index') }}" class="hover:text-base-content transition-colors duration-150">Pages</a></li>
                            <li><a href="{{ route('games.index') }}" class="hover:text-base-content transition-colors duration-150">Games</a></li>
                        </ul>
                    </div>

                    <div>
                        <p class="font-semibold text-base-content/70 mb-3 text-xs uppercase tracking-widest">Account</p>
                        <ul class="space-y-2.5 text-base-content/45">
                            @auth
                                <li><a href="{{ route('dashboard') }}" class="hover:text-base-content transition-colors duration-150">Dashboard</a></li>
                                <li><a href="{{ route('profile.edit') }}" class="hover:text-base-content transition-colors duration-150">Profile</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="hover:text-base-content transition-colors duration-150">Sign In</a></li>
                                <li><a href="{{ route('register') }}" class="hover:text-base-content transition-colors duration-150">Register</a></li>
                            @endauth
                        </ul>
                    </div>

                    <div>
                        <p class="font-semibold text-base-content/70 mb-3 text-xs uppercase tracking-widest">Company</p>
                        <ul class="space-y-2.5 text-base-content/45">
                            <li><a href="#" class="hover:text-base-content transition-colors duration-150">About</a></li>
                            <li><a href="#" class="hover:text-base-content transition-colors duration-150">Blog</a></li>
                            <li><a href="#" class="hover:text-base-content transition-colors duration-150">Contact</a></li>
                        </ul>
                    </div>

                    <div>
                        <p class="font-semibold text-base-content/70 mb-3 text-xs uppercase tracking-widest">Legal</p>
                        <ul class="space-y-2.5 text-base-content/45">
                            <li><a href="#" class="hover:text-base-content transition-colors duration-150">Privacy</a></li>
                            <li><a href="#" class="hover:text-base-content transition-colors duration-150">Terms</a></li>
                            <li><a href="#" class="hover:text-base-content transition-colors duration-150">Cookies</a></li>
                        </ul>
                    </div>

                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="footer-divider border-t mt-12 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-base-content/30">
                <p>© {{ date('Y') }} Clearwell. All rights reserved.</p>
                <p class="brand-font italic text-base-content/25">Made with care, for quieter minds.</p>
            </div>

        </div>
    </footer>

</body>
</html>
