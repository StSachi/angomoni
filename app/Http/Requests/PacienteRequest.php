<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome_completo' => ['required', 'string', 'max:200'],
            'data_nascimento' => ['required', 'date'],
            'sexo' => ['required', 'in:M,F'],
            'telefone' => ['required', 'string', 'max:30'],
            'endereco' => ['nullable', 'string', 'max:255'],

            'nacionalidade' => ['required', 'in:NACIONAL,ESTRANGEIRO'],

            'pais_id' => ['nullable', 'integer', 'exists:paises,id'],
            'provincia_id' => ['nullable', 'integer', 'exists:provincias,id'],
            'municipio_id' => ['nullable', 'integer', 'exists:municipios,id'],

            'tipo_documento' => ['required', 'in:NAO_TEM,ASSENTO,CEDULA,PASSAPORTE,BI'],
            'numero_documento' => ['nullable', 'string', 'max:60'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome_completo.required' => 'O nome completo é obrigatório.',
            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'sexo.in' => 'O sexo deve ser M ou F.',
            'telefone.required' => 'O telefone é obrigatório.',
            'nacionalidade.in' => 'A nacionalidade deve ser NACIONAL ou ESTRANGEIRO.',
            'pais_id.exists' => 'O país selecionado não é válido.',
            'provincia_id.exists' => 'A província selecionada não é válida.',
            'municipio_id.exists' => 'O município selecionado não é válido.',
            'tipo_documento.in' => 'O tipo de documento não é válido.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $nacionalidade = $this->input('nacionalidade');

        if ($nacionalidade === 'NACIONAL') {
            $this->merge(['pais_id' => null]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $nacionalidade = $this->input('nacionalidade');
            $paisId = $this->input('pais_id');

            if ($nacionalidade === 'ESTRANGEIRO' && empty($paisId)) {
                $validator->errors()->add('pais_id', 'O país é obrigatório para paciente estrangeiro.');
            }

            if ($nacionalidade === 'NACIONAL' && ! empty($paisId)) {
                $validator->errors()->add('pais_id', 'Paciente nacional não deve ter país definido.');
            }
        });
    }
}
