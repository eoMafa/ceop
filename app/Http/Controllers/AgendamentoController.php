<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Paciente;
use App\Models\Procedimento;
use App\Models\User;
use Illuminate\Http\Request;

class AgendamentoController extends Controller
{
    public function index()
    {
        $dentistas = User::where('role', 'dentista')
            ->where('ativo', true)
            ->orderBy('name')
            ->get();

        return view('agendamentos.index', compact('dentistas'));
    }

    // Retorna eventos para o FullCalendar via AJAX
    public function eventos(Request $request)
    {
        $query = Agendamento::with(['paciente', 'dentista', 'procedimento'])
            ->whereBetween('data_hora_inicio', [
                $request->start,
                $request->end,
            ]);

        if ($request->dentista_id) {
            $query->where('dentista_id', $request->dentista_id);
        }

        $agendamentos = $query->get();

        return response()->json(
            $agendamentos->map(fn($a) => [
                'id'                => $a->id,
                'title'             => $a->paciente->nome,
                'start'             => $a->data_hora_inicio->toIso8601String(),
                'end'               => $a->data_hora_fim->toIso8601String(),
                'backgroundColor'   => $a->cor_status,
                'borderColor'       => $a->cor_status,
                'extendedProps'     => [
                    'paciente'      => $a->paciente->nome,
                    'dentista'      => $a->dentista->name,
                    'procedimento'  => $a->procedimento->nome,
                    'status'        => $a->status,
                    'observacoes'   => $a->observacoes,
                ],
            ])
        );
    }

    public function create(Request $request)
    {
        $pacientes    = Paciente::orderBy('nome')->get();
        $dentistas    = User::where('role', 'dentista')->where('ativo', true)->orderBy('name')->get();
        $procedimentos = Procedimento::orderBy('nome')->get();

        $data_hora = $request->input('data_hora');

        return view('agendamentos.create', compact('pacientes', 'dentistas', 'procedimentos', 'data_hora'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id'     => 'required|exists:pacientes,id',
            'dentista_id'     => 'required|exists:users,id',
            'procedimento_id' => 'required|exists:procedimentos,id',
            'data_hora_inicio'=> 'required|date',
            'status'          => 'required|in:agendado,confirmado,cancelado,concluido,falta',
            'observacoes'     => 'nullable|string',
        ]);

        // Calcula fim baseado na duração do procedimento
        $procedimento = Procedimento::findOrFail($validated['procedimento_id']);
        $validated['data_hora_fim'] = date('Y-m-d H:i:s', strtotime(
            $validated['data_hora_inicio'] . ' + ' . $procedimento->duracao_padrao_minutos . ' minutes'
        ));

        Agendamento::create($validated);

        return redirect()->route('agendamentos.index')
            ->with('success', 'Agendamento criado com sucesso!');
    }

    public function show(Agendamento $agendamento)
    {
        $agendamento->load(['paciente', 'dentista', 'procedimento']);
        return view('agendamentos.show', compact('agendamento'));
    }

    public function edit(Agendamento $agendamento)
    {
        $pacientes    = Paciente::orderBy('nome')->get();
        $dentistas    = User::where('role', 'dentista')->where('ativo', true)->orderBy('name')->get();
        $procedimentos = Procedimento::orderBy('nome')->get();

        return view('agendamentos.edit', compact('agendamento', 'pacientes', 'dentistas', 'procedimentos'));
    }

    public function update(Request $request, Agendamento $agendamento)
    {
        $validated = $request->validate([
            'paciente_id'     => 'required|exists:pacientes,id',
            'dentista_id'     => 'required|exists:users,id',
            'procedimento_id' => 'required|exists:procedimentos,id',
            'data_hora_inicio'=> 'required|date',
            'status'          => 'required|in:agendado,confirmado,cancelado,concluido,falta',
            'observacoes'     => 'nullable|string',
        ]);

        $procedimento = Procedimento::findOrFail($validated['procedimento_id']);
        $validated['data_hora_fim'] = date('Y-m-d H:i:s', strtotime(
            $validated['data_hora_inicio'] . ' + ' . $procedimento->duracao_padrao_minutos . ' minutes'
        ));

        $agendamento->update($validated);

        return redirect()->route('agendamentos.index')
            ->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function destroy(Agendamento $agendamento)
    {
        $agendamento->delete();

        return redirect()->route('agendamentos.index')
            ->with('success', 'Agendamento cancelado com sucesso!');
    }
}