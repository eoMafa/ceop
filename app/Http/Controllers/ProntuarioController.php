<?php

namespace App\Http\Controllers;

use App\Models\Evolucao;
use App\Models\EvolucaoArquivo;
use App\Models\Paciente;
use App\Models\Prontuario;
use App\Models\User;
use App\Rules\ArquivoSeguro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProntuarioController extends Controller
{
    // Exibe o prontuário do paciente, criando se não existir
    public function show(Paciente $paciente)
    {
        $prontuario = $paciente->prontuario ?? Prontuario::create([
            'paciente_id' => $paciente->id,
        ]);

        $prontuario->load(['anamnese', 'evolucoes.dentista', 'evolucoes.arquivos', 'evolucoes.agendamento', 'evolucoes.materiais.produto']);

        $dentistas = User::where('role', 'dentista')->where('ativo', true)->orderBy('name')->get();

        return view('prontuarios.show', compact('paciente', 'prontuario', 'dentistas'));
    }

    // Salva ou atualiza a anamnese
    public function salvarAnamnese(Request $request, Prontuario $prontuario)
    {
        $validated = $request->validate([
            'alergia'               => 'boolean',
            'alergia_descricao'     => 'nullable|string',
            'medicamentos_uso'      => 'boolean',
            'medicamentos_descricao'=> 'nullable|string',
            'pressao_alta'          => 'boolean',
            'diabetes'              => 'boolean',
            'cardiopatia'           => 'boolean',
            'gestante'              => 'boolean',
            'fumante'               => 'boolean',
            'alcool'                => 'boolean',
            'doenca_renal'          => 'boolean',
            'doenca_hepatica'       => 'boolean',
            'problemas_coagulacao'  => 'boolean',
            'outras_doencas'        => 'nullable|string',
            'observacoes'           => 'nullable|string',
        ]);

        $validated['prontuario_id'] = $prontuario->id;

        $prontuario->anamnese
            ? $prontuario->anamnese->update($validated)
            : $prontuario->anamnese()->create($validated);

        return redirect()->route('prontuarios.show', $prontuario->paciente_id)
            ->with('success', 'Anamnese salva com sucesso!');
    }

    // Registra uma nova evolução
    public function storeEvolucao(Request $request, Prontuario $prontuario)
    {
        $validated = $request->validate([
            'dentista_id'          => 'required|exists:users,id',
            'agendamento_id'       => 'nullable|exists:agendamentos,id',
            'dente'                => 'nullable|string|max:10',
            'face'                 => 'nullable|string|max:50',
            'descricao'            => 'required|string',
            'arquivos.*'           => ['nullable', 'file', 'max:102400', new ArquivoSeguro],
            'materiais'            => 'nullable|array',
            'materiais.*.produto_id'  => 'required|exists:produtos,id',
            'materiais.*.quantidade'  => 'required|numeric|min:0.01',
        ]);

        $evolucao = $prontuario->evolucoes()->create([
            'dentista_id'    => $validated['dentista_id'],
            'agendamento_id' => $validated['agendamento_id'] ?? null,
            'dente'          => $validated['dente'] ?? null,
            'face'           => $validated['face'] ?? null,
            'descricao'      => $validated['descricao'],
        ]);

        // Processa materiais e baixa estoque automaticamente
        if (!empty($validated['materiais'])) {
            foreach ($validated['materiais'] as $item) {
                $produto = \App\Models\Produto::findOrFail($item['produto_id']);

                // Verifica estoque suficiente
                if ($produto->estoque_atual < $item['quantidade']) {
                    // Deleta evolução criada e retorna erro
                    $evolucao->delete();
                    return back()
                        ->withInput()
                        ->with('error', "Estoque insuficiente para o produto: {$produto->nome}");
                }

                // Registra material usado
                $evolucao->materiais()->create([
                    'produto_id'     => $produto->id,
                    'quantidade'     => $item['quantidade'],
                    'valor_unitario' => $produto->valor_custo,
                ]);

                // Baixa estoque automaticamente
                $estoqueAnterior = $produto->estoque_atual;
                $estoqueNovo = $estoqueAnterior - $item['quantidade'];

                \App\Models\MovimentacaoEstoque::create([
                    'produto_id'        => $produto->id,
                    'user_id'           => auth()->id(),
                    'tipo'              => 'saida',
                    'quantidade'        => $item['quantidade'],
                    'valor_unitario'    => $produto->valor_custo,
                    'estoque_anterior'  => $estoqueAnterior,
                    'estoque_posterior' => $estoqueNovo,
                    'motivo'            => "Uso em evolução do prontuário #{$prontuario->id}",
                ]);

                $produto->update(['estoque_atual' => $estoqueNovo]);
            }
        }

        // Upload dos arquivos
        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $arquivo) {
                $mime = $arquivo->getMimeType();
                $tipo = str_starts_with($mime, 'image/') ? 'imagem'
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

        return redirect()->route('prontuarios.show', $prontuario->paciente_id)
            ->with('success', 'Evolução registrada com sucesso!');
    }

    // Deleta um arquivo
    public function destroyArquivo(EvolucaoArquivo $arquivo)
    {
        Storage::disk('public')->delete($arquivo->caminho);
        $paciente_id = $arquivo->evolucao->prontuario->paciente_id;
        $arquivo->delete();

        return redirect()->route('prontuarios.show', $paciente_id)
            ->with('success', 'Arquivo removido com sucesso!');
    }

    // Deleta uma evolução
    public function destroyEvolucao(Evolucao $evolucao)
    {
        $paciente_id = $evolucao->prontuario->paciente_id;

        // Remove arquivos do storage
        foreach ($evolucao->arquivos as $arquivo) {
            Storage::disk('public')->delete($arquivo->caminho);
        }

        $evolucao->delete();

        return redirect()->route('prontuarios.show', $paciente_id)
            ->with('success', 'Evolução removida com sucesso!');
    }
}