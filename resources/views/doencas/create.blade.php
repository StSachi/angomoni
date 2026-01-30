<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Nova Doença
            </h2>

            <a href="{{ route('doencas.index') }}"
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

                    <form method="POST" action="{{ route('doencas.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm mb-1">Nome *</label>
                            <input name="nome" value="{{ old('nome') }}"
                                   class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                                   placeholder="Ex: Malária" required>
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Código</label>
                            <input name="codigo" value="{{ old('codigo') }}"
                                   class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                                   placeholder="Ex: MAL-001">
                            <p class="text-xs text-gray-500 mt-1">Opcional, mas se preencher deve ser único.</p>
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Descrição</label>
                            <textarea name="descricao" rows="4"
                                      class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                                      placeholder="Breve descrição da doença...">{{ old('descricao') }}</textarea>
                        </div>

                        <div class="flex items-center gap-2">
                            <input id="ativa" type="checkbox" name="ativa" value="1"
                                   class="rounded border-gray-300 dark:border-gray-700"
                                   {{ old('ativa', '1') ? 'checked' : '' }}>
                            <label for="ativa" class="text-sm">Ativa</label>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Guardar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
