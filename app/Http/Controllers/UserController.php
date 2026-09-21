<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use App\Support\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $q = trim($request->query('q', ''));

        $query = User::orderBy('role')->orderBy('name');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->paginate(20)->withQueryString();

        return view('users.index', compact('users', 'q'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $data = $request->validate([
            'name'     => 'required|min:3',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,petugas,siswa',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => strtolower($data['email']),
            'role'     => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        if ($user->role === 'siswa') {
            $user->profile()->create([]);
        }

        AuditLog::create([
            'user_name' => Auth::user()->name, 'role' => Auth::user()->role,
            'action' => 'user.create', 'target' => $user->email,
            'detail' => 'Peran: ' . Workflow::$roles[$user->role],
        ]);

        return back()->with('status', "Pengguna {$user->name} ditambahkan.");
    }

    public function updateRole(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin' || $user->id === Auth::id()) {
            abort(403);
        }

        $data = $request->validate(['role' => 'required|in:admin,petugas,siswa']);
        $oldRole = Workflow::$roles[$user->role];

        $user->update(['role' => $data['role']]);

        if ($user->role === 'siswa' && !$user->profile) {
            $user->profile()->create([]);
        }

        AuditLog::create([
            'user_name' => Auth::user()->name, 'role' => Auth::user()->role,
            'action' => 'user.role', 'target' => $user->email,
            'detail' => "{$oldRole} menjadi " . Workflow::$roles[$user->role],
        ]);

        return back()->with('status', "Peran {$user->name} diubah.");
    }

    public function toggleStatus(User $user)
    {
        if (Auth::user()->role !== 'admin' || $user->id === Auth::id()) {
            abort(403);
        }

        $user->update(['active' => !$user->active]);

        AuditLog::create([
            'user_name' => Auth::user()->name, 'role' => Auth::user()->role,
            'action' => 'user.status', 'target' => $user->email,
            'detail' => $user->active ? 'Diaktifkan' : 'Dinonaktifkan',
        ]);

        return back()->with('status', "Akun {$user->name} " . ($user->active ? 'diaktifkan' : 'dinonaktifkan') . '.');
    }

    public function resetPassword(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $data = $request->validate(['password' => 'required|min:8']);

        $user->update(['password' => Hash::make($data['password'])]);

        AuditLog::create([
            'user_name' => Auth::user()->name, 'role' => Auth::user()->role,
            'action' => 'user.password', 'target' => $user->email,
            'detail' => 'Kata sandi diatur ulang administrator',
        ]);

        return back()->with('status', "Kata sandi {$user->name} diatur ulang.");
    }
}
