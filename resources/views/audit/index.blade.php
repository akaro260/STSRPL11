
@extends('layouts.app')

@section('title', 'Log audit')

@section('content')

<div class="ph">
    <div>
        <h1>Log audit</h1>
    </div>

    <a
        class="btn ghost"
        href="{{ route('audit.export', request()->query()) }}"
    >
        Ekspor CSV
    </a>
</div>

<section class="panel">

    {{-- FILTER --}}
    <form
        class="filters"
        method="GET"
        action="{{ route('audit.index') }}"
    >
        <input
            name="q"
            type="search"
            value="{{ $q }}"
            placeholder="Cari pengguna, aksi, objek..."
        >

        <select name="group">
            <option value="">Semua aksi</option>

            @foreach ($groups as $key => $label)
                <option
                    value="{{ $key }}"
                    @selected($group === $key)
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <input
            name="from"
            type="date"
            value="{{ $from }}"
            title="Tanggal mulai"
        >

        <input
            name="to"
            type="date"
            value="{{ $to }}"
            title="Tanggal akhir"
        >

        <button
            class="btn ghost sm"
            type="submit"
        >
            Terapkan
        </button>
    </form>

    {{-- TABLE --}}
    <div class="tw">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                    <th>Objek</th>
                    <th>Detail</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td>
                            {{ $log->created_at->format('d-m-Y H:i') }}
                        </td>

                        <td>
                            {{ $log->user_name }}
                        </td>

                        <td>
                            {{ $log->action }}
                        </td>

                        <td>
                            {{ $log->target }}
                        </td>

                        <td>
                            {{ $log->detail }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty">
                                <b>Belum ada log audit</b>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    {{ $logs->links() }}

</section>

@endsection