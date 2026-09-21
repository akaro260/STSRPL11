<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SchoolAdmin')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

@auth
<div class="app">
    <aside class="side">
        <div class="brand"><span>SchoolAdmin</span></div>

        <nav id="nav">
            <a class="nav {{ request()->routeIs('home') ? 'on' : '' }}" href="{{ route('home') }}">Beranda</a>

            <a class="nav {{ request()->routeIs('permohonan.*') ? 'on' : '' }}" href="{{ route('permohonan.index') }}">
                @if (auth()->user()->role === 'admin') Semua permohonan
                @elseif (auth()->user()->role === 'petugas') Antrean permohonan
                @else Permohonan saya
                @endif
            </a>

            @if (auth()->user()->role === 'siswa')
                <a class="nav {{ request()->routeIs('profile.*') ? 'on' : '' }}" href="{{ route('profile.edit') }}">Profil saya</a>
            @endif

            @if (in_array(auth()->user()->role, ['admin', 'petugas']))
                <a class="nav {{ request()->routeIs('students.*') ? 'on' : '' }}" href="{{ route('students.index') }}">Data siswa</a>
            @endif

            @if (auth()->user()->role === 'admin')
                <a class="nav {{ request()->routeIs('users.*') ? 'on' : '' }}" href="{{ route('users.index') }}">Pengguna dan peran</a>
                <a class="nav {{ request()->routeIs('audit.*') ? 'on' : '' }}" href="{{ route('audit.index') }}">Log audit</a>
            @endif

            <a class="nav {{ request()->routeIs('notifs.*') ? 'on' : '' }}" href="{{ route('notifs.index') }}">Notifikasi</a>
        </nav>

        <div class="me">
            <div class="av">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div><b>{{ auth()->user()->name }}</b><span>{{ ucfirst(auth()->user()->role) }}</span></div>
        </div>

        <a class="lnk" href="{{ route('password.edit') }}">Ubah kata sandi</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="lnk" type="submit">Keluar</button>
        </form>
    </aside>

    <div class="col">
        <main class="page">
            <div class="sheet">
                @if (session('status'))
                    <div class="notice"><div>{{ session('status') }}</div></div>
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
<script>
    
</script>
</html>
