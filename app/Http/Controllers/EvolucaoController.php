<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Evolucao;
use App\Models\EvolucaoArquivo;
use App\Models\EvolucaoMaterial;
use App\Models\MovimentacaoEstoque;
use App\Models\Orcamento;
use App\Models\Paciente;
use App\Models\Procedimento;
use App\Models\Produto;
use App\Models\Prontuario;
use App\Models\User;
use App\Rules\ArquivoSeguro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvolucaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Evolucao::with([
            'prontuario.paciente',
            'dentista',
            'procedimento',
            'agendamento',
            'materiais',
            'arquivos',
        ])->orderByDesc('created_at');

        if ($request->filled('dentista_id')) {
            $query->where('dentista_id', $request->dentista_id);
        }

        if ($request->filled('paciente_id')) {
            $query->whereHas('prontuario', fn($q) => $q->where('paciente_id', $request->paciente_id));
        }

        if ($request->filled('procedimento_id')) {
            $query->where('procedimento_id', $request->procedimento_id);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        $evolucoes     = $query->paginate(15)->withQueryString();
        $dentistas     = User::where('role', 'dentista')->where('ativo', true)->orderBy('name')->get();
        $pacientes     = Paciente::orderBy('nome')->get();
        $procedimentos = Procedimento::orderBy('nome')->get();

        return view('evolucoes.index', compact(
            'evolucoes', 'dentistas', 'pacientes', 'procedimentos'
        ));
    }

    public function show(Evolucao $evolucao)
    {
        $evolucao->load([
            'prontuario.paciente',
            'dentista',
            'procedimento',
            'agendamento',
            'orcamento.itens.procedimento',
            'arquivos',
            'materiais.produto',
        ]);

        // Histórico de evoluções do mesmo paciente
        $historicoEvolucoes = Evolucao::with(['dentista', 'procedimento'])
            ->where('prontuario_id', $evolucao->prontuario_id)
            ->where('id', '!=', $evolucao->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('evolucoes.show', compact('evolucao', 'historicoEvolucoes'));
    }

    public function create(Request $request)
    {
        $agendamento = $request->agendamento_id
            ? Agendamento::with(['paciente', 'procedimento', 'dentista', 'orcamento.itens.procedimento'])->find($request->agendamento_id)
            : null;
        $dentistas     = User::where('role', 'dentista')->where('ativo', true)->orderBy('name')->get();
        $pacientes     = Paciente::orderBy('nome')->get();
        $procedimentos = Procedimento::orderBy('nome')->get();

        // Pré-seleciona paciente se vier do agendamento
        $paciente_id     = $agendamento?->paciente_id ?? $request->paciente_id;
        $orcamentos      = $paciente_id
            ? Orcamento::where('paciente_id', $paciente_id)->where('status', 'aprovado')->orderByDesc('created_at')->get()
            : collect();

        return view('evolucoes.create', compact(
            'agendamento', 'dentistas', 'pacientes', 'procedimentos', 'orcamentos', 'paciente_id'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id'        => 'required|exists:pacientes,id',
            'dentista_id'        => 'required|exists:users,id',
            'agendamento_id'     => 'nullable|exists:agendamentos,id',
            'procedimento_id'    => 'nullable|exists:procedimentos,id',
            'orcamento_id'       => 'nullable|exists:orcamentos,id',
            'dente'              => 'nullable|string|max:10',
            'face'               => 'nullable|string|max:50',
            'descricao'          => 'required|string',
            'arquivos.*'         => ['nullable', 'file', 'max:102400', new ArquivoSeguro],
            'materiais'              => 'nullable|array',
            'materiais.*.produto_id' => 'required|exists:produtos,id',
            'materiais.*.quantidade' => 'required|numeric|min:0.01',
        ]);

        // Busca ou cria prontuário
        $prontuario = Prontuario::firstOrCreate(['paciente_id' => $validated['paciente_id']]);

        $evolucao = $prontuario->evolucoes()->create([
            'dentista_id'     => $validated['dentista_id'],
            'agendamento_id'  => $validated['agendamento_id'] ?? null,
            'procedimento_id' => $validated['procedimento_id'] ?? null,
            'orcamento_id'    => $validated['orcamento_id'] ?? null,
            'dente'           => $validated['dente'] ?? null,
            'face'            => $validated['face'] ?? null,
            'descricao'       => $validated['descricao'],
        ]);

        // Marca agendamento como concluído
        if (!empty($validated['agendamento_id'])) {
            Agendamento::find($validated['agendamento_id'])?->update(['status' => 'concluido']);
        }

        // Processa materiais e baixa estoque
        if (!empty($validated['materiais'])) {
            foreach ($validated['materiais'] as $item) {
                $produto = Produto::findOrFail($item['produto_id']);

                if ($produto->estoque_atual < $item['quantidade']) {
                    $evolucao->delete();
                    return back()->withInput()
                        ->with('error', "Estoque insuficiente para: {$produto->nome}");
                }

                $evolucao->materiais()->create([
                    'produto_id'     => $produto->id,
                    'quantidade'     => $item['quantidade'],
                    'valor_unitario' => $produto->valor_custo,
                ]);

                $estoqueAnterior = $produto->estoque_atual;
                $estoqueNovo     = $estoqueAnterior - $item['quantidade'];

                MovimentacaoEstoque::create([
                    'produto_id'        => $produto->id,
                    'user_id'           => auth()->id(),
                    'tipo'              => 'saida',
                    'quantidade'        => $item['quantidade'],
                    'valor_unitario'    => $produto->valor_custo,
                    'estoque_anterior'  => $estoqueAnterior,
                    'estoque_posterior' => $estoqueNovo,
                    'motivo'            => "Uso em evolução #{$evolucao->id}",
                ]);

                $produto->update(['estoque_atual' => $estoqueNovo]);
            }
        }

        // Upload de arquivos
        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $arquivo) {
                $mime    = $arquivo->getMimeType();
                $tipo    = str_starts_with($mime, 'image/') ? 'imagem'
                    : (str_starts_with($mime, 'video/') ? 'video' : 'documento');
                $caminho = $arquivo->store("prontuarios/{$prontuario->id}", 'public');

                $evolucao->arquivos()->create([
                    'nome_original' => $arquivo->getClientOriginalName(),
                    'caminho'       => $caminho,
                    'tipo_mime'     => $mime,
                    'tamanho'       => $arquivo->getSize(),
                    'tipo'          => $tipo,
                ]);
            }
        }

        return redirect()->route('evolucoes.show', $evolucao->id)
            ->with('success', 'Consulta registrada com sucesso!');
    }

    public function edit(Evolucao $evolucao)
    {
        $evolucao->load(['prontuario.paciente', 'materiais.produto', 'arquivos']);

        $dentistas     = User::where('role', 'dentista')->where('ativo', true)->orderBy('name')->get();
        $procedimentos = Procedimento::orderBy('nome')->get();
        $orcamentos    = Orcamento::where('paciente_id', $evolucao->prontuario->paciente_id)
            ->where('status', 'aprovado')
            ->orderByDesc('created_at')
            ->get();

        return view('evolucoes.edit', compact('evolucao', 'dentistas', 'procedimentos', 'orcamentos'));
    }

    public function update(Request $request, Evolucao $evolucao)
    {
        $validated = $request->validate([
            'dentista_id'     => 'required|exists:users,id',
            'procedimento_id' => 'nullable|exists:procedimentos,id',
            'orcamento_id'    => 'nullable|exists:orcamentos,id',
            'dente'           => 'nullable|string|max:10',
            'face'            => 'nullable|string|max:50',
            'descricao'       => 'required|string',
            'arquivos.*'      => ['nullable', 'file', 'max:102400', new ArquivoSeguro],
        ]);

        $evolucao->update($validated);

        // Upload de novos arquivos
        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $arquivo) {
                $mime    = $arquivo->getMimeType();
                $tipo    = str_starts_with($mime, 'image/') ? 'imagem'
                    : (str_starts_with($mime, 'video/') ? 'video' : 'documento');
                $caminho = $arquivo->store("prontuarios/{$evolucao->prontuario_id}", 'public');

                $evolucao->arquivos()->create([
                    'nome_original' => $arquivo->getClientOriginalName(),
                    'caminho'       => $caminho,
                    'tipo_mime'     => $mime,
                    'tamanho'       => $arquivo->getSize(),
                    'tipo'          => $tipo,
                ]);
            }
        }

        return redirect()->route('evolucoes.show', $evolucao->id)
            ->with('success', 'Consulta atualizada com sucesso!');
    }

    public function destroyArquivo(EvolucaoArquivo $arquivo)
    {
        Storage::disk('public')->delete($arquivo->caminho);
        $evolucao_id = $arquivo->evolucao_id;
        $arquivo->delete();

        return redirect()->route('evolucoes.show', $evolucao_id)
            ->with('success', 'Arquivo removido com sucesso!');
    }

    // Carrega orçamentos por paciente via AJAX
    public function orcamentosPorPaciente(Request $request)
    {
        $orcamentos = Orcamento::where('paciente_id', $request->paciente_id)
            ->where('status', 'aprovado')
            ->orderByDesc('created_at')
            ->get(['id', 'total_liquido', 'created_at']);

        return response()->json($orcamentos);
    }
}