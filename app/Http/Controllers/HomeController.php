<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Permohonan;
use App\Support\Workflow;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->admin();
        }

        if ($user->role === 'petugas') {
            return $this->petugas();
        }

        return $this->siswa();
    }

    protected function admin()
    {
        $counts = [];
        foreach (Workflow::$statuses as $status) {
            $counts[$status] = Permohonan::where('status', $status)->count();
        }

        $total = array_sum($counts);

        $pending = Permohonan::with('student')
            ->where('status', 'Menunggu persetujuan')
            ->orderBy('updated_at')
            ->limit(6)
            ->get();

        $recentLogs = AuditLog::latest()->limit(6)->get();

        return view('home.admin', compact('counts', 'total', 'pending', 'recentLogs'));
    }

    protected function petugas()
    {
        $counts = [];
        foreach (Workflow::$statuses as $status) {
            $counts[$status] = Permohonan::where('status', $status)->count();
        }

        $queue = Permohonan::with('student')
            ->whereIn('status', ['Diajukan', 'Diproses'])
            ->orderBy('created_at')
            ->limit(7)
            ->get();

        return view('home.petugas', compact('counts', 'queue'));
    }

    protected function siswa()
    {
        $user = Auth::user();
        $profile = $user->profile;

        $counts = [];
        foreach (Workflow::$statuses as $status) {
            $counts[$status] = Permohonan::where('student_id', $user->id)->where('status', $status)->count();
        }

        $total = array_sum($counts);

        $recent = Permohonan::where('student_id', $user->id)
            ->orderByDesc('updated_at')
            ->limit(6)
            ->get();

        return view('home.siswa', compact('counts', 'total', 'profile', 'recent'));
    }
}
