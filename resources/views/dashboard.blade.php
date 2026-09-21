@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Welcome --}}
<section class="dash-hero">
    <div>
        <span class="dash-kicker">SCHOOL ADMINISTRATION</span>

        <h1>
            Selamat datang kembali,<br>
            <strong>{{ auth()->user()->name }}</strong>
        </h1>

        <p>
            Pantau aktivitas administrasi sekolah dan kelola
            permohonan dengan lebih mudah.
        </p>
    </div>

    <div class="dash-hero-art">
        <div class="dash-orb dash-orb-1"></div>
        <div class="dash-orb dash-orb-2"></div>
        <div class="dash-grid"></div>
    </div>
</section>


{{-- Statistik --}}
<div class="dash-stats">

    <div class="stat-card">
        <div class="stat-top">
            <span>Total Permohonan</span>

            <div class="stat-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <path d="M14 2v6h6"/>
                    <path d="M8 13h8M8 17h5"/>
                </svg>
            </div>
        </div>

        <div class="stat-value">
            {{ $totalPermohonan ?? 0 }}
        </div>

        <div class="stat-bottom">
            <span>Semua permohonan</span>
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-top">
            <span>Pending</span>

            <div class="stat-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v5l3 2"/>
                </svg>
            </div>
        </div>

        <div class="stat-value">
            {{ $pending ?? 0 }}
        </div>

        <div class="stat-bottom">
            <span>Menunggu diproses</span>
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-top">
            <span>Diproses</span>

            <div class="stat-icon violet">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M12 3v18"/>
                    <path d="M3 12h18"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
            </div>
        </div>

        <div class="stat-value">
            {{ $diproses ?? 0 }}
        </div>

        <div class="stat-bottom">
            <span>Sedang ditangani</span>
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-top">
            <span>Selesai</span>

            <div class="stat-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="m5 12 4 4L19 6"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
            </div>
        </div>

        <div class="stat-value">
            {{ $selesai ?? 0 }}
        </div>

        <div class="stat-bottom">
            <span>Telah diselesaikan</span>
        </div>
    </div>

</div>


{{-- Main dashboard --}}
<div class="dash-layout">

    {{-- Permohonan terbaru --}}
    <section class="panel dash-main">

        <div class="dash-section-head">
            <div>
                <span class="dash-label">AKTIVITAS</span>
                <h2>Permohonan terbaru</h2>
                <p class="mut">
                    Aktivitas permohonan yang baru masuk.
                </p>
            </div>

            <a class="btn ghost sm" href="{{ route('permohonan.index') }}">
                Lihat semua
            </a>
        </div>


        @if (($permohonanTerbaru ?? collect())->isEmpty())

            <div class="empty">
                <b>Belum ada permohonan</b>
                <span class="mut">
                    Permohonan yang masuk akan tampil di sini.
                </span>
            </div>

        @else

            <div class="dash-list">

                @foreach ($permohonanTerbaru as $item)

                    <a
                        class="dash-item"
                        href="{{ route('permohonan.show', $item) }}"
                    >

                        <div class="dash-item-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6"/>
                                <path d="M8 13h8M8 17h5"/>
                            </svg>
                        </div>

                        <div class="dash-item-content">
                            <strong>{{ $item->title }}</strong>

                            <span>
                                {{ $item->no }}

                                @if (auth()->user()->role !== 'siswa')
                                    · {{ $item->student->name }}
                                @endif
                            </span>
                        </div>

                        <div class="dash-item-status">
                            <span class="chip {{ \App\Support\Workflow::statusClass($item->status) }}">
                                {{ $item->status }}
                            </span>

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </div>

                    </a>

                @endforeach

            </div>

        @endif

    </section>


    {{-- Sidebar dashboard --}}
    <aside class="dash-side">

        {{-- Status --}}
        <section class="panel dash-status">

            <div class="dash-section-head">
                <div>
                    <span class="dash-label">STATUS</span>
                    <h2>Ringkasan</h2>
                </div>
            </div>

            <div class="status-row">
                <div>
                    <span class="status-dot pending"></span>
                    Pending
                </div>

                <strong>{{ $pending ?? 0 }}</strong>
            </div>

            <div class="status-row">
                <div>
                    <span class="status-dot process"></span>
                    Diproses
                </div>

                <strong>{{ $diproses ?? 0 }}</strong>
            </div>

            <div class="status-row">
                <div>
                    <span class="status-dot complete"></span>
                    Selesai
                </div>

                <strong>{{ $selesai ?? 0 }}</strong>
            </div>

        </section>


        {{-- Quick action --}}
        <section class="panel quick-panel">

            <span class="dash-label">AKSI CEPAT</span>

            <h2>Butuh sesuatu?</h2>

            <p>
                Gunakan menu berikut untuk mengelola administrasi sekolah.
            </p>

            @if (auth()->user()->role === 'siswa')

                <a class="quick-action" href="{{ route('permohonan.create') }}">
                    <span class="quick-action-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                    </span>

                    <span>
                        <strong>Ajukan permohonan</strong>
                        <small>Buat pengajuan baru</small>
                    </span>

                    <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>

            @endif

            <a class="quick-action" href="{{ route('permohonan.index') }}">
                <span class="quick-action-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 5h16M4 12h16M4 19h16"/>
                    </svg>
                </span>

                <span>
                    <strong>Permohonan</strong>
                    <small>Lihat seluruh data</small>
                </span>

                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </a>

        </section>

    </aside>

</div>

@endsection