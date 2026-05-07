<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\Paciente;
use App\Models\Pagamento;
use App\Models\Parcela;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function index()
    {
        $pagamentos = Pagamento::with(['paciente', 'orcamento'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('pagamentos.index', compact('pagamentos'));
    }

    public function create(Request $request)
    {
        $pacientes   = Paciente::orderBy('nome')->get();
        $orcamento   = $request->orcamento_id
            ? Orcamento::with('itens')->find($request->orcamento_id)
            : null;
        $paciente_id = $request->paciente_id;

        return view('pagamentos.create', compact('pacientes', 'orcamento', 'paciente_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id'      => 'required|exists:pacientes,id',
            'orcamento_id'     => 'nullable|exists:orcamentos,id',
            'descricao'        => 'required|string|max:255',
            'valor_total'      => 'required|numeric|min:0.01',
            'numero_parcelas'  => 'required|integer|min:1|max:60',
            'forma_pagamento'  => 'required|in:dinheiro,cartao_credito,cartao_debito,pix,convenio,boleto',
            'data_primeiro_vencimento' => 'required|date',
            'observacoes'      => 'nullable|string',
        ]);

        $pagamento = Pagamento::create([
            'paciente_id'     => $validated['paciente_id'],
            'orcamento_id'    => $validated['orcamento_id'] ?? null,
            'descricao'       => $validated['descricao'],
            'valor_total'     => $validated['valor_total'],
            'numero_parcelas' => $validated['numero_parcelas'],
            'forma_pagamento' => $validated['forma_pagamento'],
            'status'          => 'pendente',
            'observacoes'     => $validated['observacoes'] ?? null,
        ]);

        // Gera parcelas
        $valorParcela = round($validated['valor_total'] / $validated['numero_parcelas'], 2);
        $vencimento   = \Carbon\Carbon::parse($validated['data_primeiro_vencimento']);

        for ($i = 1; $i <= $validated['numero_parcelas']; $i++) {
            // Última parcela absorve diferença de arredondamento
            $valor = $i === $validated['numero_parcelas']
                ? $validated['valor_total'] - ($valorParcela * ($validated['numero_parcelas'] - 1))
                : $valorParcela;

            Parcela::create([
                'pagamento_id'    => $pagamento->id,
                'numero'          => $i,
                'valor'           => $valor,
                'data_vencimento' => $vencimento->copy()->addMonths($i - 1),
                'status'          => 'pendente',
            ]);
        }

        return redirect()->route('pagamentos.show', $pagamento->id)
            ->with('success', 'Pagamento registrado com sucesso!');
    }

    public function show(Pagamento $pagamento)
    {
        $pagamento->load(['paciente', 'orcamento', 'parcelas']);
        return view('pagamentos.show', compact('pagamento'));
    }

    public function baixarParcela(Request $request, Parcela $parcela)
    {
        $parcela->update([
            'status'          => 'pago',
            'data_pagamento'  => now(),
        ]);

        $parcela->pagamento->atualizarStatus();

        return redirect()->route('pagamentos.show', $parcela->pagamento_id)
            ->with('success', 'Parcela baixada com sucesso!');
    }

    public function cancelarParcela(Parcela $parcela)
    {
        $parcela->update(['status' => 'cancelado']);
        $parcela->pagamento->atualizarStatus();

        return redirect()->route('pagamentos.show', $parcela->pagamento_id)
            ->with('success', 'Parcela cancelada com sucesso!');
    }

    public function destroy(Pagamento $pagamento)
    {
        $pagamento->delete();
        return redirect()->route('pagamentos.index')
            ->with('success', 'Pagamento cancelado com sucesso!');
    }
}