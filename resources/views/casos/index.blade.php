<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Casos
            </h2>

            <a href="{{ route('casos.create') }}"
               class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                + Registar Caso
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-2 pr-4">Data</th>
                                    <th class="py-2 pr-4">Doença</th>
                                    <th class="py-2 pr-4">Unidade</th>
                                    <th class="py-2 pr-4">Estado</th>
                                    <th class="py-2 pr-0 text-right">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($casos as $c)
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-2 pr-4">
                                            {{ $c->data_notificacao?->format('Y-m-d') }}
                                        </td>

                                        <td class="py-2 pr-4">
                                            {{ $c->doenca?->nome }}
                                        </td>

                                        <td class="py-2 pr-4">
                                            {{ $c->unidadeSaude?->nome }}
                                        </td>

                                        <td class="py-2 pr-4">
                                            {{ $c->estado }}
                                        </td>

                                        <td class="py-2 pr-0 text-right">
                                            <a href="{{ route('casos.show', $c) }}"
                                               class="underline mr-3">
                                                Ver
                                            </a>

                                            <a href="{{ route('casos.edit', $c) }}"
                                               class="underline mr-3">
                                                Editar
                                            </a>

                                            <form class="inline"
                                                  method="POST"
                                                  action="{{ route('casos.destroy', $c) }}"
                                                  onsubmit="return confirm('Eliminar este caso?')">
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
                                        <td colspan="5" class="py-4">
                                            Nenhum caso registado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $casos->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
