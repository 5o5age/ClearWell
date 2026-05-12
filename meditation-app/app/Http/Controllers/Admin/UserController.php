<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::orderByDesc('created_at')->get(),
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
