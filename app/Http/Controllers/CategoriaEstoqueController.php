<?php

namespace App\Http\Controllers;

use App\Models\CategoriaEstoque;
use Illuminate\Http\Request;

class CategoriaEstoqueController extends Controller
{
    public function index()
    {
        $categorias = CategoriaEstoque::withCount('produtos')->orderBy('nome')->paginate(15);
        return view('estoque.categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'      => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        CategoriaEstoque::create($request->only('nome', 'descricao'));

        return redirect()->route('estoque.categorias.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function update(Request $request, CategoriaEstoque $categoriaEstoque)
    {
        $request->validate([
            'nome'      => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $categoriaEstoque->update($request->only('nome', 'descricao'));

        return redirect()->route('estoque.categorias.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(CategoriaEstoque $categoriaEstoque)
    {
        $categoriaEstoque->delete();
        return redirect()->route('estoque.categorias.index')
            ->with('success', 'Categoria removida com sucesso!');
    }
}