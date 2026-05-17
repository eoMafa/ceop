<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Convenio;
use App\Models\Orcamento;
use App\Models\OrcamentoArquivo;
use App\Models\OrcamentoItem;
use App\Models\Paciente;
use App\Models\Procedimento;
use App\Models\User;
use App\Rules\ArquivoSeguro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrcamentoController extends Controller
{
    public function index()
    {
        $orcamentos = Orcamento::with(['paciente', 'dentista'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('orcamentos.index', compact('orcamentos'));
    }

    public function create(Request $request)
    {
        $pacientes     = Paciente::orderBy('nome')->get();
        $dentistas     = User::where('role', 'dentista')->where('ativo', true)->orderBy('name')->get();
        $procedimentos = Procedimento::orderBy('nome')->get();
        $convenios     = Convenio::where('ativo', true)->orderBy('nome')->get();
        $paciente_id   = $request->input('paciente_id');

        return view('orcamentos.create', compact('pacientes', 'dentistas', 'procedimentos', 'convenios', 'paciente_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id'      => 'required|exists:pacientes,id',
            'dentista_id'      => 'required|exists:users,id',
            'convenio_id'      => 'nullable|exists:convenios,id',
            'desconto_tipo'    => 'required|in:nenhum,percentual,valor_fixo,convenio',
            'desconto_valor'   => 'nullable|numeric|min:0',
            'observacoes'      => 'nullable|string',
            'arquivos.*'       => ['nullable', 'file', 'max:10240', new ArquivoSeguro],
            'itens'            => 'required|array|min:1',
            'itens.*.procedimento_id' => 'required|exists:procedimentos,id',
            'itens.*.dente'    => 'nullable|string|max:10',
            'itens.*.quantidade' => 'required|integer|min:1',
            'itens.*.valor_unitario' => 'required|numeric|min:0',
        ]);

        $orcamento = Orcamento::create([
            'paciente_id'    => $validated['paciente_id'],
            'dentista_id'    => $validated['dentista_id'],
            'convenio_id'    => $validated['convenio_id'] ?? null,
            'status'         => 'rascunho',
            'desconto_tipo'  => $validated['desconto_tipo'],
            'desconto_valor' => $validated['desconto_valor'] ?? 0,
            'observacoes'    => $validated['observacoes'] ?? null,
        ]);

        foreach ($validated['itens'] as $item) {
            $total = $item['quantidade'] * $item['valor_unitario'];
            $orcamento->itens()->create([
                'procedimento_id' => $item['procedimento_id'],
                'dente'           => $item['dente'] ?? null,
                'quantidade'      => $item['quantidade'],
                'valor_unitario'  => $item['valor_unitario'],
                'valor_total'     => $total,
            ]);
        }

        $orcamento->load(['itens', 'convenio']);
        $orcamento->calcularTotais();

        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $arquivo) {
                $caminho = $arquivo->store("orcamentos/{$orcamento->id}", 'public');
                $orcamento->arquivos()->create([
                    'nome_original' => $arquivo->getClientOriginalName(),
                    'caminho'       => $caminho,
                    'tipo_mime'     => $arquivo->getMimeType(),
                    'tamanho'       => $arquivo->getSize(),
                ]);
            }
        }

        // Se vier de um agendamento, vincula e marca como concluído
        if ($request->filled('agendamento_id')) {
            $agendamento = Agendamento::find($request->agendamento_id);
            if ($agendamento) {
                $agendamento->update(['status' => 'concluido']);
            }
        }

        return redirect()->route('orcamentos.show', $orcamento->id)
            ->with('success', 'Orçamento criado com sucesso!');
    }

    public function show(Orcamento $orcamento)
    {
        $orcamento->load(['paciente', 'dentista', 'convenio', 'itens.procedimento', 'pagamentos.parcelas', 'arquivos']);
        return view('orcamentos.show', compact('orcamento'));
    }

    public function edit(Orcamento $orcamento)
    {
        if ($orcamento->status !== 'rascunho') {
            return redirect()->route('orcamentos.show', $orcamento->id)
                ->with('error', 'Apenas orçamentos em rascunho podem ser editados.');
        }

        $pacientes     = Paciente::orderBy('nome')->get();
        $dentistas     = User::where('role', 'dentista')->where('ativo', true)->orderBy('name')->get();
        $procedimentos = Procedimento::orderBy('nome')->get();
        $convenios     = Convenio::where('ativo', true)->orderBy('nome')->get();

        $orcamento->load(['itens.procedimento', 'convenio']);

        return view('orcamentos.edit', compact('orcamento', 'pacientes', 'dentistas', 'procedimentos', 'convenios'));
    }

    public function update(Request $request, Orcamento $orcamento)
    {
        $validated = $request->validate([
            'paciente_id'             => 'required|exists:pacientes,id',
            'dentista_id'             => 'required|exists:users,id',
            'convenio_id'             => 'nullable|exists:convenios,id',
            'desconto_tipo'           => 'required|in:nenhum,percentual,valor_fixo,convenio',
            'desconto_valor'          => 'nullable|numeric|min:0',
            'observacoes'             => 'nullable|string',
            'arquivos.*'              => ['nullable', 'file', 'max:10240', new ArquivoSeguro],
            'itens'                   => 'required|array|min:1',
            'itens.*.procedimento_id' => 'required|exists:procedimentos,id',
            'itens.*.dente'           => 'nullable|string|max:10',
            'itens.*.quantidade'      => 'required|integer|min:1',
            'itens.*.valor_unitario'  => 'required|numeric|min:0',
        ]);

        $orcamento->update([
            'paciente_id'    => $validated['paciente_id'],
            'dentista_id'    => $validated['dentista_id'],
            'convenio_id'    => $validated['convenio_id'] ?? null,
            'desconto_tipo'  => $validated['desconto_tipo'],
            'desconto_valor' => $validated['desconto_valor'] ?? 0,
            'observacoes'    => $validated['observacoes'] ?? null,
        ]);

        // Recria os itens
        $orcamento->itens()->delete();
        foreach ($validated['itens'] as $item) {
            $total = $item['quantidade'] * $item['valor_unitario'];
            $orcamento->itens()->create([
                'procedimento_id' => $item['procedimento_id'],
                'dente'           => $item['dente'] ?? null,
                'quantidade'      => $item['quantidade'],
                'valor_unitario'  => $item['valor_unitario'],
                'valor_total'     => $total,
            ]);
        }

        $orcamento->load(['itens', 'convenio']);
        $orcamento->calcularTotais();

        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $arquivo) {
                $caminho = $arquivo->store("orcamentos/{$orcamento->id}", 'public');
                $orcamento->arquivos()->create([
                    'nome_original' => $arquivo->getClientOriginalName(),
                    'caminho'       => $caminho,
                    'tipo_mime'     => $arquivo->getMimeType(),
                    'tamanho'       => $arquivo->getSize(),
                ]);
            }
        }

        return redirect()->route('orcamentos.show', $orcamento->id)
            ->with('success', 'Orçamento atualizado com sucesso!');
    }

    public function atualizarStatus(Request $request, Orcamento $orcamento)
    {
        $request->validate([
            'status' => 'required|in:rascunho,aprovado,recusado,cancelado',
        ]);

        $orcamento->update(['status' => $request->status]);

        return redirect()->route('orcamentos.show', $orcamento->id)
            ->with('success', 'Status atualizado com sucesso!');
    }

    public function destroy(Orcamento $orcamento)
    {
        $orcamento->delete();
        return redirect()->route('orcamentos.index')
            ->with('success', 'Orçamento cancelado com sucesso!');
    }

    public function destroyArquivo(OrcamentoArquivo $arquivo)
    {
        Storage::disk('public')->delete($arquivo->caminho);
        $orcamento_id = $arquivo->orcamento_id;
        $arquivo->delete();

        return redirect()->route('orcamentos.show', $orcamento_id)
            ->with('success', 'Arquivo removido com sucesso!');
    }
}