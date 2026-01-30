<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Gestão de Utilizadores
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow sm:rounded-lg p-4 overflow-x-auto">
            <table class="w-full border">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-2">ID</th>
                        <th class="text-left p-2">Nome</th>
                        <th class="text-left p-2">Email</th>
                        <th class="text-left p-2">Papel</th>
                        <th class="text-left p-2">Ativo</th>
                        <th class="text-right p-2">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b">
                            <td class="p-2">{{ $user->id }}</td>
                            <td class="p-2">{{ $user->name }}</td>
                            <td class="p-2">{{ $user->email }}</td>
                            <td class="p-2">{{ $user->papel }}</td>
                            <td class="p-2">{{ $user->ativo ? 'Sim' : 'Não' }}</td>
                            <td class="p-2 text-right">
                                @can('delete', $user)
                                    <form method="POST"
                                          action="{{ route('users.destroy', $user) }}"
                                          onsubmit="return confirm('Eliminar este utilizador?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 bg-red-600 text-white rounded">
                                            Eliminar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-2 text-gray-500" colspan="6">
                                Nenhum utilizador encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
