<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Support\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    protected $groups = [
        'auth' => 'Autentikasi', 'user' => 'Pengguna', 'profile' => 'Profil',
        'request' => 'Permohonan', 'audit' => 'Log audit',
    ];

    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $logs = $this->filtered($request)->paginate(50)->withQueryString();

        return view('audit.index', [
            'logs' => $logs,
            'groups' => $this->groups,
            'q' => $request->query('q', ''),
            'group' => $request->query('group', ''),
            'from' => $request->query('from', ''),
            'to' => $request->query('to', ''),
        ]);
    }

    public function export(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $logs = $this->filtered($request)->get();

        $filename = 'log-audit-' . now()->format('Ymd') . '.csv';

        $rows = "Waktu,Pengguna,Peran,Aksi,Objek,Detail\n";
        foreach ($logs as $log) {
            $rows .= implode(',', [
                $log->created_at->format('Y-m-d H:i:s'),
                '"' . str_replace('"', '""', $log->user_name) . '"',
                '"' . str_replace('"', '""', Workflow::$roles[$log->role] ?? $log->role) . '"',
                '"' . str_replace('"', '""', $log->action) . '"',
                '"' . str_replace('"', '""', $log->target) . '"',
                '"' . str_replace('"', '""', $log->detail) . '"',
            ]) . "\n";
        }

        AuditLog::create([
            'user_name' => Auth::user()->name, 'role' => Auth::user()->role,
            'action' => 'audit.export', 'target' => $filename, 'detail' => $logs->count() . ' baris',
        ]);

        return response($rows, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    protected function filtered(Request $request)
    {
        $q = trim($request->query('q', ''));
        $group = $request->query('group', '');
        $from = $request->query('from', '');
        $to = $request->query('to', '');

        $query = AuditLog::query()->latest();

        if ($group !== '') {
            $query->where('action', 'like', $group . '.%');
        }

        if ($from !== '') {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to !== '') {
            $query->whereDate('created_at', '<=', $to);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('user_name', 'like', "%{$q}%")
                    ->orWhere('action', 'like', "%{$q}%")
                    ->orWhere('target', 'like', "%{$q}%")
                    ->orWhere('detail', 'like', "%{$q}%");
            });
        }

        return $query;
    }
}
