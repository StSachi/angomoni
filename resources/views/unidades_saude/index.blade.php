<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Unidades de Saúde</h1>
                    <p class="text-sm text-slate-600">
                        Gestão do cadastro de unidades e respetiva geolocalização.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('unidades-saude.create') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700 transition">
                        + Nova unidade
                    </a>
                </div>
            </div>

            {{-- Pesquisa --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="md:col-span-3">
                        <label class="block text-xs font-medium text-slate-600">Pesquisar</label>
                        <input name="q" value="{{ request('q') }}"
                               placeholder="Nome, município, província, código..."
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">
                    </div>

                    <div class="flex items-end gap-2">
                        <button class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition">
                            Filtrar
                        </button>

                        <a href="{{ route('unidades-saude.index') }}"
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
                    <div class="font-semibold text-slate-900">Lista de unidades</div>
                    <div class="text-xs text-slate-500">
                        @if(isset($unidades) && method_exists($unidades, 'total'))
                            Total: {{ $unidades->total() }}
                        @else
                            Total: {{ isset($unidades) ? count($unidades) : 0 }}
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                        <tr class="text-left">
                            <th class="px-5 py-3 font-medium">ID</th>
                            <th class="px-5 py-3 font-medium">Unidade</th>
                            <th class="px-5 py-3 font-medium">Local</th>
                            <th class="px-5 py-3 font-medium">Geolocalização</th>
                            <th class="px-5 py-3 font-medium text-right">Ações</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                        @forelse($unidades as $u)
                            @php
                                $nome = data_get($u, 'nome') ?? data_get($u, 'designacao') ?? '—';
                                $provincia = data_get($u, 'provincia') ?? '—';
                                $municipio = data_get($u, 'municipio') ?? data_get($u, 'cidade') ?? '—';

                                $lat = data_get($u, 'latitude');
                                $lng = data_get($u, 'longitude');

                                $temCoords = ($lat !== null && $lng !== null);
                            @endphp

                            <tr class="hover:bg-slate-50/60">
                                <td class="px-5 py-4 text-slate-700">#{{ $u->id }}</td>

                                <td class="px-5 py-4">
                                    <div class="font-medium text-slate-900">{{ $nome }}</div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ data_get($u, 'codigo') ?? data_get($u, 'code') ?? '' }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-slate-700">
                                    <div>{{ $municipio }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $provincia }}</div>
                                </td>

                                <td class="px-5 py-4 text-slate-700">
                                    @if($temCoords)
                                        <a class="text-teal-700 hover:text-teal-800 font-semibold"
                                           target="_blank"
                                           href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}">
                                            Ver no mapa
                                        </a>
                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ number_format((float)$lat, 5) }}, {{ number_format((float)$lng, 5) }}
                                        </div>
                                    @else
                                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold bg-amber-50 text-amber-800 border-amber-100">
                                            Sem coordenadas
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('unidades-saude.edit', $u) }}"
                                           class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('unidades-saude.destroy', $u) }}"
                                              onsubmit="return confirm('Eliminar esta unidade?');">
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
                                    Nenhuma unidade encontrada.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                @if(isset($unidades) && method_exists($unidades, 'links'))
                    <div class="px-5 py-4 border-t border-slate-200">
                        {{ $unidades->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
