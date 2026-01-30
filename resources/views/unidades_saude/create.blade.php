<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Nova Unidade de Saúde
            </h2>

            <a href="{{ route('unidades-saude.index') }}"
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

                    <form method="POST" action="{{ route('unidades-saude.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm mb-1">Nome *</label>
                            <input name="nome" value="{{ old('nome') }}"
                                   class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                                   required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm mb-1">Província *</label>
                                <input name="provincia" value="{{ old('provincia') }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Município *</label>
                                <input name="municipio" value="{{ old('municipio') }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                                       required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm mb-1">Comuna</label>
                                <input name="comuna" value="{{ old('comuna') }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Bairro</label>
                                <input name="bairro" value="{{ old('bairro') }}"
                                       class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Telefone</label>
                            <input name="telefone" value="{{ old('telefone') }}"
                                   class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                                   placeholder="Ex: +244 9xx xxx xxx">
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
