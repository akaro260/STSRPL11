@extends('layouts.app')
@section('title', 'Ajukan permohonan')

@section('content')
<div class="ph"><div><h1>Ajukan permohonan</h1></div></div>

<section class="panel" style="max-width:600px">
    <form method="POST" action="{{ route('permohonan.store') }}">
        @csrf
        <div class="pad">
            <div class="field">
                <label for="category">Jenis permohonan</label>
                <select id="category" name="category">
                    @foreach (\App\Support\Workflow::$categories as $key => $label)
                        <option value="{{ $key }}" @selected(old('category') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="err">{{ $errors->first('category') }}</div>
            </div>

            <div class="field">
                <label for="title">Judul</label>
                <input id="title" name="title" value="{{ old('title') }}" maxlength="90">
                <div class="err">{{ $errors->first('title') }}</div>
            </div>

            <div class="fgrid">
                <div class="field">
                    <label for="from_date">Mulai tanggal (khusus cuti)</label>
                    <input id="from_date" name="from_date" type="date" value="{{ old('from_date') }}">
                    <div class="err">{{ $errors->first('from_date') }}</div>
                </div>
                <div class="field">
                    <label for="to_date">Sampai tanggal (khusus cuti)</label>
                    <input id="to_date" name="to_date" type="date" value="{{ old('to_date') }}">
                    <div class="err">{{ $errors->first('to_date') }}</div>
                </div>
            </div>

            <div class="field">
                <label for="description">Keterangan</label>
                <textarea id="description" name="description">{{ old('description') }}</textarea>
                <div class="err">{{ $errors->first('description') }}</div>
            </div>

            <button class="btn" type="submit">Kirim permohonan</button>
        </div>
    </form>
</section>
@endsection
