@php $user ??= auth()->user(); @endphp

<x-layouts.app title="Profila iestatījumi">
    <div class="max-w-3xl mx-auto px-5 py-16">

        <div>
            <p class="text-sm text-base-content/45 uppercase tracking-widest font-medium">Konts</p>
            <h1 class="brand-font text-4xl font-medium mt-2">Profila iestatījumi</h1>
            <p class="mt-2 text-base-content/50">Atjaunini savu personīgo informāciju, paroli vai izdzēs kontu.</p>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success rounded-2xl mt-6 text-sm">Profils atjaunināts.</div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="alert alert-success rounded-2xl mt-6 text-sm">Parole atjaunināta.</div>
        @endif
        @if (session('status') === 'avatar-updated')
            <div class="alert alert-success rounded-2xl mt-6 text-sm">Profila attēls atjaunināts.</div>
        @endif

        {{-- Avatar --}}
        <section class="mt-10 p-6 sm:p-8 rounded-2xl border border-base-200 bg-base-100">
            <div class="flex items-center gap-5">
                <img src="{{ $user->avatarUrl() }}" alt="Pašreizējais avatārs"
                     class="w-20 h-20 rounded-full ring-2 ring-primary/30 shadow-sm shrink-0"/>
                <div>
                    <h2 class="brand-font text-xl font-medium">Profila attēls</h2>
                    <p class="text-sm text-base-content/50 mt-1">Izvēlies vienu no astoņiem iepriekš sagatavotiem variantiem.</p>
                </div>
            </div>

            @php
                $avatarLabels = [
                    'lotus'    => 'lotoss',
                    'moon'     => 'mēness',
                    'sun'      => 'saule',
                    'mountain' => 'kalns',
                    'wave'     => 'vilnis',
                    'leaf'     => 'lapa',
                    'flame'    => 'liesma',
                    'star'     => 'zvaigzne',
                ];
            @endphp

            <form method="POST" action="{{ route('profile.avatar') }}" class="mt-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-4 sm:grid-cols-8 gap-3">
                    @foreach(\App\Models\User::AVATARS as $key)
                        <label class="cursor-pointer group">
                            <input type="radio" name="avatar" value="{{ $key }}"
                                   class="peer sr-only"
                                   {{ old('avatar', $user->avatar) === $key ? 'checked' : '' }} />
                            <img src="{{ asset("images/avatars/{$key}.svg") }}" alt="Avatārs {{ $avatarLabels[$key] ?? $key }}"
                                 class="w-full aspect-square rounded-full ring-2 ring-base-200 transition-all
                                        group-hover:ring-primary/40
                                        peer-checked:ring-4 peer-checked:ring-primary peer-checked:shadow-md peer-checked:scale-105"/>
                            <p class="text-[10px] text-center mt-1.5 uppercase tracking-widest text-base-content/45 peer-checked:text-primary/80">
                                {{ $avatarLabels[$key] ?? $key }}
                            </p>
                        </label>
                    @endforeach
                </div>

                @error('avatar')<p class="text-xs text-error mt-3">{{ $message }}</p>@enderror

                <div class="pt-5">
                    <button type="submit" class="btn btn-primary rounded-xl">Saglabāt attēlu</button>
                </div>
            </form>
        </section>

        {{-- Profile info --}}
        <section class="mt-6 p-6 sm:p-8 rounded-2xl border border-base-200 bg-base-100">
            <h2 class="brand-font text-xl font-medium">Profila informācija</h2>
            <p class="text-sm text-base-content/50 mt-1">Atjaunini sava konta vārdu un e-pasta adresi.</p>

            <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="label"><span class="label-text">Vārds</span></label>
                    <input id="name" name="name" type="text" required autofocus autocomplete="name"
                           value="{{ old('name', $user->name) }}"
                           class="input input-bordered w-full rounded-xl" />
                    @error('name')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="label"><span class="label-text">E-pasts</span></label>
                    <input id="email" name="email" type="email" required autocomplete="username"
                           value="{{ old('email', $user->email) }}"
                           class="input input-bordered w-full rounded-xl" />
                    @error('email')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-primary rounded-xl">Saglabāt izmaiņas</button>
                </div>
            </form>
        </section>

        {{-- Password --}}
        <section class="mt-6 p-6 sm:p-8 rounded-2xl border border-base-200 bg-base-100">
            <h2 class="brand-font text-xl font-medium">Atjaunināt paroli</h2>
            <p class="text-sm text-base-content/50 mt-1">Izmanto garu, nejaušu paroli, lai konts būtu drošībā.</p>

            <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="label"><span class="label-text">Pašreizējā parole</span></label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                           class="input input-bordered w-full rounded-xl" />
                    @error('current_password', 'updatePassword')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="label"><span class="label-text">Jaunā parole</span></label>
                    <input id="password" name="password" type="password" autocomplete="new-password"
                           class="input input-bordered w-full rounded-xl" />
                    @error('password', 'updatePassword')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label"><span class="label-text">Apstiprini paroli</span></label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                           class="input input-bordered w-full rounded-xl" />
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn btn-primary rounded-xl">Atjaunināt paroli</button>
                </div>
            </form>
        </section>

        {{-- Delete account --}}
        <section class="mt-6 p-6 sm:p-8 rounded-2xl border border-error/30 bg-error/5"
                 x-data="{ open: false }">
            <h2 class="brand-font text-xl font-medium text-error">Dzēst kontu</h2>
            <p class="text-sm text-base-content/60 mt-1">
                Kad konts ir dzēsts, viss tavs saturs un dati tiks neatgriezeniski noņemti.
                Pirms turpināt, lūdzu, lejupielādē visu informāciju, ko vēlies saglabāt.
            </p>

            <div class="mt-5">
                <button type="button" @click="open = true" class="btn btn-error btn-outline rounded-xl">
                    Dzēst manu kontu
                </button>
            </div>

            <div x-show="open" x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                 @keydown.escape.window="open = false">
                <div class="bg-base-100 rounded-2xl shadow-xl max-w-md w-full p-6 sm:p-8" @click.outside="open = false">
                    <h3 class="brand-font text-xl font-medium">Vai esi pārliecināts?</h3>
                    <p class="text-sm text-base-content/60 mt-2">
                        Šo nevar atsaukt. Ievadi paroli, lai apstiprinātu dzēšanu.
                    </p>

                    <form method="POST" action="{{ route('profile.destroy') }}" class="mt-6 space-y-4">
                        @csrf
                        @method('DELETE')

                        <div>
                            <label for="delete_password" class="label"><span class="label-text">Parole</span></label>
                            <input id="delete_password" name="password" type="password" required
                                   placeholder="Tava pašreizējā parole"
                                   class="input input-bordered w-full rounded-xl" />
                            @error('password', 'userDeletion')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="open = false" class="btn btn-ghost rounded-xl">Atcelt</button>
                            <button type="submit" class="btn btn-error rounded-xl">Dzēst kontu</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

    </div>
</x-layouts.app>
