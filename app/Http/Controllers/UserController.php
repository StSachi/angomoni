<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Auditoria;
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
        // Se tiveres policy para create:
        // $this->authorize('create', User::class);

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

        Auditoria::create([
            'user_id' => $request->user()->id,
            'acao' => 'USER_CREATE',
            'entidade' => 'User',
            'entidade_id' => $user->id,
            'antes' => null,
            'depois' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'papel' => $user->papel,
                'ativo' => (bool) $user->ativo,
            ],
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

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

        Auditoria::create([
            'user_id' => $request->user()->id,
            'acao' => 'USER_DELETE',
            'entidade' => 'User',
            'entidade_id' => $user->id,
            'antes' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'papel' => $user->papel,
                'ativo' => (bool) $user->ativo,
            ],
            'depois' => null,
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilizador eliminado com sucesso.');
    }
}
