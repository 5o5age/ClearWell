<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    private const OTP_TTL_MINUTES = 10;
    private const RESEND_COOLDOWN_SECONDS = 45;

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $pendingId = Str::uuid()->toString();
        $code = (string) random_int(100000, 999999);

        Cache::put("register.pending.{$pendingId}", [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'code' => $code,
            'attempts' => 0,
            'last_sent_at' => now()->toIso8601String(),
        ], now()->addMinutes(self::OTP_TTL_MINUTES));

        Mail::to($validated['email'])->send(new RegistrationOtp($code, $validated['name']));

        $request->session()->put('register.pending_id', $pendingId);

        return redirect()->route('register.verify');
    }

    public function showVerify(Request $request): View|RedirectResponse
    {
        $pendingId = $request->session()->get('register.pending_id');
        if (!$pendingId || !Cache::has("register.pending.{$pendingId}")) {
            return redirect()->route('register')->with('error', 'Apstiprināšanas sesija beigusies. Lūdzu, reģistrējies vēlreiz.');
        }

        $data = Cache::get("register.pending.{$pendingId}");

        return view('auth.register-verify', [
            'email' => $data['email'],
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $pendingId = $request->session()->get('register.pending_id');
        if (!$pendingId || !Cache::has("register.pending.{$pendingId}")) {
            return redirect()->route('register')->with('error', 'Apstiprināšanas sesija beigusies. Lūdzu, reģistrējies vēlreiz.');
        }

        $data = Cache::get("register.pending.{$pendingId}");

        if (($data['attempts'] ?? 0) >= 5) {
            Cache::forget("register.pending.{$pendingId}");
            $request->session()->forget('register.pending_id');
            return redirect()->route('register')->with('error', 'Pārāk daudz nepareizu mēģinājumu. Lūdzu, reģistrējies vēlreiz.');
        }

        if (!hash_equals((string) $data['code'], (string) $request->input('code'))) {
            $data['attempts'] = ($data['attempts'] ?? 0) + 1;
            Cache::put("register.pending.{$pendingId}", $data, now()->addMinutes(self::OTP_TTL_MINUTES));

            return back()->withErrors(['code' => 'Nepareizs kods. Atlikuši mēģinājumi: ' . (5 - $data['attempts']) . '.']);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'avatar' => User::AVATARS[array_rand(User::AVATARS)],
        ]);
        $user->forceFill(['email_verified_at' => now()])->save();

        Cache::forget("register.pending.{$pendingId}");
        $request->session()->forget('register.pending_id');

        Auth::login($user);

        return redirect()->route('home');
    }

    public function resend(Request $request): RedirectResponse
    {
        $pendingId = $request->session()->get('register.pending_id');
        if (!$pendingId || !Cache::has("register.pending.{$pendingId}")) {
            return redirect()->route('register')->with('error', 'Apstiprināšanas sesija beigusies. Lūdzu, reģistrējies vēlreiz.');
        }

        $data = Cache::get("register.pending.{$pendingId}");

        $lastSent = isset($data['last_sent_at']) ? \Carbon\Carbon::parse($data['last_sent_at']) : null;
        if ($lastSent && $lastSent->diffInSeconds(now()) < self::RESEND_COOLDOWN_SECONDS) {
            $wait = self::RESEND_COOLDOWN_SECONDS - $lastSent->diffInSeconds(now());
            return back()->with('error', "Lūdzu, pagaidi {$wait} sekundes pirms jauna koda pieprasīšanas.");
        }

        $data['code'] = (string) random_int(100000, 999999);
        $data['attempts'] = 0;
        $data['last_sent_at'] = now()->toIso8601String();

        Cache::put("register.pending.{$pendingId}", $data, now()->addMinutes(self::OTP_TTL_MINUTES));

        Mail::to($data['email'])->send(new RegistrationOtp($data['code'], $data['name']));

        return back()->with('success', 'Jauns kods ir nosūtīts uz tavu e-pastu.');
    }

    public function cancel(Request $request): RedirectResponse
    {
        $pendingId = $request->session()->pull('register.pending_id');
        if ($pendingId) {
            Cache::forget("register.pending.{$pendingId}");
        }

        return redirect()->route('register');
    }
}
