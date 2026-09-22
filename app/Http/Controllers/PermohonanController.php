<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Notif;
use App\Models\Permohonan;
use App\Models\User;
use App\Support\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status', '');
        $category = $request->query('category', '');
        $q = trim($request->query('q', ''));

        $query = Permohonan::with(['student', 'steps'])
    ->orderByDesc('updated_at');

        // Siswa hanya boleh melihat permohonan miliknya sendiri.
        if ($user->role === 'siswa') {
            $query->where('student_id', $user->id);
        }

        if ($status === 'todo') {
            $query->whereIn('status', Workflow::todoStatuses($user->role));
        } elseif ($status !== '') {
            $query->where('status', $status);
        }

        if ($category !== '') {
            $query->where('category', $category);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('no', 'like', "%{$q}%")
                    ->orWhere('title', 'like', "%{$q}%");
            });
        }

        $permohonan = $query->paginate(5)->withQueryString();

        return view('permohonan.index', compact('permohonan', 'status', 'category', 'q'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'siswa') {
            abort(403);
        }

        return view('permohonan.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'siswa') {
            abort(403);
        }

        $data = $request->validate([
            'category'    => 'required|in:cuti,dokumen,lainnya',
            'title'       => 'required|min:5|max:90',
            'description' => 'required|min:15',
            'from_date'   => 'required_if:category,cuti|nullable|date',
            'to_date'     => 'required_if:category,cuti|nullable|date|after_or_equal:from_date',
        ], [
            'title.min'       => 'Judul minimal 5 karakter.',
            'description.min' => 'Keterangan minimal 15 karakter agar petugas paham keperluan Anda.',
        ]);

        $user = Auth::user();

        // Buat nomor permohonan berikutnya, misal REQ-0009.
        $lastNo = Permohonan::orderByDesc('id')->value('no');
        $nextSeq = $lastNo ? ((int) substr($lastNo, 4)) + 1 : 1;
        $no = 'REQ-' . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);

        $permohonan = Permohonan::create([
            'no'          => $no,
            'student_id'  => $user->id,
            'category'    => $data['category'],
            'title'       => $data['title'],
            'description' => $data['description'],
            'from_date'   => $data['category'] === 'cuti' ? $data['from_date'] : null,
            'to_date'     => $data['category'] === 'cuti' ? $data['to_date'] : null,
            'status'      => 'Diajukan',
        ]);

        $permohonan->steps()->create([
            'actor_name' => $user->name, 'role' => 'siswa',
            'status' => 'Diajukan', 'note' => 'Permohonan diajukan.',
        ]);

        AuditLog::create([
            'user_name' => $user->name, 'role' => $user->role,
            'action' => 'request.create', 'target' => $no, 'detail' => $data['title'],
        ]);

        // Beri tahu semua petugas.
        $petugasList = User::where('role', 'petugas')->where('active', true)->get();
        foreach ($petugasList as $petugas) {
            Notif::create([
                'user_id' => $petugas->id, 'permohonan_id' => $permohonan->id,
                'text' => "Permohonan baru {$no} dari {$user->name}",
            ]);
        }

        return redirect()->route('permohonan.show', $permohonan)->with('status', "Permohonan {$no} terkirim.");
    }

    public function show(Permohonan $permohonan)
    {
        $user = Auth::user();

        // Siswa tidak boleh membuka permohonan siswa lain.
        if ($user->role === 'siswa' && $permohonan->student_id !== $user->id) {
            abort(403);
        }

        $permohonan->load(['student.profile', 'steps']);

        $actions = Workflow::nextStatuses($user->role, $permohonan->status);
        $info = Workflow::info($permohonan->status, $user->role);

        return view('permohonan.show', compact('permohonan', 'actions', 'info'));
    }

    public function transition(Request $request, Permohonan $permohonan)
    {
        $user = Auth::user();

        $allowed = Workflow::nextStatuses($user->role, $permohonan->status);

        $data = $request->validate([
            'to'   => 'required|in:' . implode(',', $allowed ?: ['-']),
            'note' => 'nullable|string',
        ], [
            'to.in' => 'Tindakan ini tidak tersedia untuk status saat ini.',
        ]);

        $note = trim($data['note'] ?? '');

        if ($data['to'] === 'Ditolak' && strlen($note) < 5) {
            return back()->withErrors(['note' => 'Tulis alasan penolakan agar pemohon tahu langkah selanjutnya.']);
        }

        $previousStatus = $permohonan->status;

        $permohonan->status = $data['to'];
        if (in_array($data['to'], ['Disetujui', 'Ditolak'])) {
            $permohonan->decided_at = \Illuminate\Support\Carbon::now();
        }
        $permohonan->save();

        $permohonan->steps()->create([
            'actor_name' => $user->name, 'role' => $user->role,
            'status' => $data['to'], 'note' => $note ?: null,
        ]);

        AuditLog::create([
            'user_name' => $user->name, 'role' => $user->role,
            'action' => 'request.' . strtolower($data['to']),
            'target' => $permohonan->no,
            'detail' => "{$previousStatus} menjadi {$data['to']}" . ($note ? ": {$note}" : ''),
        ]);

        Notif::create([
            'user_id' => $permohonan->student_id, 'permohonan_id' => $permohonan->id,
            'text' => "{$permohonan->no} \"{$permohonan->title}\" kini berstatus {$data['to']}",
        ]);

        if ($data['to'] === 'Menunggu persetujuan') {
            $admins = User::where('role', 'admin')->where('active', true)->get();
            foreach ($admins as $admin) {
                Notif::create([
                    'user_id' => $admin->id, 'permohonan_id' => $permohonan->id,
                    'text' => "{$permohonan->no} menunggu persetujuan Anda",
                ]);
            }
        }

        return back()->with('status', "{$permohonan->no} " . Workflow::doneWord($data['to']) . '.');
    }
}
