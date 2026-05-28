<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = User::findOrFail($request->session()->get('auth_user_id'));

        return view('profile.show', compact('user'));
    }

    public function updateName(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $user = User::findOrFail($request->session()->get('auth_user_id'));
        $user->update(['name' => $validated['name']]);

        $request->session()->put([
            'auth_user' => $user->name,
            'auth_name' => $user->name,
        ]);

        return back()->with('success', 'Nama profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::findOrFail($request->session()->get('auth_user_id'));

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password lama tidak sesuai.'])
                ->withInput()
                ->with('open_password_modal', true);
        }

        $user->update(['password' => $validated['password']]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
