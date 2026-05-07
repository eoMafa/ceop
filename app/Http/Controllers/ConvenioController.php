<?php

namespace App\Http\Controllers;

use App\Models\Convenio;
use Illuminate\Http\Request;

class ConvenioController extends Controller
{
    public function index()
    {
        $convenios = Convenio::orderBy('nome')->paginate(15);
        return view('convenios.index', compact('convenios'));
    }

    public function create()
    {
        return view('convenios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'                 => 'required|string|max:255',
            'desconto_percentual'  => 'required|numeric|min:0|max:100',
        ]);

        Convenio::create($validated);

        return redirect()->route('convenios.index')
            ->with('success', 'Convênio cadastrado com sucesso!');
    }

    public function edit(int $id)
    {
        $convenio = Convenio::withTrashed()->findOrFail($id);
        return view('convenios.edit', compact('convenio'));
    }

    public function update(Request $request, Convenio $convenio)
    {
        $validated = $request->validate([
            'nome'                => 'required|string|max:255',
            'desconto_percentual' => 'required|numeric|min:0|max:100',
        ]);

        $convenio->update($validated);

        return redirect()->route('convenios.index')
            ->with('success', 'Convênio atualizado com sucesso!');
    }

    public function destroy(Convenio $convenio)
    {
        $convenio->delete();
        return redirect()->route('convenios.index')
            ->with('success', 'Convênio inativado com sucesso!');
    }

    public function restore(int $id)
    {
        Convenio::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('convenios.index')
            ->with('success', 'Convênio restaurado com sucesso!');
    }

    public function search(Request $request)
    {
        $termo = $request->input('q');
        $convenios = Convenio::withTrashed()
            ->where('nome', 'ilike', "%{$termo}%")
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('convenios.index', compact('convenios', 'termo'));
    }
}