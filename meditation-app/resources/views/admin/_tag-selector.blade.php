@php
    $selected = old('tags', $selectedTagIds ?? []) ?? [];
@endphp

<div>
    <label class="label"><span class="label-text">Tagi</span></label>
    <div class="flex flex-wrap gap-2">
        @foreach($tags as $tag)
            @php $isSelected = in_array($tag->id, $selected); @endphp
            <label class="cursor-pointer select-none">
                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                    {{ $isSelected ? 'checked' : '' }}
                    class="sr-only js-tag-checkbox" />
                <span class="badge rounded-lg px-3 py-3 cursor-pointer transition-colors {{ $isSelected ? 'badge-primary' : 'badge-ghost' }}">
                    {{ $tag->name }}
                </span>
            </label>
        @endforeach
    </div>
    <p class="text-xs text-base-content/40 mt-2">Atzīmē tagus, kas attiecas uz šo saturu.</p>
</div>

<script>
    document.addEventListener('change', (e) => {
        if (!e.target.classList.contains('js-tag-checkbox')) return;
        const pill = e.target.nextElementSibling;
        if (!pill) return;
        pill.classList.toggle('badge-primary', e.target.checked);
        pill.classList.toggle('badge-ghost', !e.target.checked);
    });
</script>
