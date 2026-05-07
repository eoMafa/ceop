<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::orderBy('name')->paginate(15);

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users|max:255',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => 'required|in:admin,dentista,recepcionista',
            'cro'      => 'nullable|string|max:20',
            'telefone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        $permissions = \App\Models\Permission::orderBy('modulo')->orderBy('acao')->get();
        return view('usuarios.edit', compact('usuario', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $usuario->id,
            'role'         => 'required|in:admin,dentista,recepcionista',
            'cro'          => 'nullable|string|max:20',
            'telefone'     => 'nullable|string|max:20',
            'permissions'  => 'nullable|array',
            'permissions.*'=> 'exists:permissions,id',
        ]);

        $usuario->update([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'cro'      => $validated['cro'] ?? null,
            'telefone' => $validated['telefone'] ?? null,
        ]);

        // Atualiza permissões (admin não precisa)
        if (!$usuario->isAdmin()) {
            $usuario->permissions()->sync($validated['permissions'] ?? []);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function updatePassword(Request $request, User $usuario)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $usuario->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('usuarios.edit', $usuario->id)
            ->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Você não pode inativar seu próprio usuário!');
        }

        $usuario->update(['ativo' => false]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário inativado com sucesso!');
    }

    public function restore(int $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->update(['ativo' => true]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuário restaurado com sucesso!');
    }

    public function search(Request $request)
    {
        $termo = $request->input('q');

        $usuarios = User::where(function ($query) use ($termo) {
                $query->where('name', 'ilike', "%{$termo}%")
                      ->orWhere('email', 'ilike', "%{$termo}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('usuarios.index', compact('usuarios', 'termo'));
    }
}
