<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Doenças
            </h2>

            <a href="{{ route('doencas.create') }}"
               class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                + Nova Doença
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Mensagem de sucesso --}}
                    @if (session('success'))
                        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-2 pr-4">Nome</th>
                                    <th class="py-2 pr-4">Código</th>
                                    <th class="py-2 pr-4">Ativa</th>
                                    <th class="py-2 pr-0 text-right">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($doencas as $d)
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-2 pr-4">{{ $d->nome }}</td>

                                        <td class="py-2 pr-4">
                                            {{ $d->codigo ?? '-' }}
                                        </td>

                                        <td class="py-2 pr-4">
                                            <span class="px-2 py-1 rounded text-xs
                                                {{ $d->ativa
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200'
                                                    : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200' }}">
                                                {{ $d->ativa ? 'Sim' : 'Não' }}
                                            </span>
                                        </td>

                                        <td class="py-2 pr-0 text-right">
                                            <a href="{{ route('doencas.edit', $d) }}"
                                               class="underline mr-3">
                                                Editar
                                            </a>

                                            <form class="inline"
                                                  method="POST"
                                                  action="{{ route('doencas.destroy', $d) }}"
                                                  onsubmit="return confirm('Eliminar esta doença?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="underline text-red-600 dark:text-red-400">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4" colspan="4">
                                            Nenhuma doença cadastrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginação --}}
                    <div class="mt-4">
                        {{ $doencas->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
