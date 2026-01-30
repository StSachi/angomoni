<?php

namespace App\Http\Controllers;

use App\Models\Doenca;
use Illuminate\Http\Request;

class DoencaController extends Controller
{
    public function index()
    {
        $doencas = Doenca::orderBy('nome')->paginate(10);
        return view('doencas.index', compact('doencas'));
    }

    public function create()
    {
        return view('doencas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:doencas,nome'],
            'codigo' => ['nullable', 'string', 'max:50', 'unique:doencas,codigo'],
            'descricao' => ['nullable', 'string'],
            'ativa' => ['nullable', 'boolean'],
        ]);

        $data['ativa'] = (bool) ($data['ativa'] ?? false);

        Doenca::create($data);

        return redirect()
            ->route('doencas.index')
            ->with('success', 'Doença cadastrada com sucesso.');
    }

    public function edit(Doenca $doenca)
    {
        return view('doencas.edit', compact('doenca'));
    }

    public function update(Request $request, Doenca $doenca)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:doencas,nome,' . $doenca->id],
            'codigo' => ['nullable', 'string', 'max:50', 'unique:doencas,codigo,' . $doenca->id],
            'descricao' => ['nullable', 'string'],
            'ativa' => ['nullable', 'boolean'],
        ]);

        $data['ativa'] = (bool) ($data['ativa'] ?? false);

        $doenca->update($data);

        return redirect()
            ->route('doencas.index')
            ->with('success', 'Doença atualizada com sucesso.');
    }

    public function destroy(Doenca $doenca)
    {
        $doenca->delete();

        return redirect()
            ->route('doencas.index')
            ->with('success', 'Doença eliminada com sucesso.');
    }
}
