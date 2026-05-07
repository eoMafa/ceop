<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo #{{ $pagamento->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; padding: 30px; }

        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #1a56a0; }
        .header p { color: #666; font-size: 12px; margin-top: 4px; }

        .titulo-recibo { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 2px; }

        .info-grid { display: table; width: 100%; margin-bottom: 20px; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; font-weight: bold; width: 35%; padding: 4px 0; color: #555; }
        .info-value { display: table-cell; padding: 4px 0; }

        .section { margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; text-transform: uppercase; color: #1a56a0; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; letter-spacing: 1px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table th { background: #1a56a0; color: white; padding: 8px; text-align: left; font-size: 12px; }
        table td { padding: 7px 8px; border-bottom: 1px solid #eee; font-size: 12px; }
        table tr:last-child td { border-bottom: none; }
        table .text-right { text-align: right; }
        table .text-center { text-align: center; }

        .total-box { background: #f5f5f5; border: 1px solid #ddd; border-radius: 4px; padding: 12px 15px; margin-bottom: 20px; }
        .total-box .total-row { display: flex; justify-content: space-between; padding: 3px 0; }
        .total-box .total-final { font-size: 16px; font-weight: bold; color: #1a56a0; border-top: 1px solid #ccc; padding-top: 8px; margin-top: 5px; }

        .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-primary { background: #cce5ff; color: #004085; }

        .assinaturas { display: flex; justify-content: space-between; margin-top: 50px; }
        .assinatura { text-align: center; width: 45%; }
        .assinatura .linha { border-top: 1px solid #333; padding-top: 8px; margin-top: 40px; font-size: 12px; }

        .footer { text-align: center; margin-top: 30px; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }

        .numero-recibo { float: right; font-size: 12px; color: #666; }
    </style>
</head>
<body>

    {{-- Cabeçalho --}}
    <div class="header">
        <div class="numero-recibo">Recibo nº {{ str_pad($pagamento->id, 6, '0', STR_PAD_LEFT) }}</div>
        <h1>{{ config('app.name') }}</h1>
        <p>Sistema de Gestão Odontológica</p>
    </div>

    <div class="titulo-recibo">Recibo de Pagamento</div>

    {{-- Dados do paciente --}}
    <div class="section">
        <div class="section-title">Dados do Paciente</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nome:</div>
                <div class="info-value">{{ $pagamento->paciente->nome }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">CPF:</div>
                <div class="info-value">{{ $pagamento->paciente->cpf ?? '—' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Telefone:</div>
                <div class="info-value">{{ $pagamento->paciente->telefone ?? '—' }}</div>
            </div>
        </div>
    </div>

    {{-- Dados do pagamento --}}
    <div class="section">
        <div class="section-title">Dados do Pagamento</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Descrição:</div>
                <div class="info-value">{{ $pagamento->descricao }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Forma de Pagamento:</div>
                <div class="info-value">{{ ucfirst(str_replace('_', ' ', $pagamento->forma_pagamento)) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Data de Emissão:</div>
                <div class="info-value">{{ now()->format('d/m/Y H:i') }}</div>
            </div>
            @if($pagamento->orcamento)
            <div class="info-row">
                <div class="info-label">Orçamento Ref.:</div>
                <div class="info-value">#{{ str_pad($pagamento->orcamento->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Parcelas --}}
    <div class="section">
        <div class="section-title">Parcelas</div>
        <table>
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Vencimento</th>
                    <th>Pagamento</th>
                    <th class="text-right">Valor</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagamento->parcelas as $parcela)
                    <tr>
                        <td class="text-center">{{ $parcela->numero }}</td>
                        <td>{{ $parcela->data_vencimento->format('d/m/Y') }}</td>
                        <td>{{ $parcela->data_pagamento ? $parcela->data_pagamento->format('d/m/Y') : '—' }}</td>
                        <td class="text-right">R$ {{ number_format($parcela->valor, 2, ',', '.') }}</td>
                        <td class="text-center">
                            @if($parcela->status === 'pago')
                                <span class="badge badge-success">Pago</span>
                            @elseif($parcela->status === 'pendente')
                                <span class="badge badge-warning">Pendente</span>
                            @else
                                <span class="badge badge-danger">Cancelado</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Total --}}
    <div class="total-box">
        @php
            $totalPago = $pagamento->parcelas->where('status', 'pago')->sum('valor');
            $totalPendente = $pagamento->parcelas->where('status', 'pendente')->sum('valor');
        @endphp
        <div class="total-row">
            <span>Total do Pagamento:</span>
            <span>R$ {{ number_format($pagamento->valor_total, 2, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span>Total Pago:</span>
            <span style="color:#155724">R$ {{ number_format($totalPago, 2, ',', '.') }}</span>
        </div>
        @if($totalPendente > 0)
        <div class="total-row">
            <span>Total Pendente:</span>
            <span style="color:#856404">R$ {{ number_format($totalPendente, 2, ',', '.') }}</span>
        </div>
        @endif
        <div class="total-row total-final">
            <span>Status:</span>
            <span>{{ ucfirst($pagamento->status) }}</span>
        </div>
    </div>

    {{-- Observações --}}
    @if($pagamento->observacoes)
        <div class="section">
            <div class="section-title">Observações</div>
            <p>{{ $pagamento->observacoes }}</p>
        </div>
    @endif

    {{-- Assinaturas --}}
    <div class="assinaturas">
        <div class="assinatura">
            <div class="linha">Responsável pelo Estabelecimento</div>
        </div>
        <div class="assinatura">
            <div class="linha">{{ $pagamento->paciente->nome }}</div>
        </div>
    </div>

    {{-- Rodapé --}}
    <div class="footer">
        Documento gerado em {{ now()->format('d/m/Y \à\s H:i') }} — {{ config('app.name') }}
    </div>

</body>
</html>