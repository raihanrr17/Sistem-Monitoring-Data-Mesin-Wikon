<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:admin,user'],
        ], [
            'role.required' => 'Silakan pilih role.',
            'role.in'       => 'Role tidak valid.',
        ]);

        // User langsung masuk tanpa email & password
        if ($request->role === 'user') {
            $user = User::where('role', 'user')->first();

            if (!$user) {
                return back()->withErrors(['role' => 'Akun user tidak ditemukan.']);
            }

            Auth::login($user);
            $request->session()->regenerate();

            return redirect('/home');
        }

        // Admin wajib pakai email & password
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role !== 'admin') {
                Auth::logout();
                return back()
                    ->withInput($request->only('email', 'role'))
                    ->withErrors(['email' => 'Akun ini bukan admin.']);
            }

            return redirect('/admin');
        }

        return back()
            ->withInput($request->only('email', 'role'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil logout.');
    }

    private function redirectByRole($user)
    {
        return match ($user->role) {
            'admin' => redirect('/admin'),
            'user'  => redirect('/home'),
            default => redirect('/'),
        };
    }
}