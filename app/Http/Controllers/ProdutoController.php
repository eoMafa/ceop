<?php

namespace App\Http\Controllers;

use App\Models\CategoriaEstoque;
use App\Models\Fornecedor;
use App\Models\MovimentacaoEstoque;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::with(['categoria', 'fornecedor'])
            ->orderBy('nome')
            ->paginate(15);

        $totalBaixo = Produto::whereColumn('estoque_atual', '<=', 'estoque_minimo')
            ->whereNull('deleted_at')
            ->count();

        return view('estoque.produtos.index', compact('produtos', 'totalBaixo'));
    }

    public function create()
    {
        $categorias  = CategoriaEstoque::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();
        return view('estoque.produtos.create', compact('categorias', 'fornecedores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'                 => 'required|string|max:255',
            'codigo'               => 'nullable|string|max:50|unique:produtos',
            'categoria_estoque_id' => 'nullable|exists:categoria_estoques,id',
            'fornecedor_id'        => 'nullable|exists:fornecedores,id',
            'descricao'            => 'nullable|string',
            'unidade'              => 'required|in:un,cx,ml,g,kg,l,pct,rolo',
            'estoque_atual'        => 'required|numeric|min:0',
            'estoque_minimo'       => 'required|numeric|min:0',
            'valor_custo'          => 'required|numeric|min:0',
        ]);

        Produto::create($validated);

        return redirect()->route('estoque.produtos.index')
            ->with('success', 'Produto cadastrado com sucesso!');
    }

    public function show(Produto $produto)
    {
        $produto->load(['categoria', 'fornecedor', 'movimentacoes.user']);
        return view('estoque.produtos.show', compact('produto'));
    }

    public function edit(int $id)
    {
        $produto      = Produto::withTrashed()->findOrFail($id);
        $categorias   = CategoriaEstoque::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();
        return view('estoque.produtos.edit', compact('produto', 'categorias', 'fornecedores'));
    }

    public function update(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'nome'                 => 'required|string|max:255',
            'codigo'               => 'nullable|string|max:50|unique:produtos,codigo,' . $produto->id,
            'categoria_estoque_id' => 'nullable|exists:categoria_estoques,id',
            'fornecedor_id'        => 'nullable|exists:fornecedores,id',
            'descricao'            => 'nullable|string',
            'unidade'              => 'required|in:un,cx,ml,g,kg,l,pct,rolo',
            'estoque_minimo'       => 'required|numeric|min:0',
            'valor_custo'          => 'required|numeric|min:0',
        ]);

        $produto->update($validated);

        return redirect()->route('estoque.produtos.show', $produto->id)
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();
        return redirect()->route('estoque.produtos.index')
            ->with('success', 'Produto inativado com sucesso!');
    }

    public function restore(int $id)
    {
        Produto::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('estoque.produtos.index')
            ->with('success', 'Produto restaurado com sucesso!');
    }

    public function movimentar(Request $request, Produto $produto)
    {
        $validated = $request->validate([
            'tipo'           => 'required|in:entrada,saida,ajuste',
            'quantidade'     => 'required|numeric|min:0.01',
            'valor_unitario' => 'nullable|numeric|min:0',
            'motivo'         => 'nullable|string|max:255',
            'documento'      => 'nullable|string|max:100',
        ]);

        $estoqueAnterior = $produto->estoque_atual;

        $estoqueNovo = match($validated['tipo']) {
            'entrada' => $estoqueAnterior + $validated['quantidade'],
            'saida'   => $estoqueAnterior - $validated['quantidade'],
            'ajuste'  => $validated['quantidade'],
        };

        if ($estoqueNovo < 0) {
            return back()->with('error', 'Estoque insuficiente para esta saída!');
        }

        MovimentacaoEstoque::create([
            'produto_id'        => $produto->id,
            'user_id'           => auth()->id(),
            'tipo'              => $validated['tipo'],
            'quantidade'        => $validated['quantidade'],
            'valor_unitario'    => $validated['valor_unitario'] ?? null,
            'estoque_anterior'  => $estoqueAnterior,
            'estoque_posterior' => $estoqueNovo,
            'motivo'            => $validated['motivo'] ?? null,
            'documento'         => $validated['documento'] ?? null,
        ]);

        $produto->update(['estoque_atual' => $estoqueNovo]);

        // Atualiza valor_custo se for entrada com valor informado
        if ($validated['tipo'] === 'entrada' && !empty($validated['valor_unitario'])) {
            $produto->update(['valor_custo' => $validated['valor_unitario']]);
        }

        return redirect()->route('estoque.produtos.show', $produto->id)
            ->with('success', 'Movimentação registrada com sucesso!');
    }

    public function search(Request $request)
    {
        $termo = $request->input('q');

        $produtos = Produto::withTrashed()
            ->with(['categoria', 'fornecedor'])
            ->where(function ($query) use ($termo) {
                $query->where('nome', 'ilike', "%{$termo}%")
                      ->orWhere('codigo', 'ilike', "%{$termo}%");
            })
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        $totalBaixo = 0;

        return view('estoque.produtos.index', compact('produtos', 'termo', 'totalBaixo'));
    }
}