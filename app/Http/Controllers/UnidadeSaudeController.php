<?php

namespace App\Http\Controllers;

use App\Models\UnidadeSaude;
use Illuminate\Http\Request;

class UnidadeSaudeController extends Controller
{
    public function index()
    {
        $unidades = UnidadeSaude::orderBy('provincia')
            ->orderBy('municipio')
            ->orderBy('nome')
            ->paginate(10);

        return view('unidades_saude.index', compact('unidades'));
    }

    public function create()
    {
        return view('unidades_saude.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'provincia' => ['required', 'string', 'max:100'],
            'municipio' => ['required', 'string', 'max:100'],
            'comuna' => ['nullable', 'string', 'max:100'],
            'bairro' => ['nullable', 'string', 'max:100'],
            'telefone' => ['nullable', 'string', 'max:30'],
        ]);

        UnidadeSaude::create($data);

        return redirect()
            ->route('unidades-saude.index')
            ->with('success', 'Unidade de saúde cadastrada com sucesso.');
    }

    public function edit(UnidadeSaude $unidades_saude)
    {
        // Atenção: o route model binding aqui vem como {unidades_saude}
        $unidade = $unidades_saude;

        return view('unidades_saude.edit', compact('unidade'));
    }

    public function update(Request $request, UnidadeSaude $unidades_saude)
    {
        $unidade = $unidades_saude;

        $data = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'provincia' => ['required', 'string', 'max:100'],
            'municipio' => ['required', 'string', 'max:100'],
            'comuna' => ['nullable', 'string', 'max:100'],
            'bairro' => ['nullable', 'string', 'max:100'],
            'telefone' => ['nullable', 'string', 'max:30'],
        ]);

        $unidade->update($data);

        return redirect()
            ->route('unidades-saude.index')
            ->with('success', 'Unidade de saúde atualizada com sucesso.');
    }

    public function destroy(UnidadeSaude $unidades_saude)
    {
        $unidades_saude->delete();

        return redirect()
            ->route('unidades-saude.index')
            ->with('success', 'Unidade de saúde eliminada com sucesso.');
    }
}
