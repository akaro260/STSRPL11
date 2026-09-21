@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

{{-- Hero --}}
<section class="siswa-hero">

    <div class="siswa-hero-content">

        <span class="dash-label">PORTAL SISWA</span>

        <h1>
            Halo, {{ auth()->user()->name }}
        </h1>

        <p>
            Ajukan permohonan administrasi sekolah dan pantau
            perkembangannya dengan mudah.
        </p>

        <a
            href="{{ route('permohonan.create') }}"
            class="siswa-hero-button"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M12 5v14"/>
                <path d="M5 12h14"/>
            </svg>

            Ajukan permohonan
        </a>

    </div>

    <div class="siswa-hero-decoration">

        <div class="siswa-orb orb-one"></div>
        <div class="siswa-orb orb-two"></div>

        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <path d="M14 2v6h6"/>
            <path d="M8 13h8"/>
            <path d="M8 17h5"/>
        </svg>

    </div>

</section>


@php($complete = \App\Support\Workflow::completeness($profile))


{{-- Profile warning --}}
@if ($complete < 100)

    <section class="profile-complete-card">

        <div class="profile-complete-icon">

            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 7v5"/>
                <path d="M12 16h.01"/>
            </svg>

        </div>

        <div class="profile-complete-content">

            <div class="profile-complete-head">
                <strong>Lengkapi profil Anda</strong>
                <span>{{ $complete }}%</span>
            </div>

            <p>
                Lengkapi data profil agar proses administrasi
                dapat berjalan dengan lebih lancar.
            </p>

            <div class="profile-progress">
                <span style="width: {{ $complete }}%"></span>
            </div>

        </div>

        <a
            href="{{ route('profile.edit') }}"
            class="btn sm"
        >
            Lengkapi profil
        </a>

    </section>

@endif


{{-- Statistik --}}
<section class="siswa-stats">

    <div class="siswa-stat">

        <div class="siswa-stat-icon blue">

            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <path d="M14 2v6h6"/>
                <path d="M8 13h8M8 17h5"/>
            </svg>

        </div>

        <div>
            <span>Total permohonan</span>
            <strong>{{ $total }}</strong>
            <small>Semua pengajuan Anda</small>
        </div>

    </div>


    <div class="siswa-stat">

        <div class="siswa-stat-icon green">

            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="m5 12 4 4L19 6"/>
                <circle cx="12" cy="12" r="9"/>
            </svg>

        </div>

        <div>
            <span>Disetujui</span>
            <strong>{{ $counts['Disetujui'] ?? 0 }}</strong>
            <small>Permohonan diterima</small>
        </div>

    </div>


    <div class="siswa-stat">

        <div class="siswa-stat-icon red">

            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="12" cy="12" r="9"/>
                <path d="m9 9 6 6"/>
                <path d="m15 9-6 6"/>
            </svg>

        </div>

        <div>
            <span>Ditolak</span>
            <strong>{{ $counts['Ditolak'] ?? 0 }}</strong>
            <small>Permohonan ditolak</small>
        </div>

    </div>

</section>


{{-- Content --}}
<div class="siswa-layout">

    {{-- Recent --}}
    <section class="panel siswa-recent">

        <div class="siswa-section-head">

            <div>
                <span class="dash-label">AKTIVITAS</span>

                <h2>Permohonan terbaru</h2>

                <p>
                    Pantau status pengajuan administrasi Anda.
                </p>
            </div>

            <a
                href="{{ route('permohonan.index') }}"
                class="btn ghost sm"
            >
                Lihat semua
            </a>

        </div>


        @forelse ($recent as $item)

            <a
                href="{{ route('permohonan.show', $item) }}"
                class="siswa-request"
            >

                <div class="request-icon">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                        <path d="M8 13h8"/>
                        <path d="M8 17h5"/>
                    </svg>

                </div>


                <div class="request-info">

                    <strong>
                        {{ $item->title }}
                    </strong>

                    <span>
                        {{ $item->no }}
                    </span>

                </div>


                <div class="request-right">

                    <span class="chip {{ \App\Support\Workflow::statusClass($item->status) }}">
                        {{ $item->status }}
                    </span>

                    <svg
                        class="request-arrow"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                </div>

            </a>

        @empty

            <div class="siswa-empty">

                <div class="empty-icon">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                    </svg>

                </div>

                <strong>Belum ada permohonan</strong>

                <span>
                    Ajukan permohonan pertama Anda untuk memulai.
                </span>

                <a
                    href="{{ route('permohonan.create') }}"
                    class="btn sm"
                >
                    Ajukan sekarang
                </a>

            </div>

        @endforelse

    </section>


    {{-- Sidebar --}}
    <aside class="siswa-side">

        <section class="panel siswa-status">

            <div class="siswa-side-head">

                <span class="dash-label">STATUS</span>

                <h2>Ringkasan pengajuan</h2>

            </div>


            <div class="siswa-status-row">

                <div>
                    <span class="summary-dot blue"></span>
                    <span>Diajukan</span>
                </div>

                <strong>
                    {{ $counts['Diajukan'] ?? 0 }}
                </strong>

            </div>


            <div class="siswa-status-row">

                <div>
                    <span class="summary-dot violet"></span>
                    <span>Diproses</span>
                </div>

                <strong>
                    {{ $counts['Diproses'] ?? 0 }}
                </strong>

            </div>


            <div class="siswa-status-row">

                <div>
                    <span class="summary-dot green"></span>
                    <span>Disetujui</span>
                </div>

                <strong>
                    {{ $counts['Disetujui'] ?? 0 }}
                </strong>

            </div>


            <div class="siswa-status-row">

                <div>
                    <span class="summary-dot red"></span>
                    <span>Ditolak</span>
                </div>

                <strong>
                    {{ $counts['Ditolak'] ?? 0 }}
                </strong>

            </div>

        </section>


        <section class="panel siswa-profile-card">

            <div class="profile-card-top">

                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div>
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>Siswa</span>
                </div>

            </div>


            <div class="profile-card-line"></div>


            <div class="profile-card-item">

                <span>Kelengkapan profil</span>

                <strong>{{ $complete }}%</strong>

            </div>


            <a
                href="{{ route('profile.edit') }}"
                class="profile-card-link"
            >
                <span>Kelola profil</span>

                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="m9 18 6-6-6-6"/>
                </svg>

            </a>

        </section>

    </aside>

</div>

@endsection