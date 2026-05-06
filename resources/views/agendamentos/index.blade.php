@extends('layouts.app')

@section('title', 'Agendamentos')

@section('breadcrumb')
    <li class="breadcrumb-item active">Agendamentos</li>
@endsection

@section('actions')
    <a href="{{ route('agendamentos.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Novo Agendamento
    </a>
@endsection

@section('content')
    {{-- Filtro por dentista --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="form-label mb-0">Filtrar por dentista:</label>
                </div>
                <div class="col-md-3">
                    <select id="filtro-dentista" class="form-select">
                        <option value="">Todos</option>
                        @foreach($dentistas as $dentista)
                            <option value="{{ $dentista->id }}">{{ $dentista->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto ms-auto">
                    {{-- Legenda --}}
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge text-white" style="background:#4299e1">Agendado</span>
                        <span class="badge text-white" style="background:#48bb78">Confirmado</span>
                        <span class="badge text-white" style="background:#f56565">Cancelado</span>
                        <span class="badge text-white" style="background:#667eea">Concluído</span>
                        <span class="badge text-white" style="background:#ed8936">Falta</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Calendário --}}
    <div class="card">
        <div class="card-body">
            <div id="calendario"></div>
        </div>
    </div>

    {{-- Modal de detalhes --}}
    <div class="modal modal-blur fade" id="modal-agendamento" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Agendamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Paciente</dt>
                        <dd class="col-sm-8" id="modal-paciente"></dd>

                        <dt class="col-sm-4">Dentista</dt>
                        <dd class="col-sm-8" id="modal-dentista"></dd>

                        <dt class="col-sm-4">Procedimento</dt>
                        <dd class="col-sm-8" id="modal-procedimento"></dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8" id="modal-status"></dd>

                        <dt class="col-sm-4">Observações</dt>
                        <dd class="col-sm-8" id="modal-observacoes"></dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <a href="#" id="modal-btn-editar" class="btn btn-primary">Editar</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendario');
    const filtroDentista = document.getElementById('filtro-dentista');

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
        locale: 'pt-br',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia',
            list: 'Lista',
        },
        initialView: 'timeGridWeek',
        slotMinTime: '07:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        nowIndicator: true,
        height: 'auto',
        events: function (info, successCallback, failureCallback) {
            fetch(`{{ route('agendamentos.eventos') }}?start=${info.startStr}&end=${info.endStr}&dentista_id=${filtroDentista.value}`)
                .then(r => r.json())
                .then(data => successCallback(data))
                .catch(() => failureCallback());
        },
        // Clique em evento — abre modal
        eventClick: function (info) {
            const props = info.event.extendedProps;
            const id = info.event.id;

            document.getElementById('modal-paciente').textContent = props.paciente;
            document.getElementById('modal-dentista').textContent = props.dentista;
            document.getElementById('modal-procedimento').textContent = props.procedimento;
            document.getElementById('modal-status').textContent = props.status;
            document.getElementById('modal-observacoes').textContent = props.observacoes || '—';
            document.getElementById('modal-btn-editar').href = `/agendamentos/${id}/edit`;

            const modal = new bootstrap.Modal(document.getElementById('modal-agendamento'));
            modal.show();
        },
        // Clique em data vazia — abre criar agendamento
        dateClick: function (info) {
            window.location.href = `{{ route('agendamentos.create') }}?data_hora=${info.dateStr}`;
        },
    });

    calendar.render();

    // Refetch ao mudar filtro
    filtroDentista.addEventListener('change', function () {
        calendar.refetchEvents();
    });
});
</script>
@endpush