@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')

<div class="admin-dashboard">

    {{-- HERO --}}
    <section class="admin-hero">
        <div>
            <span class="admin-eyebrow">DASHBOARD ADMIN</span>
            <h1>Halo, {{ auth()->user()->name }}</h1>
            <p>
                Pantau permohonan siswa dan aktivitas sistem dari satu tempat.
            </p>
        </div>

        <div class="admin-hero-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.8">
                <path d="M3 12l9-8 9 8"/>
                <path d="M5 10v10h14V10"/>
                <path d="M9 20v-6h6v6"/>
            </svg>
        </div>
    </section>


    {{-- STATISTICS --}}
    <section class="admin-stats">

        <div class="admin-stat primary">
            <div class="admin-stat-top">
                <span>Total Permohonan</span>

                <div class="admin-stat-icon">
                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8">
                        <path d="M6 2h9l4 4v16H6z"/>
                        <path d="M14 2v5h5"/>
                        <path d="M9 12h6"/>
                        <path d="M9 16h6"/>
                    </svg>
                </div>
            </div>

            <strong>{{ $total }}</strong>
            <small>Seluruh permohonan masuk</small>
        </div>


        <div class="admin-stat warning">
            <div class="admin-stat-top">
                <span>Menunggu Persetujuan</span>

                <div class="admin-stat-icon">
                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </div>
            </div>

            <strong>{{ $counts['Menunggu persetujuan'] ?? 0 }}</strong>
            <small>Permohonan perlu ditinjau</small>
        </div>


        <div class="admin-stat success">
            <div class="admin-stat-top">
                <span>Disetujui</span>

                <div class="admin-stat-icon">
                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="m8 12 2.5 2.5L16 9"/>
                    </svg>
                </div>
            </div>

            <strong>{{ $counts['Disetujui'] ?? 0 }}</strong>
            <small>Permohonan telah disetujui</small>
        </div>


        <div class="admin-stat danger">
            <div class="admin-stat-top">
                <span>Ditolak</span>

                <div class="admin-stat-icon">
                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="m9 9 6 6"/>
                        <path d="m15 9-6 6"/>
                    </svg>
                </div>
            </div>

            <strong>{{ $counts['Ditolak'] ?? 0 }}</strong>
            <small>Permohonan ditolak</small>
        </div>

    </section>


    {{-- MAIN CONTENT --}}
    <div class="admin-content-grid">

        {{-- PENDING --}}
        <section class="admin-card">
            <div class="admin-card-header">
                <div>
                    <span class="admin-card-label">PERLU PERHATIAN</span>
                    <h2>Menunggu persetujuan</h2>
                    <p>Permohonan yang membutuhkan tindakan admin.</p>
                </div>

                <a href="{{ route('permohonan.index') }}" class="admin-link">
                    Lihat semua
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8">
                        <path d="M5 12h14"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>
                </a>
            </div>

            <div class="admin-request-list">
                @forelse ($pending as $item)

                    <a class="admin-request" href="{{ route('permohonan.show', $item) }}">

                        <div class="admin-request-number">
                            {{ strtoupper(substr($item->no, -2)) }}
                        </div>

                        <div class="admin-request-info">
                            <strong>{{ $item->title }}</strong>

                            <div>
                                <span>{{ $item->no }}</span>
                                <span class="dot"></span>
                                <span>{{ $item->student->name }}</span>
                            </div>
                        </div>

                        <div class="admin-request-arrow">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.8">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </div>

                    </a>

                @empty

                    <div class="admin-empty">
                        <div class="admin-empty-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.7">
                                <path d="M9 12l2 2 4-4"/>
                                <circle cx="12" cy="12" r="9"/>
                            </svg>
                        </div>

                        <strong>Tidak ada permohonan</strong>
                        <span>Semua permohonan sudah ditangani.</span>
                    </div>

                @endforelse
            </div>
        </section>


        {{-- ACTIVITY --}}
        <section class="admin-card">

            <div class="admin-card-header">
                <div>
                    <span class="admin-card-label">LOG SISTEM</span>
                    <h2>Aktivitas terbaru</h2>
                    <p>Aktivitas pengguna yang tercatat dalam sistem.</p>
                </div>

                <a href="{{ route('audit.index') }}" class="admin-link">
                    Semua log
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8">
                        <path d="M5 12h14"/>
                        <path d="m13 6 6 6-6 6"/>
                    </svg>
                </a>
            </div>


            <div class="admin-activity-list">

                @forelse ($recentLogs as $log)

                    <div class="admin-activity">

                        <div class="admin-activity-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.7">
                                <path d="M12 3v18"/>
                                <path d="M5 8h14"/>
                                <path d="M5 16h14"/>
                            </svg>
                        </div>

                        <div class="admin-activity-info">
                            <strong>{{ $log->action }}</strong>

                            <span>
                                {{ $log->user_name }}
                                <span class="dot"></span>
                                {{ $log->target }}
                            </span>
                        </div>

                        <time>
                            {{ $log->created_at->diffForHumans() }}
                        </time>

                    </div>

                @empty

                    <div class="admin-empty">
                        <div class="admin-empty-icon">
                            <svg width="25" height="25" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.7">
                                <path d="M9 12l2 2 4-4"/>
                                <circle cx="12" cy="12" r="9"/>
                            </svg>
                        </div>

                        <strong>Belum ada aktivitas</strong>
                        <span>Aktivitas sistem akan muncul di sini.</span>
                    </div>

                @endforelse

            </div>

        </section>

    </div>

</div>




@endsection