<x-layouts.app title="Administratora panelis">
    <div class="max-w-7xl mx-auto px-5 py-16">
        <h1 class="brand-font text-3xl font-medium">Administratora panelis</h1>
        <p class="mt-2 text-base-content/50">Publicē un pārvaldi vietnes saturu.</p>

        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <a href="{{ route('admin.audios.create') }}" class="p-6 rounded-2xl border border-base-200 hover:border-primary/40 hover:bg-base-200/30 transition">
                <h2 class="font-semibold text-lg">+ Jauns audio</h2>
                <p class="text-sm text-base-content/50 mt-1">Augšupielādēt mp3, wav, ogg vai m4a.</p>
            </a>
            <a href="{{ route('admin.videos.create') }}" class="p-6 rounded-2xl border border-base-200 hover:border-primary/40 hover:bg-base-200/30 transition">
                <h2 class="font-semibold text-lg">+ Jauns video</h2>
                <p class="text-sm text-base-content/50 mt-1">Augšupielādēt mp4, webm, mov vai mkv.</p>
            </a>
            <a href="{{ route('admin.posts.create') }}" class="p-6 rounded-2xl border border-base-200 hover:border-primary/40 hover:bg-base-200/30 transition">
                <h2 class="font-semibold text-lg">+ Jauns raksts</h2>
                <p class="text-sm text-base-content/50 mt-1">Uzraksti rakstu vai lapu.</p>
            </a>
            <a href="{{ route('admin.users.index') }}" class="p-6 rounded-2xl border border-base-200 hover:border-primary/40 hover:bg-base-200/30 transition">
                <h2 class="font-semibold text-lg">Pārvaldīt lietotājus</h2>
                <p class="text-sm text-base-content/50 mt-1">Piešķir lietotājiem administratora tiesības vai dzēs kontus.</p>
            </a>
        </div>
    </div>
</x-layouts.app>
