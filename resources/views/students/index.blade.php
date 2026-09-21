@extends('layouts.app')
@section('title', 'Data siswa')

@section('content')
<div class="ph"><div><h1>Data siswa</h1></div></div>

<section class="panel">
    <form class="filters" method="GET" action="{{ route('students.index') }}">
        <input name="q" type="search" value="{{ $q }}" placeholder="Cari nama">
        <button class="btn ghost sm" type="submit">Cari</button>
    </form>

    <div class="tw">
        @if ($students->isEmpty())
            <div class="empty"><b>Tidak ada siswa yang cocok</b></div>
        @else
            <table>
                <thead><tr><th>Nama</th><th>Kelas</th><th>NISN</th><th>Permohonan</th></tr></thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td><a href="{{ route('students.show', $student) }}">{{ $student->name }}</a></td>
                            <td>{{ $student->profile->kelas ?? '-' }}</td>
                            <td>{{ $student->profile->nisn ?? '-' }}</td>
                            <td>{{ $student->permohonans_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $students->links() }}
</section>
@endsection
