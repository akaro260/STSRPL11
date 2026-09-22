@extends('layouts.app')

@section('title', 'Permohonan')

@section('content')

<div class="ph">


<div>
    <h1>Permohonan</h1>
</div>

@if (auth()->user()->role === 'siswa')
    <a class="btn" href="{{ route('permohonan.create') }}">
        Ajukan permohonan
    </a>
@endif


</div>

<section class="panel">


<form class="filters" method="GET" action="{{ route('permohonan.index') }}">

    <input
        name="q"
        type="search"
        value="{{ $q }}"
        placeholder="Cari nomor atau judul"
    >

    <select name="status" onchange="this.form.submit()">

        <option value="">
            Semua status
        </option>

        @foreach (\App\Support\Workflow::$statuses as $item)

            <option
                value="{{ $item }}"
                @selected($status === $item)
            >
                {{ $item }}
            </option>

        @endforeach

    </select>

    <button class="btn ghost sm" type="submit">
        Cari
    </button>

</form>


<div class="tw">

    @if ($permohonan->isEmpty())

        <div class="empty">
            <b>Belum ada permohonan</b>
        </div>

    @else

        <table>

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Judul</th>
                    <th>Pemohon</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($permohonan as $item)

                    <tr>

                        <td>
                            <button
                                type="button"
                                class="table-link"
                                onclick="openPermohonan({{ $item->id }})"
                            >
                                {{ $item->no }}
                            </button>
                        </td>

                        <td>
                            <button
                                type="button"
                                class="table-link"
                                onclick="openPermohonan({{ $item->id }})"
                            >
                                {{ $item->title }}
                            </button>
                        </td>

                        <td>
                            {{ $item->student->name }}
                        </td>

                        <td>
                            <span class="chip {{ \App\Support\Workflow::statusClass($item->status) }}">
                                {{ $item->status }}
                            </span>
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @endif

</div>


{{ $permohonan->links('components.custom') }}


</section>

{{-- ================================
MODAL DETAIL PERMOHONAN
================================ --}}

<div
    id="permohonanModal"
    class="modal-overlay"
    onclick="closePermohonan(event)"
>


<div
    class="modal"
    onclick="event.stopPropagation()"
>

    <div class="modal-head">

        <div>
            <span class="modal-eyebrow">
                Detail permohonan
            </span>

            <h2 id="modalTitle">
                -
            </h2>

            <p id="modalNo">
                -
            </p>
        </div>

        <button
            type="button"
            class="modal-close"
            onclick="closePermohonan()"
            aria-label="Tutup"
        >
            &times;
        </button>

    </div>


    <div class="modal-body">

        {{-- STATUS --}}

        <div class="modal-status">

            <span id="modalStatus" class="chip">
                -
            </span>

        </div>


        {{-- RINCIAN --}}

        <section class="modal-section">

            <h3>Rincian</h3>

            <dl class="dl">

                <dt>Pemohon</dt>
                <dd id="modalStudent">-</dd>

                <dt>Jenis</dt>
                <dd id="modalCategory">-</dd>

                <dt>Periode izin</dt>
                <dd id="modalPeriod">-</dd>

                <dt>Diajukan</dt>
                <dd id="modalCreated">-</dd>

            </dl>

            <div class="modal-description">

                <span>Deskripsi</span>

                <p id="modalDescription">
                    -
                </p>

            </div>

        </section>


        {{-- PERJALANAN --}}

        <section class="modal-section">

            <h3>Perjalanan permohonan</h3>

            <ol id="modalSteps" class="tl">
                <li class="empty-step">
                    Belum ada perjalanan.
                </li>
            </ol>

        </section>


        {{-- LANGKAH BERIKUTNYA --}}

        <section
            id="modalActions"
            class="modal-section"
        >

            <h3>Langkah berikutnya</h3>

            <p id="modalInfo">
                -
            </p>

            <form
                id="modalActionForm"
                method="POST"
                action=""
            >

                @csrf

                <div class="field">

                    <label for="modalNote">
                        Catatan
                    </label>

                    <textarea
                        id="modalNote"
                        name="note"
                    ></textarea>

                    <div class="err">
                        @error('note')
                            {{ $message }}
                        @enderror
                    </div>

                </div>

                <div
                    id="modalActionButtons"
                    class="acts"
                ></div>

            </form>

        </section>

    </div>

</div>


</div>

@php
    $currentRole = auth()->user()->role;

    $modalPermohonan = $permohonan->map(function ($item) use ($currentRole) {
        $actions = \App\Support\Workflow::nextStatuses(
            $currentRole,
            $item->status
        );

        $info = \App\Support\Workflow::info(
            $item->status,
            $currentRole
        );

        return [
            'id' => $item->id,

            'title' => $item->title,

            'no' => $item->no,

            'status' => $item->status,

            'statusClass' => \App\Support\Workflow::statusClass(
                $item->status
            ),

            'student' => $item->student->name,

            'category' => \App\Support\Workflow::$categories[$item->category]
                ?? $item->category,

            'fromDate' => $item->from_date
                ? $item->from_date->format('d-m-Y')
                : null,

            'toDate' => $item->to_date
                ? $item->to_date->format('d-m-Y')
                : null,

            'createdAt' => $item->created_at->format('d-m-Y H:i'),

            'description' => $item->description,

            'info' => $info,

            'actions' => collect($actions)->map(function ($action) {
                return [
                    'value' => $action,
                    'label' => \App\Support\Workflow::actionLabel($action),
                ];
            })->values(),

            'steps' => $item->steps->map(function ($step) {
                return [
                    'status' => $step->status,

                    'statusClass' => \App\Support\Workflow::statusClass(
                        $step->status
                    ),

                    'actorName' => $step->actor_name,

                    'role' => ucfirst($step->role),

                    'createdAt' => $step->created_at->format('d-m-Y H:i'),

                    'note' => $step->note,
                ];
            })->values(),
        ];
    })->values();
@endphp
<script>

const permohonanData = @json($modalPermohonan);


function openPermohonan(id)
{
    const item = permohonanData.find(
        permohonan => permohonan.id === id
    );

    if (!item) {
        return;
    }

    // ================================
    // INFORMASI UTAMA
    // ================================

    document.getElementById('modalTitle').textContent =
        item.title;

    document.getElementById('modalNo').textContent =
        item.no;


    // ================================
    // STATUS
    // ================================

    const status =
        document.getElementById('modalStatus');

    status.textContent =
        item.status;

    status.className =
        'chip ' + item.statusClass;


    // ================================
    // RINCIAN
    // ================================

    document.getElementById('modalStudent').textContent =
        item.student;

    document.getElementById('modalCategory').textContent =
        item.category;


    const period =
        document.getElementById('modalPeriod');

    if (item.fromDate && item.toDate) {

        period.textContent =
            item.fromDate + ' sampai ' + item.toDate;

    } else {

        period.textContent = '-';

    }


    document.getElementById('modalCreated').textContent =
        item.createdAt;

    document.getElementById('modalDescription').textContent =
        item.description || '-';
    // ================================
// LANGKAH BERIKUTNYA
// ================================

const modalActions =
    document.getElementById('modalActions');

const modalInfo =
    document.getElementById('modalInfo');

const modalForm =
    document.getElementById('modalActionForm');

const modalButtons =
    document.getElementById('modalActionButtons');

modalInfo.textContent = item.info || '-';

modalButtons.innerHTML = '';

modalForm.style.display = 'none';

if (item.actions && item.actions.length > 0) {

    modalForm.style.display = 'block';

    const transitionUrl =
        @json(route('permohonan.transition', ['permohonan' => '__ID__']))
            .replace('__ID__', item.id);

    modalForm.action = transitionUrl;

    item.actions.forEach(action => {

        const button =
            document.createElement('button');

        button.type = 'submit';

        button.name = 'to';

        button.value = action.value;

        button.className = 'btn';

        button.textContent = action.label;

        modalButtons.appendChild(button);
    });

} else {

    modalForm.style.display = 'none';
}

    // ================================
    // PERJALANAN PERMOHONAN
    // ================================

    const steps =
        document.getElementById('modalSteps');

    steps.innerHTML = '';


    if (!item.steps || item.steps.length === 0) {

        steps.innerHTML = `
            <li class="empty-step">
                Belum ada perjalanan.
            </li>
        `;

    } else {

        item.steps.forEach(step => {

            const li =
                document.createElement('li');

            li.className =
                step.statusClass;

            li.innerHTML = `
                <div class="tb">

                    <span class="chip ${escapeHtml(step.statusClass)}">
                        ${escapeHtml(step.status)}
                    </span>

                    <p>
                        <b>${escapeHtml(step.actorName)}</b>
                        <small>${escapeHtml(step.role)}</small>
                    </p>

                    <small>
                        ${escapeHtml(step.createdAt)}
                    </small>

                    ${
                        step.note
                            ? `<p>${escapeHtml(step.note)}</p>`
                            : ''
                    }

                </div>
            `;

            steps.appendChild(li);

        });

    }


    // ================================
    // BUKA MODAL
    // ================================

    document.getElementById('permohonanModal')
        .classList.add('show');

    document.body.classList.add('modal-open');
}


function closePermohonan(event)
{
    if (
        event &&
        event.target !== event.currentTarget
    ) {
        return;
    }

    document.getElementById('permohonanModal')
        .classList.remove('show');

    document.body.classList.remove('modal-open');
}


// ESC untuk menutup modal

document.addEventListener('keydown', function(event)
{
    if (event.key === 'Escape') {
        closePermohonan();
    }
});


// Mencegah HTML injection dari data database

function escapeHtml(value)
{
    const div =
        document.createElement('div');

    div.textContent =
        value ?? '';

    return div.innerHTML;
}

</script>


@endsection
