<?php

namespace App\Http\Controllers;

use App\Http\Requests\PacienteRequest;
use App\Models\Paciente;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::query()
            ->orderBy('nome_completo')
            ->paginate(20);

        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.create');
    }

    public function store(PacienteRequest $request)
    {
        $data = $request->validated();

        if (($data['nacionalidade'] ?? null) === 'NACIONAL') {
            $data['pais_id'] = null;
        }

        $paciente = Paciente::create($data);

        return redirect()
            ->route('pacientes.show', $paciente)
            ->with('success', 'Paciente registado com sucesso.');
    }

    public function show(Paciente $paciente)
    {
        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(PacienteRequest $request, Paciente $paciente)
    {
        $data = $request->validated();

        if (($data['nacionalidade'] ?? null) === 'NACIONAL') {
            $data['pais_id'] = null;
        }

        $paciente->update($data);

        return redirect()
            ->route('pacientes.show', $paciente)
            ->with('success', 'Paciente atualizado com sucesso.');
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();

        return redirect()
            ->route('pacientes.index')
            ->with('success', 'Paciente removido com sucesso.');
    }
}
