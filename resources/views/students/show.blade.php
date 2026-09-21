@extends('layouts.app')
@section('title', $student->name)

@section('content')
<div class="ph"><div><h1>{{ $student->name }}</h1><p>{{ $student->email }}</p></div></div>

<section class="panel" style="max-width:600px">
    <div class="pad">
        <dl class="dl">
            @foreach (\App\Support\Workflow::$profileFields as $field => $label)
                <dt>{{ $label }}</dt>
                <dd>{{ $student->profile->$field ?? '-' }}</dd>
            @endforeach
        </dl>
    </div>
</section>
@endsection
