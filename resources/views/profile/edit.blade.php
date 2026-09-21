@extends('layouts.app')
@section('title', 'Profil saya')

@section('content')
<div class="ph"><div><h1>Profil saya</h1></div></div>

<section class="panel" style="max-width:600px">
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')
        <div class="pad">
            <div class="field">
                <label for="nisn">NISN</label>
                <input id="nisn" name="nisn" value="{{ old('nisn', $profile->nisn) }}">
                <div class="err">{{ $errors->first('nisn') }}</div>
            </div>
            <div class="field">
                <label for="kelas">Kelas</label>
                <input id="kelas" name="kelas" value="{{ old('kelas', $profile->kelas) }}">
                <div class="err">{{ $errors->first('kelas') }}</div>
            </div>
            <div class="field">
                <label for="tgl_lahir">Tanggal lahir</label>
                <input id="tgl_lahir" name="tgl_lahir" type="date" value="{{ old('tgl_lahir', $profile->tgl_lahir?->format('Y-m-d')) }}">
                <div class="err">{{ $errors->first('tgl_lahir') }}</div>
            </div>
            <div class="field">
                <label for="telp">Nomor HP</label>
                <input id="telp" name="telp" value="{{ old('telp', $profile->telp) }}">
                <div class="err">{{ $errors->first('telp') }}</div>
            </div>
            <div class="field">
                <label for="alamat">Alamat</label>
                <input id="alamat" name="alamat" value="{{ old('alamat', $profile->alamat) }}">
                <div class="err">{{ $errors->first('alamat') }}</div>
            </div>
            <div class="field">
                <label for="wali">Nama orang tua atau wali</label>
                <input id="wali" name="wali" value="{{ old('wali', $profile->wali) }}">
                <div class="err">{{ $errors->first('wali') }}</div>
            </div>
            <div class="field">
                <label for="telp_wali">Nomor HP orang tua atau wali</label>
                <input id="telp_wali" name="telp_wali" value="{{ old('telp_wali', $profile->telp_wali) }}">
                <div class="err">{{ $errors->first('telp_wali') }}</div>
            </div>

            <button class="btn" type="submit">Simpan perubahan</button>
        </div>
    </form>
</section>
@endsection
