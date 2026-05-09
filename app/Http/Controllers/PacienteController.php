<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Rules\CpfCnpjValido;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pacientes = Paciente::orderBy('nome')->paginate(15);

        return view('pacientes.index', compact('pacientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pacientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'            => 'required|string|max:255',
            'cpf' => ['nullable', 'string', 'max:14', 'unique:pacientes', new CpfCnpjValido],
            'rg'              => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'sexo'            => 'nullable|in:M,F,outro',
            'telefone'        => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'cep'             => 'nullable|string|max:9',
            'logradouro'      => 'nullable|string|max:255',
            'numero'          => 'nullable|string|max:10',
            'complemento'     => 'nullable|string|max:100',
            'bairro'          => 'nullable|string|max:100',
            'cidade'          => 'nullable|string|max:100',
            'estado'          => 'nullable|string|max:2',
            'observacoes'     => 'nullable|string',
        ]);
    
        $paciente = Paciente::create($validated);
        ActivityLogService::criou('pacientes', "Cadastrou o paciente {$paciente->nome}", $paciente);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente)
    {
        return view('pacientes.show', compact('paciente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'nome'            => 'required|string|max:255',
            'cpf' => ['nullable', 'string', 'max:14', 'unique:pacientes,cpf,' . $paciente->id, new CpfCnpjValido],
            'rg'              => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'sexo'            => 'nullable|in:M,F,outro',
            'telefone'        => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'cep'             => 'nullable|string|max:9',
            'logradouro'      => 'nullable|string|max:255',
            'numero'          => 'nullable|string|max:10',
            'complemento'     => 'nullable|string|max:100',
            'bairro'          => 'nullable|string|max:100',
            'cidade'          => 'nullable|string|max:100',
            'estado'          => 'nullable|string|max:2',
            'observacoes'     => 'nullable|string',
        ]);

        $dadosAnteriores = $paciente->toArray();
        $paciente->update($validated);
        ActivityLogService::editou('pacientes', "Editou o paciente {$paciente->nome}", $paciente, $dadosAnteriores);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete(); // SoftDelete automático
        ActivityLogService::deletou('pacientes', "Inativou o paciente {$paciente->nome}", $paciente);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente removido com sucesso!');
    }

    public function search(Request $request)
    {
        $termo = $request->input('q');

        $pacientes = Paciente::withTrashed()
            ->where(function ($query) use ($termo) {
                $query->where('nome', 'ilike', "%{$termo}%")
                    ->orWhere('cpf', 'ilike', "%{$termo}%")
                    ->orWhere('telefone', 'ilike', "%{$termo}%")
                    ->orWhere('email', 'ilike', "%{$termo}%");
            })
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('pacientes.index', compact('pacientes', 'termo'));
    }

    public function restore(int $id)
    {
        $paciente = Paciente::withTrashed()->findOrFail($id);
        $paciente->restore();

        return redirect()->route('pacientes.edit', $paciente->id)
            ->with('success', 'Paciente restaurado com sucesso!');
    }
}
