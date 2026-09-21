<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        $q = trim($request->query('q', ''));

        $query = User::with('profile')->withCount('permohonans')->where('role', 'siswa');

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%");
        }

        $students = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('students.index', compact('students', 'q'));
    }

    public function show(User $student)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        if ($student->role !== 'siswa') {
            abort(404);
        }

        $student->load('profile');

        return view('students.show', compact('student'));
    }
}
