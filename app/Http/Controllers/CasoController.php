<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use App\Models\Doenca;
use App\Models\Paciente;
use App\Models\UnidadeSaude;
use App\Services\ServicoAuditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CasoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // CRUD protegido pela Policy (viewAny/view/create/update/delete...)
        $this->authorizeResource(Caso::class, 'caso');
    }

    /**
     * LISTAGEM
     * - ADMIN: vê tudo (com filtros opcionais)
     * - REGISTADOR: vê apenas casos da sua unidade_registo_id
     * - TECNICO_UNIDADE: pode ver casos (depende da Policy; aqui não bloqueamos)
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $papel = $user->papel ?? null;

        $q = Caso::query()
            ->with(['doenca', 'paciente', 'unidadeRegisto', 'unidadeOrigem', 'utilizador', 'validador'])
            ->orderByDesc('id');

        // REGISTADOR fica limitado à sua unidade
        if ($papel === 'REGISTADOR') {
            if (! $user->unidade_saude_id) {
                return redirect()->route('dashboard')->withErrors([
                    'unidade' => 'A sua conta não está associada a nenhuma unidade de saúde. Contacte o administrador.',
                ]);
            }
            $q->where('unidade_registo_id', $user->unidade_saude_id);
        } else {
            // filtros opcionais para ADMIN/TECNICO_UNIDADE
            if ($request->filled('unidade_registo_id')) {
                $q->where('unidade_registo_id', $request->integer('unidade_registo_id'));
            }
            if ($request->filled('unidade_origem_id')) {
                $q->where('unidade_origem_id', $request->integer('unidade_origem_id'));
            }
        }

        // filtros comuns
        if ($request->filled('doenca_id')) {
            $q->where('doenca_id', $request->integer('doenca_id'));
        }
        if ($request->filled('paciente_id')) {
            $q->where('paciente_id', $request->integer('paciente_id'));
        }
        if ($request->filled('estado')) {
            $q->where('estado', $request->input('estado'));
        }

        $casos = $q->paginate(15)->withQueryString();

        $doencas   = Doenca::orderBy('nome')->get();
        $pacientes = Paciente::orderBy('nome')->get();

        // Para filtros no UI (ADMIN vê todas; outros veem apenas a sua unidade)
        $unidades = ($papel === 'ADMIN')
            ? UnidadeSaude::orderBy('nome')->get()
            : UnidadeSaude::where('id', $user->unidade_saude_id)->orderBy('nome')->get();

        return view('casos.index', compact('casos', 'doencas', 'pacientes', 'unidades', 'papel'));
    }

    /**
     * FORM CREATE
     * - ADMIN: pode escolher unidade_registo_id e unidade_origem_id
     * - REGISTADOR: unidade_registo_id fixa = user.unidade_saude_id
     */
    public function create()
    {
        $user  = Auth::user();
        $papel = $user->papel ?? null;

        if ($papel === 'REGISTADOR' && ! $user->unidade_saude_id) {
            return redirect()->route('casos.index')->withErrors([
                'unidade' => 'A sua conta não está associada a nenhuma unidade de saúde. Contacte o administrador.',
            ]);
        }

        $doencas   = Doenca::orderBy('nome')->get();
        $pacientes = Paciente::orderBy('nome')->get();

        $unidades = ($papel === 'ADMIN')
            ? UnidadeSaude::orderBy('nome')->get()
            : UnidadeSaude::where('id', $user->unidade_saude_id)->orderBy('nome')->get();

        return view('casos.create', compact('doencas', 'pacientes', 'unidades', 'papel'));
    }

    /**
     * STORE
     * Regras:
     * - user_id é sempre o utilizador autenticado
     * - REGISTADOR: unidade_registo_id e unidade_origem_id = unidade do user
     * - ADMIN: pode definir unidade_registo_id e unidade_origem_id
     */
    public function store(Request $request)
    {
        $user  = Auth::user();
        $papel = $user->papel ?? null;

        $rules = [
            'paciente_id' => ['required', 'integer', 'exists:pacientes,id'],
            'doenca_id'   => ['required', 'integer', 'exists:doencas,id'],

            'data_notificacao'     => ['required', 'date'],
            'data_inicio_sintomas' => ['nullable', 'date', 'before_or_equal:data_notificacao'],

            'classificacao_caso'   => ['nullable', 'string', 'max:100'],
            'tipo_deteccao'        => ['nullable', 'string', 'max:100'],
            'fonte_notificacao'    => ['nullable', 'string', 'max:100'],

            'estado'               => ['required', 'string', 'max:50'],
            'parecer_tecnico'      => ['nullable', 'string', 'max:2000'],
        ];

        if ($papel === 'ADMIN') {
            $rules['unidade_registo_id'] = ['required', 'integer', 'exists:unidades_saude,id'];
            $rules['unidade_origem_id']  = ['nullable', 'integer', 'exists:unidades_saude,id'];
        }

        $data = $request->validate($rules);

        $data['user_id'] = $user->id;

        if ($papel === 'REGISTADOR') {
            if (! $user->unidade_saude_id) {
                return back()->withErrors([
                    'unidade' => 'A sua conta não está associada a nenhuma unidade de saúde. Contacte o administrador.',
                ])->withInput();
            }

            $data['unidade_registo_id'] = $user->unidade_saude_id;
            $data['unidade_origem_id']  = $user->unidade_saude_id;
        } else {
            if (empty($data['unidade_origem_id'])) {
                $data['unidade_origem_id'] = $data['unidade_registo_id'];
            }
        }

        // Workflow: por padrão cria como SUBMETIDO já com timestamp (como você vinha fazendo)
        $data['submetido_em'] = $data['submetido_em'] ?? now();

        $caso = Caso::create($data);

        ServicoAuditoria::registar('CREATE_CASO', 'Caso #' . $caso->id . ' criado');

        return redirect()
            ->route('casos.show', $caso)
            ->with('success', 'Caso registado com sucesso.');
    }

    public function show(Caso $caso)
    {
        $caso->load(['doenca', 'paciente', 'unidadeRegisto', 'unidadeOrigem', 'utilizador', 'validador']);
        return view('casos.show', compact('caso'));
    }

    public function edit(Caso $caso)
    {
        $user  = Auth::user();
        $papel = $user->papel ?? null;

        $doencas   = Doenca::orderBy('nome')->get();
        $pacientes = Paciente::orderBy('nome')->get();

        $unidades = ($papel === 'ADMIN')
            ? UnidadeSaude::orderBy('nome')->get()
            : UnidadeSaude::where('id', $user->unidade_saude_id)->orderBy('nome')->get();

        return view('casos.edit', compact('caso', 'doencas', 'pacientes', 'unidades', 'papel'));
    }

    /**
     * UPDATE
     * - REGISTADOR não troca unidade_registo_id nem unidade_origem_id
     * - ADMIN pode trocar
     */
    public function update(Request $request, Caso $caso)
    {
        $user  = Auth::user();
        $papel = $user->papel ?? null;

        $rules = [
            'paciente_id' => ['required', 'integer', 'exists:pacientes,id'],
            'doenca_id'   => ['required', 'integer', 'exists:doencas,id'],

            'data_notificacao'     => ['required', 'date'],
            'data_inicio_sintomas' => ['nullable', 'date', 'before_or_equal:data_notificacao'],

            'classificacao_caso'   => ['nullable', 'string', 'max:100'],
            'tipo_deteccao'        => ['nullable', 'string', 'max:100'],
            'fonte_notificacao'    => ['nullable', 'string', 'max:100'],

            'estado'               => ['required', 'string', 'max:50'],
            'parecer_tecnico'      => ['nullable', 'string', 'max:2000'],
        ];

        if ($papel === 'ADMIN') {
            $rules['unidade_registo_id'] = ['required', 'integer', 'exists:unidades_saude,id'];
            $rules['unidade_origem_id']  = ['nullable', 'integer', 'exists:unidades_saude,id'];
        }

        $data = $request->validate($rules);

        if ($papel === 'REGISTADOR') {
            if (! $user->unidade_saude_id) {
                return back()->withErrors([
                    'unidade' => 'A sua conta não está associada a nenhuma unidade de saúde. Contacte o administrador.',
                ])->withInput();
            }

            $data['unidade_registo_id'] = $user->unidade_saude_id;
            $data['unidade_origem_id']  = $user->unidade_saude_id;
        } else {
            if (empty($data['unidade_origem_id'])) {
                $data['unidade_origem_id'] = $data['unidade_registo_id'];
            }
        }

        $caso->update($data);

        ServicoAuditoria::registar('UPDATE_CASO', 'Caso #' . $caso->id . ' atualizado');

        return redirect()
            ->route('casos.show', $caso)
            ->with('success', 'Caso atualizado com sucesso.');
    }

    public function destroy(Caso $caso)
    {
        $id = $caso->id;

        $caso->delete();

        ServicoAuditoria::registar('DELETE_CASO', 'Caso #' . $id . ' removido');

        return redirect()
            ->route('casos.index')
            ->with('success', 'Caso removido com sucesso.');
    }

    /**
     * WORKFLOW: Submeter caso (REGISTADOR e ADMIN) - rota /casos/{caso}/submit
     */
    public function submit(Caso $caso)
    {
        $this->authorize('submit', $caso);

        if ($caso->submetido_em) {
            return back()->withErrors([
                'workflow' => 'Este caso já foi submetido.',
            ]);
        }

        $caso->submetido_em = now();

        if (! $caso->estado) {
            $caso->estado = 'SUBMETIDO';
        }

        $caso->save();

        ServicoAuditoria::registar('SUBMIT_CASO', 'Caso #' . $caso->id . ' submetido');

        return redirect()
            ->route('casos.show', $caso)
            ->with('success', 'Caso submetido com sucesso.');
    }

    /**
     * WORKFLOW: Validar caso (TECNICO_UNIDADE e ADMIN) - rota /casos/{caso}/validate
     */
    public function validateCase(Request $request, Caso $caso)
    {
        $this->authorize('validate', $caso);

        if (! $caso->submetido_em) {
            return back()->withErrors([
                'workflow' => 'Não é possível validar um caso que ainda não foi submetido.',
            ]);
        }

        if ($caso->validado_em) {
            return back()->withErrors([
                'workflow' => 'Este caso já foi validado.',
            ]);
        }

        $data = $request->validate([
            'parecer_tecnico' => ['nullable', 'string', 'max:2000'],
            'estado'          => ['nullable', 'string', 'max:50'],
        ]);

        if (array_key_exists('parecer_tecnico', $data)) {
            $caso->parecer_tecnico = $data['parecer_tecnico'];
        }

        $caso->estado = $data['estado'] ?? ($caso->estado ?: 'VALIDADO');

        $caso->validado_por = Auth::id();
        $caso->validado_em  = now();

        $caso->save();

        ServicoAuditoria::registar('VALIDATE_CASO', 'Caso #' . $caso->id . ' validado');

        return redirect()
            ->route('casos.show', $caso)
            ->with('success', 'Caso validado com sucesso.');
    }

    /**
     * WORKFLOW: Rejeitar caso (TECNICO_UNIDADE e ADMIN) - rota /casos/{caso}/reject
     */
    public function rejectCase(Request $request, Caso $caso)
    {
        $this->authorize('reject', $caso);

        if (! $caso->submetido_em) {
            return back()->withErrors([
                'workflow' => 'Não é possível rejeitar um caso que ainda não foi submetido.',
            ]);
        }

        $data = $request->validate([
            'parecer_tecnico' => ['nullable', 'string', 'max:2000'],
        ]);

        if (array_key_exists('parecer_tecnico', $data)) {
            $caso->parecer_tecnico = $data['parecer_tecnico'];
        }

        $caso->estado = 'REJEITADO';
        $caso->validado_por = null;
        $caso->validado_em  = null;

        $caso->save();

        ServicoAuditoria::registar('REJECT_CASO', 'Caso #' . $caso->id . ' rejeitado');

        return redirect()
            ->route('casos.show', $caso)
            ->with('success', 'Caso rejeitado com sucesso.');
    }
}
