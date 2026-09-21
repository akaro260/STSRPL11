@extends('layouts.app')
@section('title', 'Ubah kata sandi')

@section('content')
<div class="ph"><div><h1>Ubah kata sandi</h1></div></div>

<section class="panel" style="max-width:480px">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')
        <div class="pad">
            <div class="field">
                <label for="current_password">Kata sandi saat ini</label>
                <input id="current_password" name="current_password" type="password">
                <div class="err">{{ $errors->first('current_password') }}</div>
            </div>
            <div class="field">
                <label for="password">Kata sandi baru</label>
                <input id="password" name="password" type="password">
                <div class="err">{{ $errors->first('password') }}</div>
            </div>
            <div class="field">
                <label for="password_confirmation">Ulangi kata sandi baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password">
            </div>
            <button class="btn" type="submit">Simpan kata sandi</button>
        </div>
    </form>
</section>
@endsection
