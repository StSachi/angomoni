<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
