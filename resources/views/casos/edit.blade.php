<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Caso
            </h2>

            <a href="{{ route('casos.index') }}"
               class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200">
                            <ul class="list-disc ml-5">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('casos.update', $caso) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm mb-1">Doença *</label>
                            <select name="doenca_id" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900" required>
                                <option value="">-- Selecione --</option>
                                @foreach($doencas as $d)
                                    <option value="{{ $d->id }}" {{ old('doenca_id', $caso->doenca_id) == $d->id ? 'selected' : '' }}>
                                        {{ $d->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Unidade de Saúde *</label>
                            <select name="unidade_saude_id" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900" required>
                                <option value="">-- Selecione --</option>
                                @foreach($unidades as $u)
                                    <option value="{{ $u->id }}" {{ old('unidade_saude_id', $caso->unidade_saude_id) == $u->id ? 'selected' : '' }}>
                                        {{ $u->provincia }} / {{ $u->municipio }} - {{ $u->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm mb-1">Data de notificação *</label>
                                <input type="date" name="data_notificacao"
                                       value="{{ old('data_notificacao', $caso->data_notificacao?->toDateString()) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900" required>
                            </div>

                            <div>
                                <label class="block text-sm mb-1">Estado *</label>
                                <select name="estado" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900" required>
                                    @foreach(['SUSPEITO','CONFIRMADO','DESCARTADO','OBITO'] as $e)
                                        <option value="{{ $e }}" {{ old('estado', $caso->estado) === $e ? 'selected' : '' }}>
                                            {{ $e }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm mb-1">Idade</label>
                                <input type="number" name="idade" min="0" max="120"
                                       value="{{ old('idade', $caso->idade) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            </div>

                            <div>
                                <label class="block text-sm mb-1">Sexo *</label>
                                <select name="sexo" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900" required>
                                    <option value="N" {{ old('sexo', $caso->sexo) === 'N' ? 'selected' : '' }}>Não informado</option>
                                    <option value="M" {{ old('sexo', $caso->sexo) === 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo', $caso->sexo) === 'F' ? 'selected' : '' }}>Feminino</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm mb-1">Iniciais do paciente</label>
                                <input name="paciente_iniciais" value="{{ old('paciente_iniciais', $caso->paciente_iniciais) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Telefone de contacto</label>
                                <input name="telefone_contacto" value="{{ old('telefone_contacto', $caso->telefone_contacto) }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Observações</label>
                            <textarea name="observacoes" rows="4"
                                      class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900">{{ old('observacoes', $caso->observacoes) }}</textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Atualizar
                            </button>
                        </div>
                    </form>

                    <form class="mt-4" method="POST" action="{{ route('casos.destroy', $caso) }}"
                          onsubmit="return confirm('Eliminar este caso?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">
                            Eliminar
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
