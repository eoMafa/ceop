<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Estoque</title>
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
        .text-danger { color: #e53e3e; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🦷 {{ config('app.name') }}</h1>
        <p>Relatório de Estoque</p>
    </div>

    <div class="periodo">Período: {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</div>

    @if($estoqueBaixo->count() > 0)
        <h2>⚠️ Produtos com Estoque Baixo</h2>
        <table>
            <thead><tr><th>Produto</th><th>Categoria</th><th>Estoque Atual</th><th>Mínimo</th></tr></thead>
            <tbody>
                @foreach($estoqueBaixo as $produto)
                    <tr>
                        <td>{{ $produto->nome }}</td>
                        <td>{{ $produto->categoria->nome ?? '—' }}</td>
                        <td class="text-danger">{{ number_format($produto->estoque_atual, 2, ',', '.') }} {{ $produto->unidade }}</td>
                        <td>{{ number_format($produto->estoque_minimo, 2, ',', '.') }} {{ $produto->unidade }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($custoPorProduto->count() > 0)
        <h2>Custo de Materiais em Consultas</h2>
        <table>
            <thead><tr><th>Produto</th><th>Quantidade</th><th>Custo Total</th></tr></thead>
            <tbody>
                @foreach($custoPorProduto as $nome => $dados)
                    <tr>
                        <td>{{ $nome }}</td>
                        <td>{{ number_format($dados['quantidade'], 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($dados['custo'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"><strong>Total</strong></td>
                    <td><strong>R$ {{ number_format($custoTotal, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    @endif

    <h2>Movimentações no Período</h2>
    <table>
        <thead><tr><th>Data</th><th>Produto</th><th>Tipo</th><th>Quantidade</th><th>Motivo</th></tr></thead>
        <tbody>
            @forelse($movimentacoes as $mov)
                <tr>
                    <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $mov->produto->nome }}</td>
                    <td>{{ ucfirst($mov->tipo) }}</td>
                    <td>{{ number_format($mov->quantidade, 2, ',', '.') }}</td>
                    <td>{{ $mov->motivo ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:#666">Nenhuma movimentação.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Gerado em {{ now()->format('d/m/Y \à\s H:i') }} — {{ config('app.name') }}</div>
</body>
</html>