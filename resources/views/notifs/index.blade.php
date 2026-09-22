@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')

<div class="notif-page">

    <div class="ph notif-header">
        <div>
            <span class="eyebrow">Pusat informasi</span>
            <h1>Notifikasi</h1>
            <p class="page-desc">
                Lihat informasi terbaru dan aktivitas yang berkaitan dengan akun kamu.
            </p>
        </div>

        @if ($unread > 0)
            <form method="POST" action="{{ route('notifs.readAll') }}">
                @csrf
                <button class="btn ghost mark-read-btn" type="submit">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="m5 12 4 4L19 6"
                              fill="none"
                              stroke="currentColor"
                              stroke-width="2"
                              stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                    Tandai semua dibaca
                </button>
            </form>
        @endif
    </div>

    <section class="panel notif-panel">

        <div class="notif-summary">
            <div class="notif-summary-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                    <path d="M10 21h4"/>
                </svg>
            </div>

            <div>
                @if ($unread > 0)
                    <strong>{{ $unread }} notifikasi belum dibaca</strong>
                    <p>Periksa notifikasi terbaru kamu.</p>
                @else
                    <strong>Semua notifikasi sudah dibaca</strong>
                    <p>Tidak ada informasi baru yang perlu diperiksa.</p>
                @endif
            </div>
        </div>

        <div class="notif-list">

            @forelse ($notifs as $notif)

                <form method="POST"
                      action="{{ route('notifs.read', $notif) }}"
                      class="notif-form">

                    @csrf

                    <button
                        class="notif-item {{ $notif->read ? '' : 'unread' }}"
                        type="submit"
                    >

                        <div class="notif-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                                <path d="M10 21h4"/>
                            </svg>
                        </div>

                        <div class="notif-content">
                            <div class="notif-title-row">
                                <b>{{ $notif->text }}</b>

                                @if (!$notif->read)
                                    <span class="notif-dot" title="Belum dibaca"></span>
                                @endif
                            </div>

                            <small class="mut">
                                {{ $notif->created_at->diffForHumans() }}
                            </small>
                        </div>

                        <div class="notif-arrow">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"
                                      fill="none"
                                      stroke="currentColor"
                                      stroke-width="2"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </div>

                    </button>

                </form>

            @empty

                <div class="notif-empty">

                    <div class="notif-empty-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                            <path d="M10 21h4"/>
                        </svg>
                    </div>

                    <strong>Belum ada notifikasi</strong>
                    <p>
                        Notifikasi aktivitas akun kamu akan muncul di sini.
                    </p>

                </div>

            @endforelse

        </div>

    </section>

</div>

@endsection