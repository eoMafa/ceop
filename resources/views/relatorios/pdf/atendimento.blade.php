<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Atendimento</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; padding: 20px; }
        h1 { font-size: 18px; color: #1a56a0; }
        h2 { font-size: 14px; color: #1a56a0; margin: 15px 0 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header p { color: #666; font-size: 11px; }
        .periodo { text-align: center; color: #666; margin-bottom: 15px; }
        .cards { display: flex; gap: 10px; margin-bottom: 15px; }
        .card { flex: 1; border: 1px solid #ddd; border-radius: 4px; padding: 10px; text-align: center; }
        .card .valor { font-size: 20px; font-weight: bold; color: #1a56a0; }
        .card .label { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 11px; }
        th { background: #1a56a0; color: white; padding: 6px; text-align: left; }
        td { padding: 5px 6px; border-bottom: 1px solid #eee; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Relatório de Atendimento</p>
    </div>

    <div class="periodo">Período: {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</div>

    <div class="cards">
        <div class="card">
            <div class="label">Total</div>
            <div class="valor">{{ $agendamentos->count() }}</div>
        </div>
        <div class="card">
            <div class="label">Concluídos</div>
            <div class="valor" style="color:#38a169">{{ $porStatus->get('concluido', 0) }}</div>
        </div>
        <div class="card">
            <div class="label">Faltas</div>
            <div class="valor" style="color:#d69e2e">{{ $faltas }}</div>
        </div>
        <div class="card">
            <div class="label">Cancelamentos</div>
            <div class="valor" style="color:#e53e3e">{{ $cancelamentos }}</div>
        </div>
    </div>

    <h2>Por Dentista</h2>
    <table>
        <thead><tr><th>Dentista</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($porDentista as $dentista => $total)
                <tr><td>{{ $dentista }}</td><td>{{ $total }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>Por Procedimento</h2>
    <table>
        <thead><tr><th>Procedimento</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($porProcedimento as $proc => $total)
                <tr><td>{{ $proc }}</td><td>{{ $total }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h2>Listagem de Agendamentos</h2>
    <table>
        <thead><tr><th>Data/Hora</th><th>Paciente</th><th>Dentista</th><th>Procedimento</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($agendamentos as $a)
                <tr>
                    <td>{{ $a->data_hora_inicio->format('d/m/Y H:i') }}</td>
                    <td>{{ $a->paciente->nome }}</td>
                    <td>{{ $a->dentista->name }}</td>
                    <td>{{ $a->procedimento->nome }}</td>
                    <td>{{ ucfirst($a->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Gerado em {{ now()->format('d/m/Y \à\s H:i') }} — {{ config('app.name') }}</div>
</body>
</html>