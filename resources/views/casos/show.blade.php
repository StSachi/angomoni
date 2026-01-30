<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detalhe do Caso
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

                    @if (session('success'))
                        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="space-y-2">
                        <div><strong>Data:</strong> {{ $caso->data_notificacao->format('Y-m-d') }}</div>
                        <div><strong>Doença:</strong> {{ $caso->doenca?->nome }}</div>
                        <div><strong>Unidade:</strong> {{ $caso->unidadeSaude?->nome }} ({{ $caso->unidadeSaude?->provincia }} / {{ $caso->unidadeSaude?->municipio }})</div>
                        <div><strong>Estado:</strong> {{ $caso->estado }}</div>
                        <div><strong>Sexo:</strong> {{ $caso->sexo }}</div>
                        <div><strong>Idade:</strong> {{ $caso->idade ?? '-' }}</div>
                        <div><strong>Iniciais:</strong> {{ $caso->paciente_iniciais ?? '-' }}</div>
                        <div><strong>Telefone:</strong> {{ $caso->telefone_contacto ?? '-' }}</div>
                        <div><strong>Registado por:</strong> {{ $caso->user?->name }} ({{ $caso->user?->email }})</div>

                        @if($caso->observacoes)
                            <div class="pt-2">
                                <strong>Observações:</strong>
                                <div class="mt-1 whitespace-pre-line">{{ $caso->observacoes }}</div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
