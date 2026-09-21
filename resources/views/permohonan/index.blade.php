@extends('layouts.app')
@section('title', 'Permohonan')

@section('content')
<div class="ph">
    <div><h1>Permohonan</h1></div>
    @if (auth()->user()->role === 'siswa')
        <a class="btn" href="{{ route('permohonan.create') }}">Ajukan permohonan</a>
    @endif
</div>

<section class="panel">
    <form class="filters" method="GET" action="{{ route('permohonan.index') }}">
        <input name="q" type="search" value="{{ $q }}" placeholder="Cari nomor atau judul">
        <select name="status" onchange="this.form.submit()">
            <option value="">Semua status</option>
            @foreach (\App\Support\Workflow::$statuses as $item)
                <option value="{{ $item }}" @selected($status === $item)>{{ $item }}</option>
            @endforeach
        </select>
        <button class="btn ghost sm" type="submit">Cari</button>
    </form>

    <div class="tw">
        @if ($permohonan->isEmpty())
            <div class="empty"><b>Belum ada permohonan</b></div>
        @else
            <table>
                <thead><tr><th>No.</th><th>Judul</th><th>Pemohon</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach ($permohonan as $item)
                        <tr>
                            <td>{{ $item->no }}</td>
                            <td><a href="{{ route('permohonan.show', $item) }}">{{ $item->title }}</a></td>
                            <td>{{ $item->student->name }}</td>
                            <td><span class="chip {{ \App\Support\Workflow::statusClass($item->status) }}">{{ $item->status }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{ $permohonan->links() }}
</section>
@endsection
