@extends('layouts.app')

@section('title', 'Categorias de Estoque')

@section('breadcrumb')
    <li class="breadcrumb-item active">Categorias</li>
@endsection

@section('content')
    <div class="row g-3">

        {{-- Formulário de nova categoria --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nova Categoria</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('estoque.categorias.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Nome</label>
                            <input type="text" name="nome"
                                class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome') }}">
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea name="descricao" rows="2"
                                class="form-control">{{ old('descricao') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Adicionar</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Lista de categorias --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Todas as Categorias</h3>
                    <span class="ms-auto text-secondary">{{ $categorias->total() }} registros</span>
                </div>
                <table class="table table-vcenter table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Produtos</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categorias as $categoria)
                            <tr class="{{ $categoria->trashed() ? 'table-danger' : '' }}">
                                <td>{{ $categoria->nome }}</td>
                                <td class="text-secondary">{{ $categoria->descricao ?? '—' }}</td>
                                <td>{{ $categoria->produtos_count }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown">Ações</button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <button class="dropdown-item"
                                                onclick="editarCategoria({{ $categoria->id }}, '{{ $categoria->nome }}', '{{ $categoria->descricao }}')">
                                                Editar
                                            </button>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('estoque.categorias.destroy', $categoria->id) }}"
                                                method="POST"
                                                data-confirm="Deseja remover a categoria {{ $categoria->nome }}?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">Remover</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-4">Nenhuma categoria encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($categorias->hasPages())
                    <div class="card-footer d-flex align-items-center">
                        {{ $categorias->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal de edição --}}
    <div class="modal modal-blur fade" id="modal-editar-categoria" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-editar-categoria" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label required">Nome</label>
                            <input type="text" name="nome" id="edit-nome" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea name="descricao" id="edit-descricao" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function editarCategoria(id, nome, descricao) {
    document.getElementById('edit-nome').value = nome;
    document.getElementById('edit-descricao').value = descricao || '';
    document.getElementById('form-editar-categoria').action = `/estoque/categorias/${id}`;
    const modal = bootstrap.Modal.getOrCreate(document.getElementById('modal-editar-categoria'));
    modal.show();
}
</script>
@endpush