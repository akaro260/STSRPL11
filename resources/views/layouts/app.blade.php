<!doctype html>

<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<title>@yield('title', 'SchoolAdmin')</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<!-- @vite('resources/css/app.css', 'resources/js/app.js') -->

</head>

<body>

@auth

<div class="app">

{{-- SIDEBAR --}}
<aside class="side">

    <div class="brand">
        <span>SchoolAdmin</span>
    </div>

    <nav id="nav">

        <a class="nav {{ request()->routeIs('home') ? 'on' : '' }}"
            href="{{ route('home') }}">
            Beranda
        </a>

        <a class="nav {{ request()->routeIs('permohonan.*') ? 'on' : '' }}"
            href="{{ route('permohonan.index') }}">

            @if (auth()->user()->role === 'admin')
                Semua permohonan
            @elseif (auth()->user()->role === 'petugas')
                Antrean permohonan
            @else
                Permohonan saya
            @endif

        </a>

        @if (auth()->user()->role === 'siswa')

            <a class="nav {{ request()->routeIs('profile.*') ? 'on' : '' }}"
                href="{{ route('profile.edit') }}">
                Profil saya
            </a>

        @endif

        @if (in_array(auth()->user()->role, ['admin', 'petugas']))

            <a class="nav {{ request()->routeIs('students.*') ? 'on' : '' }}"
                href="{{ route('students.index') }}">
                Data siswa
            </a>

        @endif

        @if (auth()->user()->role === 'admin')

            <a class="nav {{ request()->routeIs('users.*') ? 'on' : '' }}"
                href="{{ route('users.index') }}">
                Pengguna dan peran
            </a>

            <a class="nav {{ request()->routeIs('audit.*') ? 'on' : '' }}"
                href="{{ route('audit.index') }}">
                Log audit
            </a>

        @endif

    </nav>

    {{-- USER AREA --}}
    <div class="me">

        <div class="av">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <div>
            <b>{{ auth()->user()->name }}</b>
            <span>{{ ucfirst(auth()->user()->role) }}</span>
        </div>

    </div>

    <a class="lnk" href="{{ route('password.edit') }}">
        Ubah kata sandi
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button class="lnk" type="submit">
            Keluar
        </button>
    </form>

</aside>


{{-- CONTENT COLUMN --}}
<div class="col">

    {{-- TOP NAVBAR --}}
    <header class="topbar">

        {{-- BREADCRUMB --}}
        <div class="breadcrumb">

            <a href="{{ route('home') }}">
                Beranda
            </a>

            @php
                $routeName = request()->route()?->getName();

                $breadcrumb = match (true) {
                    request()->routeIs('permohonan.*') => 'Permohonan',
                    request()->routeIs('profile.*') => 'Profil',
                    request()->routeIs('students.*') => 'Data siswa',
                    request()->routeIs('users.*') => 'Pengguna dan peran',
                    request()->routeIs('audit.*') => 'Log audit',
                    default => null,
                };
            @endphp

            @if ($breadcrumb && !request()->routeIs('home'))

                <span class="breadcrumb-separator">
                    /
                </span>

                <span class="breadcrumb-current">
                    {{ $breadcrumb }}
                </span>

            @endif

        </div>


        {{-- RIGHT SIDE --}}
        <div class="topbar-right">

            {{-- NOTIFICATION --}}
            <a href="{{ route('notifs.index') }}"
                class="notification"
                aria-label="Notifikasi">

                <svg width="21"
                    height="21"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" />

                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />

                </svg>

                @if (($unreadNotifications ?? 0) > 0)

                    <span class="notification-badge">
                        {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                    </span>

                @endif

            </a>


            {{-- USER --}}

        </div>

    </header>


    {{-- MAIN PAGE --}}
    <main class="page">

        <div class="sheet">

            @if (session('status'))

                <div class="notice">
                    <div>
                        {{ session('status') }}
                    </div>
                </div>

            @endif

            @yield('content')

        </div>

    </main>

</div>

</div>

@else

@yield('content')

@endauth

</body>

</html>
