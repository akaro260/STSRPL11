@extends('layouts.app')
@section('title', 'Pengguna')

@section('content')
<div class="ph"><div><h1>Pengguna dan peran</h1></div></div>

<section class="panel" style="margin-bottom:18px">
    <header><h2>Tambah pengguna</h2></header>
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="pad">
            <div class="fgrid">
                <div class="field">
                    <label for="name">Nama lengkap</label>
                    <input id="name" name="name" value="{{ old('name') }}">
                    <div class="err">{{ $errors->first('name') }}</div>
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}">
                    <div class="err">{{ $errors->first('email') }}</div>
                </div>
                <div class="field">
                    <label for="role">Peran</label>
                    <select id="role" name="role">
                        @foreach (\App\Support\Workflow::$roles as $key => $label)
                            <option value="{{ $key }}" @selected(old('role') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="password">Kata sandi awal</label>
                    <input id="password" name="password" type="text">
                    <div class="err">{{ $errors->first('password') }}</div>
                </div>
            </div>
            <button class="btn" type="submit">Tambah pengguna</button>
        </div>
    </form>
</section>

<section class="panel">
    <form class="filters" method="GET" action="{{ route('users.index') }}">
        <input name="q" type="search" value="{{ $q }}" placeholder="Cari nama atau email">
        <button class="btn ghost sm" type="submit">Cari</button>
    </form>

    <div class="tw">
        <table>
            <thead><tr><th>Nama</th><th>Peran</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach ($users as $row)
                    <tr>
                        <td><b>{{ $row->name }}</b><small>{{ $row->email }}</small></td>
                        <td>
                            @if ($row->id === auth()->id())
                                {{ \App\Support\Workflow::$roles[$row->role] }}
                            @else
                                <form method="POST" action="{{ route('users.role', $row) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" onchange="this.form.submit()">
                                        @foreach (\App\Support\Workflow::$roles as $key => $label)
                                            <option value="{{ $key }}" @selected($row->role === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @endif
                        </td>
                        <td>
                            @if ($row->active)
                                <span class="chip setuju">Aktif</span>
                            @else
                                <span class="chip tolak">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            @unless ($row->id === auth()->id())
                                <form method="POST" action="{{ route('users.status', $row) }}">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn ghost sm" type="submit">{{ $row->active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
</section>
@endsection
