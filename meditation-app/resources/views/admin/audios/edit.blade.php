<x-layouts.app title="Rediģēt audio">
    <div class="max-w-2xl mx-auto px-5 py-16">
        <h1 class="brand-font text-3xl font-medium">Rediģēt audio</h1>

        @if ($errors->any())
            <div class="alert alert-error mt-4 rounded-2xl text-sm">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.audios.update', $audio) }}" enctype="multipart/form-data" class="mt-8 space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="label"><span class="label-text">Nosaukums</span></label>
                <input type="text" name="title" value="{{ old('title', $audio->title) }}" required
                    class="input input-bordered w-full rounded-xl" />
            </div>

            <div>
                <label class="label"><span class="label-text">Apraksts</span></label>
                <textarea name="description" rows="4"
                    class="textarea textarea-bordered w-full rounded-xl">{{ old('description', $audio->description) }}</textarea>
            </div>

            <div>
                <label class="label"><span class="label-text">Aizstāt failu (atstāj tukšu, lai paturētu pašreizējo)</span></label>
                <input type="file" name="file" accept="audio/*"
                    class="file-input file-input-bordered w-full rounded-xl" />
                <p class="text-xs text-base-content/40 mt-2">Pašreizējais: {{ basename($audio->file_path) }}</p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn btn-primary rounded-xl">Saglabāt izmaiņas</button>
                <a href="{{ route('audio.index') }}" class="btn btn-ghost rounded-xl">Atcelt</a>
            </div>
        </form>
    </div>
</x-layouts.app>
