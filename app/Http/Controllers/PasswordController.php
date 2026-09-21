<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('auth.password');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        AuditLog::create([
            'user_name' => $user->name, 'role' => $user->role,
            'action' => 'user.password.self', 'target' => $user->email,
            'detail' => 'Pengguna mengubah kata sandi sendiri',
        ]);

        return redirect()->route('home')->with('status', 'Kata sandi diperbarui.');
    }
}
