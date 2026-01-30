<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use App\Models\Doenca;
use App\Models\UnidadeSaude;
use Illuminate\Http\Request;

class CasoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Caso::class);

        $casos = Caso::with(['doenca', 'unidadeSaude', 'user'])
            ->orderByDesc('data_notificacao')
            ->orderByDesc('id')
            ->paginate(10);

        return view('casos.index', compact('casos'));
    }

    public function create()
    {
        $this->authorize('create', Caso::class);

        $doencas = Doenca::where('ativa', true)->orderBy('nome')->get();
        $unidades = UnidadeSaude::orderBy('provincia')->orderBy('municipio')->orderBy('nome')->get();

        return view('casos.create', compact('doencas', 'unidades'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Caso::class);

        $data = $request->validate([
            'doenca_id' => ['required', 'exists:doencas,id'],
            'unidade_saude_id' => ['required', 'exists:unidades_saude,id'],
            'data_notificacao' => ['required', 'date'],
            'idade' => ['nullable', 'integer', 'min:0', 'max:120'],
            'sexo' => ['required', 'in:M,F,N'],
            'estado' => ['required', 'in:SUSPEITO,CONFIRMADO,DESCARTADO,OBITO'],
            'paciente_iniciais' => ['nullable', 'string', 'max:10'],
            'telefone_contacto' => ['nullable', 'string', 'max:30'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $data['user_id'] = $request->user()->id;

        $caso = Caso::create($data);

        return redirect()
            ->route('casos.show', $caso)
            ->with('success', 'Caso registado com sucesso.');
    }

    public function show(Caso $caso)
    {
        $this->authorize('view', $caso);

        $caso->load(['doenca', 'unidadeSaude', 'user']);
        return view('casos.show', compact('caso'));
    }

    public function edit(Caso $caso)
    {
        $this->authorize('update', $caso);

        $doencas = Doenca::where('ativa', true)->orderBy('nome')->get();
        $unidades = UnidadeSaude::orderBy('provincia')->orderBy('municipio')->orderBy('nome')->get();

        return view('casos.edit', compact('caso', 'doencas', 'unidades'));
    }

    public function update(Request $request, Caso $caso)
    {
        $this->authorize('update', $caso);

        $data = $request->validate([
            'doenca_id' => ['required', 'exists:doencas,id'],
            'unidade_saude_id' => ['required', 'exists:unidades_saude,id'],
            'data_notificacao' => ['required', 'date'],
            'idade' => ['nullable', 'integer', 'min:0', 'max:120'],
            'sexo' => ['required', 'in:M,F,N'],
            'estado' => ['required', 'in:SUSPEITO,CONFIRMADO,DESCARTADO,OBITO'],
            'paciente_iniciais' => ['nullable', 'string', 'max:10'],
            'telefone_contacto' => ['nullable', 'string', 'max:30'],
            'observacoes' => ['nullable', 'string'],
        ]);

        $caso->update($data);

        return redirect()
            ->route('casos.show', $caso)
            ->with('success', 'Caso atualizado com sucesso.');
    }

    public function destroy(Caso $caso)
    {
        $this->authorize('delete', $caso);

        $caso->delete();

        return redirect()
            ->route('casos.index')
            ->with('success', 'Caso eliminado com sucesso.');
    }
}
