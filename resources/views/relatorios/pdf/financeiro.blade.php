<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Financeiro</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; padding: 20px; }
        h1 { font-size: 18px; color: #1a56a0; margin-bottom: 5px; }
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
        <p>Relatório Financeiro</p>
    </div>

    <div class="periodo">Período: {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</div>

    <div class="cards">
        <div class="card">
            <div class="label">Receita no Período</div>
            <div class="valor">R$ {{ number_format($receitaPeriodo, 2, ',', '.') }}</div>
        </div>
        <div class="card">
            <div class="label">Parcelas Vencidas</div>
            <div class="valor" style="color:#e53e3e">{{ $parcelasVencidas->count() }}</div>
        </div>
    </div>

    <h2>Receita por Forma de Pagamento</h2>
    <table>
        <thead><tr><th>Forma</th><th>Quantidade</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($receitaPorForma as $forma)
                <tr>
                    <td>{{ ucfirst(str_replace('_', ' ', $forma->forma_pagamento)) }}</td>
                    <td>{{ $forma->quantidade }}</td>
                    <td>R$ {{ number_format($forma->total, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Orçamentos por Status</h2>
    <table>
        <thead><tr><th>Status</th><th>Quantidade</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($orcamentosPorStatus as $orc)
                <tr>
                    <td>{{ ucfirst($orc->status) }}</td>
                    <td>{{ $orc->quantidade }}</td>
                    <td>R$ {{ number_format($orc->total, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($parcelasVencidas->count() > 0)
        <h2>Parcelas Vencidas</h2>
        <table>
            <thead><tr><th>Paciente</th><th>Vencimento</th><th>Valor</th></tr></thead>
            <tbody>
                @foreach($parcelasVencidas as $parcela)
                    <tr>
                        <td>{{ $parcela->pagamento->paciente->nome }}</td>
                        <td>{{ $parcela->data_vencimento->format('d/m/Y') }}</td>
                        <td>R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">Gerado em {{ now()->format('d/m/Y \à\s H:i') }} — {{ config('app.name') }}</div>
</body>
</html>