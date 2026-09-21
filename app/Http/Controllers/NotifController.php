<?php

namespace App\Http\Controllers;

use App\Models\Notif;
use Illuminate\Support\Facades\Auth;

class NotifController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifs = $user->notifs()->latest()->limit(60)->get();
        $unread = $user->notifs()->where('read', false)->count();

        return view('notifs.index', compact('notifs', 'unread'));
    }

    public function read(Notif $notif)
    {
        if ($notif->user_id !== Auth::id()) {
            abort(403);
        }

        $notif->update(['read' => true]);

        if ($notif->permohonan_id) {
            return redirect()->route('permohonan.show', $notif->permohonan_id);
        }

        return back();
    }

    public function readAll()
    {
        Auth::user()->notifs()->where('read', false)->update(['read' => true]);

        return back()->with('status', 'Semua notifikasi ditandai terbaca.');
    }
}
