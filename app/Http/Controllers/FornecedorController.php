<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index()
    {
        $fornecedores = Fornecedor::orderBy('nome')->paginate(15);
        return view('estoque.fornecedores.index', compact('fornecedores'));
    }

    public function create()
    {
        return view('estoque.fornecedores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'        => 'required|string|max:255',
            'cnpj'        => 'nullable|string|max:18|unique:fornecedores',
            'telefone'    => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
            'contato'     => 'nullable|string|max:255',
            'cep'         => 'nullable|string|max:9',
            'logradouro'  => 'nullable|string|max:255',
            'numero'      => 'nullable|string|max:10',
            'complemento' => 'nullable|string|max:100',
            'bairro'      => 'nullable|string|max:100',
            'cidade'      => 'nullable|string|max:100',
            'estado'      => 'nullable|string|max:2',
            'observacoes' => 'nullable|string',
        ]);

        Fornecedor::create($validated);

        return redirect()->route('estoque.fornecedores.index')
            ->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function show(Fornecedor $fornecedor)
    {
        $fornecedor->load('produtos');
        return view('estoque.fornecedores.show', compact('fornecedor'));
    }

    public function edit(int $id)
    {
        $fornecedor = Fornecedor::withTrashed()->findOrFail($id);
        return view('estoque.fornecedores.edit', compact('fornecedor'));
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        $validated = $request->validate([
            'nome'        => 'required|string|max:255',
            'cnpj'        => 'nullable|string|max:18|unique:fornecedores,cnpj,' . $fornecedor->id,
            'telefone'    => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
            'contato'     => 'nullable|string|max:255',
            'cep'         => 'nullable|string|max:9',
            'logradouro'  => 'nullable|string|max:255',
            'numero'      => 'nullable|string|max:10',
            'complemento' => 'nullable|string|max:100',
            'bairro'      => 'nullable|string|max:100',
            'cidade'      => 'nullable|string|max:100',
            'estado'      => 'nullable|string|max:2',
            'observacoes' => 'nullable|string',
        ]);

        $fornecedor->update($validated);

        return redirect()->route('estoque.fornecedores.index')
            ->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();
        return redirect()->route('estoque.fornecedores.index')
            ->with('success', 'Fornecedor inativado com sucesso!');
    }

    public function restore(int $id)
    {
        Fornecedor::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('estoque.fornecedores.index')
            ->with('success', 'Fornecedor restaurado com sucesso!');
    }

    public function search(Request $request)
    {
        $termo = $request->input('q');

        $fornecedores = Fornecedor::withTrashed()
            ->where(function ($query) use ($termo) {
                $query->where('nome', 'ilike', "%{$termo}%")
                      ->orWhere('cnpj', 'ilike', "%{$termo}%");
            })
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('estoque.fornecedores.index', compact('fornecedores', 'termo'));
    }
}