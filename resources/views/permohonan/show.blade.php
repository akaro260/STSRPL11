@extends('layouts.app')
@section('title', $permohonan->no)

@section('content')
<div class="ph">
    <div><h1>{{ $permohonan->title }}</h1><p>{{ $permohonan->no }}</p></div>
    <span class="chip {{ \App\Support\Workflow::statusClass($permohonan->status) }}">{{ $permohonan->status }}</span>
</div>

<div class="grid2">
    <div class="stack">
        <section class="panel">
            <header><h2>Rincian</h2></header>
            <div class="pad">
                <dl class="dl">
                    <dt>Pemohon</dt><dd>{{ $permohonan->student->name }}</dd>
                    <dt>Jenis</dt><dd>{{ \App\Support\Workflow::$categories[$permohonan->category] }}</dd>
                    @if ($permohonan->from_date)
                        <dt>Periode izin</dt><dd>{{ $permohonan->from_date->format('d-m-Y') }} sampai {{ $permohonan->to_date->format('d-m-Y') }}</dd>
                    @endif
                    <dt>Diajukan</dt><dd>{{ $permohonan->created_at->format('d-m-Y H:i') }}</dd>
                </dl>
                <p>{{ $permohonan->description }}</p>
            </div>
        </section>

        <section class="panel">
            <header><h2>Langkah berikutnya</h2></header>
            <div class="pad">
                <p>{{ $info }}</p>

                @if (count($actions) > 0)
                    <form method="POST" action="{{ route('permohonan.transition', $permohonan) }}">
                        @csrf
                        <div class="field">
                            <label for="note">Catatan</label>
                            <textarea id="note" name="note"></textarea>
                            <div class="err">{{ $errors->first('note') }}</div>
                        </div>
                        <div class="acts">
                            @foreach ($actions as $action)
                                <button class="btn" type="submit" name="to" value="{{ $action }}">{{ \App\Support\Workflow::actionLabel($action) }}</button>
                            @endforeach
                        </div>
                    </form>
                @endif
            </div>
        </section>
    </div>

    <section class="panel">
        <header><h2>Perjalanan permohonan</h2></header>
        <div class="pad">
            <ol class="tl">
                @foreach ($permohonan->steps as $step)
                    <li class="{{ \App\Support\Workflow::statusClass($step->status) }}">
                        <div class="tb">
                            <span class="chip {{ \App\Support\Workflow::statusClass($step->status) }}">{{ $step->status }}</span>
                            <p><b>{{ $step->actor_name }}</b> <small>{{ ucfirst($step->role) }}</small></p>
                            <small>{{ $step->created_at->format('d-m-Y H:i') }}</small>
                            @if ($step->note)<p>{{ $step->note }}</p>@endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
</div>
@endsection
