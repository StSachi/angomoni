<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Doenças</h1>
                    <p class="text-sm text-slate-600">
                        Catálogo de doenças monitorizadas pelo sistema.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('doencas.create') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700 transition">
                        + Nova doença
                    </a>
                </div>
            </div>

            {{-- Pesquisa --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="md:col-span-3">
                        <label class="block text-xs font-medium text-slate-600">Pesquisar</label>
                        <input name="q" value="{{ request('q') }}"
                               placeholder="Nome, código, classificação..."
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">
                    </div>

                    <div class="flex items-end gap-2">
                        <button class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition">
                            Filtrar
                        </button>

                        <a href="{{ route('doencas.index') }}"
                           class="w-full text-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Flash --}}
            @if(session('success'))
                <div class="bg-teal-50 border border-teal-100 text-teal-800 rounded-2xl p-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabela --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div class="font-semibold text-slate-900">Lista de doenças</div>
                    <div class="text-xs text-slate-500">
                        @if(isset($doencas) && method_exists($doencas, 'total'))
                            Total: {{ $doencas->total() }}
                        @else
                            Total: {{ isset($doencas) ? count($doencas) : 0 }}
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                        <tr class="text-left">
                            <th class="px-5 py-3 font-medium">ID</th>
                            <th class="px-5 py-3 font-medium">Doença</th>
                            <th class="px-5 py-3 font-medium">Código</th>
                            <th class="px-5 py-3 font-medium">Tipo/Classe</th>
                            <th class="px-5 py-3 font-medium text-right">Ações</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                        @forelse($doencas as $d)
                            @php
                                // Ajusta conforme os teus campos reais:
                                $nome = data_get($d, 'nome') ?? data_get($d, 'designacao') ?? '—';
                                $codigo = data_get($d, 'codigo') ?? data_get($d, 'code') ?? '—';
                                $classe = data_get($d, 'tipo') ?? data_get($d, 'categoria') ?? data_get($d, 'classe') ?? '—';
                            @endphp

                            <tr class="hover:bg-slate-50/60">
                                <td class="px-5 py-4 text-slate-700">#{{ $d->id }}</td>

                                <td class="px-5 py-4">
                                    <div class="font-medium text-slate-900">{{ $nome }}</div>
                                    @if(data_get($d,'descricao'))
                                        <div class="text-xs text-slate-500 mt-1 line-clamp-1">
                                            {{ data_get($d,'descricao') }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-slate-700">{{ $codigo }}</td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-700 border-slate-200">
                                        {{ $classe }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('doencas.edit', $d) }}"
                                           class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('doencas.destroy', $d) }}"
                                              onsubmit="return confirm('Eliminar esta doença?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700 transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                                    Nenhuma doença encontrada.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                @if(isset($doencas) && method_exists($doencas, 'links'))
                    <div class="px-5 py-4 border-t border-slate-200">
                        {{ $doencas->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
