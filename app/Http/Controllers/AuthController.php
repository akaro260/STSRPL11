<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email atau kata sandi salah.']);
        }

        if (!$user->active) {
            return back()->withErrors(['email' => 'Akun ini dinonaktifkan. Hubungi administrator sekolah.']);
        }

        if (!Auth::attempt($data)) {
            AuditLog::create([
                'user_name' => $user->email, 'role' => $user->role,
                'action' => 'auth.failed', 'target' => $user->email, 'detail' => 'Kata sandi salah',
            ]);

            return back()->withErrors(['email' => 'Email atau kata sandi salah.']);
        }

        $request->session()->regenerate();
        $user->update(['last_login_at' => now()]);

        AuditLog::create([
            'user_name' => $user->name, 'role' => $user->role,
            'action' => 'auth.login', 'target' => $user->email, 'detail' => 'Masuk ke sistem',
        ]);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        AuditLog::create([
            'user_name' => $user->name, 'role' => $user->role,
            'action' => 'auth.logout', 'target' => $user->email, 'detail' => 'Keluar dari sistem',
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
