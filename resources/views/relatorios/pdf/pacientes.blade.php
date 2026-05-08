<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Pacientes</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; padding: 20px; }
        h1 { font-size: 18px; color: #1a56a0; }
        h2 { font-size: 14px; color: #1a56a0; margin: 15px 0 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header p { color: #666; font-size: 11px; }
        .periodo { text-align: center; color: #666; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 11px; }
        th { background: #1a56a0; color: white; padding: 6px; text-align: left; }
        td { padding: 5px 6px; border-bottom: 1px solid #eee; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🦷 {{ config('app.name') }}</h1>
        <p>Relatório de Pacientes</p>
    </div>

    <div class="periodo">Período: {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</div>

    <h2>🎂 Aniversariantes do Mês</h2>
    <table>
        <thead><tr><th>Nome</th><th>Nascimento</th><th>Idade</th><th>Telefone</th></tr></thead>
        <tbody>
            @forelse($aniversariantes as $p)
                <tr>
                    <td>{{ $p->nome }}</td>
                    <td>{{ $p->data_nascimento->format('d/m/Y') }}</td>
                    <td>{{ $p->idade }} anos</td>
                    <td>{{ $p->telefone ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:#666">Nenhum aniversariante.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Pacientes Cadastrados no Período</h2>
    <table>
        <thead><tr><th>Nome</th><th>CPF</th><th>Telefone</th><th>Cadastro</th></tr></thead>
        <tbody>
            @forelse($cadastradosPeriodo as $p)
                <tr>
                    <td>{{ $p->nome }}</td>
                    <td>{{ $p->cpf ?? '—' }}</td>
                    <td>{{ $p->telefone ?? '—' }}</td>
                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:#666">Nenhum paciente no período.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Gerado em {{ now()->format('d/m/Y \à\s H:i') }} — {{ config('app.name') }}</div>
</body>
</html>