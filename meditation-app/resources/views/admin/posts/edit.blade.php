<x-layouts.app title="Rediģēt rakstu">
    <div class="max-w-3xl mx-auto px-5 py-16">
        <h1 class="brand-font text-3xl font-medium">Rediģēt rakstu</h1>

        @if ($errors->any())
            <div class="alert alert-error mt-4 rounded-2xl text-sm">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="mt-8 space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="label"><span class="label-text">Nosaukums</span></label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                    class="input input-bordered w-full rounded-xl" />
            </div>

            <div>
                <label class="label"><span class="label-text">Saturs</span></label>
                <textarea name="body" rows="12" required
                    class="textarea textarea-bordered w-full rounded-xl">{{ old('body', $post->body) }}</textarea>
            </div>

            @include('admin._tag-selector')

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn btn-primary rounded-xl">Saglabāt izmaiņas</button>
                <a href="{{ route('pages.index') }}" class="btn btn-ghost rounded-xl">Atcelt</a>
            </div>
        </form>
    </div>
</x-layouts.app>
