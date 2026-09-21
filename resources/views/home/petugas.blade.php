@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')

{{-- Header --}}
<section class="petugas-hero">
    <div class="petugas-hero-content">
        <span class="dash-label">PETUGAS ADMINISTRASI</span>

        <h1>
            Halo, {{ auth()->user()->name }}
        </h1>

        <p>
            Kelola dan pantau permohonan siswa yang perlu ditangani
            melalui antrean administrasi.
        </p>
    </div>

    <div class="petugas-hero-decoration">
        <div class="hero-circle circle-one"></div>
        <div class="hero-circle circle-two"></div>

        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <path d="M14 2v6h6"/>
            <path d="M8 13h8"/>
            <path d="M8 17h5"/>
        </svg>
    </div>
</section>


{{-- Statistik --}}
<section class="petugas-stats">

    <div class="petugas-stat">
        <div class="petugas-stat-icon blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <path d="M14 2v6h6"/>
                <path d="M8 13h8M8 17h5"/>
            </svg>
        </div>

        <div>
            <span>Diajukan</span>
            <strong>{{ $counts['Diajukan'] ?? 0 }}</strong>
            <small>Permohonan baru</small>
        </div>
    </div>


    <div class="petugas-stat">
        <div class="petugas-stat-icon violet">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 7v5l3 2"/>
            </svg>
        </div>

        <div>
            <span>Diproses</span>
            <strong>{{ $counts['Diproses'] ?? 0 }}</strong>
            <small>Sedang ditangani</small>
        </div>
    </div>


    <div class="petugas-stat">
        <div class="petugas-stat-icon amber">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 7v5"/>
                <path d="M12 16h.01"/>
            </svg>
        </div>

        <div>
            <span>Menunggu persetujuan</span>
            <strong>{{ $counts['Menunggu persetujuan'] ?? 0 }}</strong>
            <small>Perlu perhatian</small>
        </div>
    </div>

</section>


{{-- Konten utama --}}
<div class="petugas-layout">

    {{-- Antrean --}}
    <section class="panel petugas-queue">

        <div class="petugas-section-head">

            <div>
                <span class="dash-label">ANTREAN</span>

                <h2>
                    Perlu Anda tangani
                </h2>

                <p>
                    Daftar permohonan yang sedang menunggu tindakan.
                </p>
            </div>

            <a
                href="{{ route('permohonan.index') }}"
                class="btn ghost sm"
            >
                Lihat semua
            </a>

        </div>


        @forelse ($queue as $item)

            <a
                class="petugas-request"
                href="{{ route('permohonan.show', $item) }}"
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
                        <i></i>
                        {{ $item->student->name }}
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

            <div class="petugas-empty">

                <div class="empty-icon">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M9 12h6"/>
                        <path d="M12 9v6"/>
                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                    </svg>

                </div>

                <strong>Antrean kosong</strong>

                <span>
                    Tidak ada permohonan yang perlu ditangani saat ini.
                </span>

            </div>

        @endforelse

    </section>


    {{-- Sidebar --}}
    <aside class="petugas-side">

        <section class="panel petugas-summary">

            <div class="petugas-side-head">
                <span class="dash-label">RINGKASAN</span>
                <h2>Status permohonan</h2>
            </div>


            <div class="summary-item">

                <div>
                    <span class="summary-dot blue"></span>
                    <span>Diajukan</span>
                </div>

                <strong>
                    {{ $counts['Diajukan'] ?? 0 }}
                </strong>

            </div>


            <div class="summary-item">

                <div>
                    <span class="summary-dot violet"></span>
                    <span>Diproses</span>
                </div>

                <strong>
                    {{ $counts['Diproses'] ?? 0 }}
                </strong>

            </div>


            <div class="summary-item">

                <div>
                    <span class="summary-dot amber"></span>
                    <span>Menunggu persetujuan</span>
                </div>

                <strong>
                    {{ $counts['Menunggu persetujuan'] ?? 0 }}
                </strong>

            </div>

        </section>


        <section class="panel petugas-info">

            <div class="info-icon">

                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 11v5"/>
                    <path d="M12 8h.01"/>
                </svg>

            </div>

            <div>
                <strong>Tips untuk petugas</strong>

                <p>
                    Periksa antrean secara berkala agar permohonan
                    siswa dapat segera diproses.
                </p>
            </div>

        </section>

    </aside>

</div>

@endsection