<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Unidades de Saúde
            </h2>

            <a href="{{ route('unidades-saude.create') }}"
               class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                + Nova Unidade
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
                                    <th class="py-2 pr-4">Nome</th>
                                    <th class="py-2 pr-4">Província</th>
                                    <th class="py-2 pr-4">Município</th>
                                    <th class="py-2 pr-4">Comuna/Bairro</th>
                                    <th class="py-2 pr-4">Telefone</th>
                                    <th class="py-2 pr-0 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unidades as $u)
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-2 pr-4">{{ $u->nome }}</td>
                                        <td class="py-2 pr-4">{{ $u->provincia }}</td>
                                        <td class="py-2 pr-4">{{ $u->municipio }}</td>
                                        <td class="py-2 pr-4">
                                            {{ $u->comuna ?? '-' }}{{ ($u->bairro ? ' / '.$u->bairro : '') }}
                                        </td>
                                        <td class="py-2 pr-4">{{ $u->telefone ?? '-' }}</td>

                                        <td class="py-2 pr-0 text-right">
                                            <a href="{{ route('unidades-saude.edit', $u) }}"
                                               class="underline mr-3">
                                                Editar
                                            </a>

                                            <form class="inline" method="POST"
                                                  action="{{ route('unidades-saude.destroy', $u) }}"
                                                  onsubmit="return confirm('Eliminar esta unidade de saúde?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="underline text-red-600 dark:text-red-400">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4" colspan="6">Nenhuma unidade cadastrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $unidades->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
