<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'newest');

        $query = User::query();
        $query = match ($sort) {
            'oldest' => $query->orderBy('created_at'),
            'name'   => $query->orderBy('name'),
            default  => $query->orderByDesc('created_at'),
        };

        return view('admin.users.index', [
            'users' => $query->get(),
            'sort'  => in_array($sort, ['newest', 'oldest', 'name'], true) ? $sort : 'newest',
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        if ($request->user()->id === $user->id && $data['role'] !== 'admin') {
            return back()->with('error', 'Tu nevari atņemt sev administratora tiesības.');
        }

        $user->update(['role' => $data['role']]);

        $roleLabel = $user->role === 'admin' ? 'administrators' : 'lietotājs';
        return back()->with('success', "{$user->name} tagad ir {$roleLabel}.");
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return back()->with('error', 'Tu nevari dzēst savu kontu no šejienes.');
        }

        $user->delete();

        return back()->with('success', 'Lietotājs dzēsts.');
    }
}
