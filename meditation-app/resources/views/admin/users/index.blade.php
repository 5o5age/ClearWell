<x-layouts.app title="Pārvaldīt lietotājus">
    <div class="max-w-5xl mx-auto px-5 py-16">

        <div class="flex items-start justify-between gap-6 flex-wrap">
            <div>
                <h1 class="brand-font text-3xl font-medium">Lietotāji</h1>
                <p class="mt-2 text-base-content/50">Piešķir lietotājiem administratora tiesības vai atsauc piekļuvi.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm rounded-xl">← Administratora panelis</a>
        </div>

        <div class="mt-8 flex items-center gap-2 flex-wrap">
            <span class="text-xs uppercase tracking-widest text-base-content/45 mr-2">Kārtot:</span>
            <a href="{{ route('admin.users.index', ['sort' => 'newest']) }}"
               class="btn btn-xs rounded-lg {{ $sort === 'newest' ? 'btn-primary' : 'btn-ghost' }}">Jaunākie vispirms</a>
            <a href="{{ route('admin.users.index', ['sort' => 'oldest']) }}"
               class="btn btn-xs rounded-lg {{ $sort === 'oldest' ? 'btn-primary' : 'btn-ghost' }}">Vecākie vispirms</a>
            <a href="{{ route('admin.users.index', ['sort' => 'name']) }}"
               class="btn btn-xs rounded-lg {{ $sort === 'name' ? 'btn-primary' : 'btn-ghost' }}">Pēc vārda (A–Z)</a>
        </div>

        <div class="mt-10 overflow-x-auto rounded-2xl border border-base-200 bg-base-100">
            <table class="table">
                <thead>
                    <tr class="text-xs uppercase tracking-widest text-base-content/45">
                        <th>Lietotājs</th>
                        <th>E-pasts</th>
                        <th>Loma</th>
                        <th>Pievienojies</th>
                        <th class="text-right">Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="hover">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-semibold text-xs ring-1 ring-primary/30">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium">
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                            <span class="text-xs text-base-content/40">(tu)</span>
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td class="text-sm text-base-content/70">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge badge-primary badge-sm rounded-lg">administrators</span>
                                @else
                                    <span class="badge badge-ghost badge-sm rounded-lg">lietotājs</span>
                                @endif
                            </td>
                            <td class="text-xs text-base-content/45">{{ $user->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    @if($user->id !== auth()->id())
                                        @if($user->role === 'admin')
                                            <form method="POST" action="{{ route('admin.users.updateRole', $user) }}"
                                                  onsubmit="return confirm('Atņemt administratora tiesības lietotājam {{ $user->name }}?');">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="role" value="user">
                                                <button type="submit" class="btn btn-ghost btn-xs rounded-lg">Atņemt admin</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.updateRole', $user) }}"
                                                  onsubmit="return confirm('Piešķirt lietotājam {{ $user->name }} administratora tiesības?');">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="role" value="admin">
                                                <button type="submit" class="btn btn-primary btn-xs rounded-lg">Padarīt par admin</button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                              onsubmit="return confirm('Neatgriezeniski dzēst lietotāju {{ $user->name }}? Šo nevar atsaukt.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-xs rounded-lg text-error">Dzēst</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-base-content/35 italic">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="text-xs text-base-content/40 mt-4">
            Lietotāji kopā: {{ $users->count() }} · Administratori: {{ $users->where('role', 'admin')->count() }}
        </p>

    </div>
</x-layouts.app>
