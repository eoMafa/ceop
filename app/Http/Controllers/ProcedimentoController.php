<?php

namespace App\Http\Controllers;

use App\Models\Procedimento;
use Illuminate\Http\Request;

class ProcedimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $procedimentos = Procedimento::orderBy('nome')->paginate(15);

        return view('procedimentos.index', compact('procedimentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('procedimentos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'                   => 'required|string|max:255',
            'descricao'              => 'nullable|string',
            'duracao_padrao_minutos' => 'required|integer|min:5|max:480',
            'valor_padrao'           => 'required|numeric|min:0',
        ]);

        Procedimento::create($validated);

        return redirect()->route('procedimentos.index')
            ->with('success', 'Procedimento cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $procedimento = Procedimento::withTrashed()->findOrFail($id);

        return view('procedimentos.show', compact('procedimento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $procedimento = Procedimento::withTrashed()->findOrFail($id);

        return view('procedimentos.edit', compact('procedimento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Procedimento $procedimento)
    {
         $validated = $request->validate([
            'nome'                   => 'required|string|max:255',
            'descricao'              => 'nullable|string',
            'duracao_padrao_minutos' => 'required|integer|min:5|max:480',
            'valor_padrao'           => 'required|numeric|min:0',
        ]);

        $procedimento->update($validated);

        return redirect()->route('procedimentos.index')
            ->with('success', 'Procedimento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Procedimento $procedimento)
    {
        $procedimento->delete();

        return redirect()->route('procedimentos.index')
            ->with('success', 'Procedimento inativado com sucesso!');
    }

    public function restore(int $id)
    {
        $procedimento = Procedimento::withTrashed()->findOrFail($id);
        $procedimento->restore();

        return redirect()->route('procedimentos.edit', $procedimento->id)
            ->with('success', 'Procedimento restaurado com sucesso!');
    }

    public function search(Request $request)
    {
        $termo = $request->input('q');

        $procedimentos = Procedimento::withTrashed()
            ->where('nome', 'ilike', "%{$termo}%")
            ->orWhere('descricao', 'ilike', "%{$termo}%")
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('procedimentos.index', compact('procedimentos', 'termo'));
    }
}
