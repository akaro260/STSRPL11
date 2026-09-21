@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')
<div class="ph">
    <div><h1>Notifikasi</h1></div>
    @if ($unread > 0)
        <form method="POST" action="{{ route('notifs.readAll') }}">
            @csrf
            <button class="btn ghost" type="submit">Tandai semua dibaca</button>
        </form>
    @endif
</div>

<section class="panel">
    @forelse ($notifs as $notif)
        <form method="POST" action="{{ route('notifs.read', $notif) }}">
            @csrf
            <button class="row {{ $notif->read ? '' : 'unread' }}" type="submit" style="width:100%;text-align:left">
                <b>{{ $notif->text }}</b>
                <small class="mut">{{ $notif->created_at->diffForHumans() }}</small>
            </button>
        </form>
    @empty
        <div class="empty"><b>Belum ada notifikasi</b></div>
    @endforelse
</section>
@endsection
