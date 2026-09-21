<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SchoolAdmin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="login">
    <section class="hero">
        <div class="brand"><span>SchoolAdmin</span></div>
        <div>
            <h1>Urusan administrasi sekolah, rapi dalam satu buku.</h1>
            <p>Siswa mengajukan, petugas memproses, administrator memutuskan.</p>
        </div>
    </section>

    <section class="lf">
        <div class="lc">
            <h2>Masuk</h2>
            <p class="mut">Gunakan email dan kata sandi dari sekolah Anda.</p>

            @if ($errors->any())
                <div class="formerr">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                </div>
                <div class="field">
                    <label for="password">Kata sandi</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <button class="btn" type="submit" style="width:100%">Masuk</button>
            </form>

            <div class="demo">
                <div class="fine"><b>Akun contoh:</b></div>
                <p class="fine">Admin: admin@sekolah.id / Admin@123</p>
                <p class="fine">Petugas: petugas@sekolah.id / Petugas@123</p>
                <p class="fine">Siswa: siswa@sekolah.id / Siswa@123</p>
            </div>
        </div>
    </section>
</div>
</body>
</html>
