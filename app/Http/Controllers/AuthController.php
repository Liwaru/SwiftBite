<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('auth_user_id')) {
            return match ((int) $request->session()->get('auth_level')) {
                3, 4 => redirect()->route('admin.dashboard'),
                2 => redirect()->route('cashier.dashboard'),
                default => redirect()->route('customer.home'),
            };
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('name', $credentials['username'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['username' => 'Username atau password salah.'])
                ->withInput($request->only('username'));
        }

        $request->session()->regenerate();
        $request->session()->put([
            'auth_user_id' => $user->getKey(),
            'auth_user' => $user->name,
            'auth_name' => $user->name,
            'auth_level' => $user->level,
        ]);

        return match ($user->level) {
            3, 4 => redirect()->route('admin.dashboard'),
            2 => redirect()->route('cashier.dashboard'),
            default => redirect()->route('customer.home'),
        };
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['auth_user_id', 'auth_user', 'auth_name', 'auth_level']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
