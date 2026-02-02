<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ServicoAuditoria;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::orderByDesc('id')->paginate(20);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Se tiveres policy para create:
        // $this->authorize('create', User::class);

        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'papel' => ['required', Rule::in(['ADMIN', 'PROFISSIONAL'])],
            'ativo' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $ativo = (bool) ($data['ativo'] ?? true);

        // Password temporária se não vier no form
        $tempPassword = $data['password'] ?? ('Ango@' . random_int(100000, 999999));

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'papel' => $data['papel'],
            'ativo' => $ativo,
            'password' => Hash::make($tempPassword),
        ]);

        ServicoAuditoria::registar(
            'CREATE',
            "Criou utilizador: {$user->name} ({$user->email})",
            $request
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilizador criado com sucesso.')
            ->with('temp_password', $tempPassword);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $nome  = $user->name;
        $email = $user->email;
        $papel = $user->papel;

        $user->delete();

        ServicoAuditoria::registar(
        'DELETE',
        "Eliminou utilizador: {$nome} ({$email}) papel={$papel}",
        $request
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilizador eliminado com sucesso.');
    }
}
