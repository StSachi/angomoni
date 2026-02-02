<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Casos</h1>
                    <p class="text-sm text-slate-600">
                        Gestão e consulta de casos registados no sistema, com geolocalização.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('casos.create') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700 transition">
                        + Registar caso
                    </a>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-600">Pesquisar</label>
                        <input name="q" value="{{ request('q') }}"
                               placeholder="Nome, ID, observação..."
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">
                    </div>

                    <td class="px-4 py-3 text-sm text-slate-700">
                        {{ $caso->cidade ?? '—' }}, {{ $caso->provincia ?? '—' }}
                    </td>


                    <div>
                        <label class="block text-xs font-medium text-slate-600">Data início</label>
                        <input type="date" name="inicio" value="{{ request('inicio') }}"
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600">Data fim</label>
                        <input type="date" name="fim" value="{{ request('fim') }}"
                               class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">
                    </div>

                    <div class="flex items-end gap-2">
                        <button class="w-full rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 transition">
                            Filtrar
                        </button>

                        <a href="{{ route('casos.index') }}"
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

            @php
                // Dados para o mapa (tentando ser compatível com teus nomes)
                $casosMapa = collect($casos)->map(function ($caso) {
                    $lat = data_get($caso, 'unidadeSaude.latitude')
                        ?? data_get($caso, 'unidade.latitude')
                        ?? data_get($caso, 'unidade_saude.latitude');

                    $lng = data_get($caso, 'unidadeSaude.longitude')
                        ?? data_get($caso, 'unidade.longitude')
                        ?? data_get($caso, 'unidade_saude.longitude');

                    if ($lat === null || $lng === null) return null;

                    $doenca = data_get($caso, 'doenca.nome') ?? data_get($caso, 'doenca') ?? 'N/D';
                    $unidade = data_get($caso, 'unidadeSaude.nome')
                        ?? data_get($caso, 'unidade.nome')
                        ?? data_get($caso, 'unidade_saude.nome')
                        ?? 'N/D';

                    $dataCaso = data_get($caso, 'data_registo') ?? data_get($caso, 'data') ?? data_get($caso, 'created_at');
                    $dataFmt = $dataCaso ? \Illuminate\Support\Carbon::parse($dataCaso)->format('d/m/Y') : '—';

                    return [
                        'id' => $caso->id,
                        'doenca' => $doenca,
                        'unidade' => $unidade,
                        'data' => $dataFmt,
                        'lat' => (float) $lat,
                        'lng' => (float) $lng,
                        'estado' => data_get($caso, 'estado') ?? '—',
                    ];
                })->filter()->values();
            @endphp

            {{-- MAPA DE GEOLOCALIZAÇÃO --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-slate-900">Geolocalização dos casos</div>
                        <div class="text-xs text-slate-500 mt-1">
                            Mostra no mapa apenas casos com coordenadas (via Unidade de Saúde).
                        </div>
                    </div>

                    <div class="text-xs text-slate-500">
                        Com coordenadas: {{ $casosMapa->count() }}
                    </div>
                </div>

                <div class="p-5">
                    <div id="casosMap" class="w-full rounded-2xl border border-slate-200" style="height: 360px;"></div>

                    @if($casosMapa->isEmpty())
                        <p class="mt-3 text-sm text-slate-600">
                            Nenhum caso com coordenadas encontrado. Para ativar o mapa, adiciona <b>latitude</b> e <b>longitude</b> às Unidades de Saúde.
                        </p>
                    @endif
                </div>
            </div>

            {{-- Tabela --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div class="font-semibold text-slate-900">Lista de casos</div>
                    <div class="text-xs text-slate-500">
                        @if(isset($casos) && method_exists($casos, 'total'))
                            Total: {{ $casos->total() }}
                        @else
                            Total: {{ isset($casos) ? count($casos) : 0 }}
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                        <tr class="text-left">
                            <th class="px-5 py-3 font-medium">ID</th>
                            <th class="px-5 py-3 font-medium">Doença</th>
                            <th class="px-5 py-3 font-medium">Unidade</th>
                            <th class="px-5 py-3 font-medium">Localização</th>
                            <th class="px-5 py-3 font-medium">Data</th>
                            <th class="px-5 py-3 font-medium">Estado</th>
                            <th class="px-5 py-3 font-medium text-right">Ações</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                        @forelse($casos as $caso)
                            @php
                                $doencaNome = data_get($caso, 'doenca.nome') ?? data_get($caso, 'doenca') ?? '—';
                                $unidadeNome = data_get($caso, 'unidadeSaude.nome')
                                    ?? data_get($caso, 'unidade.nome')
                                    ?? data_get($caso, 'unidade_saude.nome')
                                    ?? '—';

                                $lat = data_get($caso, 'unidadeSaude.latitude')
                                    ?? data_get($caso, 'unidade.latitude')
                                    ?? data_get($caso, 'unidade_saude.latitude');

                                $lng = data_get($caso, 'unidadeSaude.longitude')
                                    ?? data_get($caso, 'unidade.longitude')
                                    ?? data_get($caso, 'unidade_saude.longitude');

                                $dataCaso = data_get($caso, 'data_registo') ?? data_get($caso, 'data') ?? data_get($caso, 'created_at');
                                $estado = data_get($caso, 'estado') ?? '—';

                                $badge = "bg-slate-100 text-slate-700 border-slate-200";
                                if (is_string($estado)) {
                                    $e = mb_strtolower($estado);
                                    if (str_contains($e, 'ativo') || str_contains($e, 'aberto')) $badge = "bg-amber-50 text-amber-800 border-amber-100";
                                    if (str_contains($e, 'encerr') || str_contains($e, 'resolv')) $badge = "bg-emerald-50 text-emerald-800 border-emerald-100";
                                    if (str_contains($e, 'grave') || str_contains($e, 'crit')) $badge = "bg-rose-50 text-rose-800 border-rose-100";
                                }
                            @endphp

                            <tr class="hover:bg-slate-50/60">
                                <td class="px-5 py-4 text-slate-700">#{{ $caso->id }}</td>

                                <td class="px-5 py-4">
                                    <div class="font-medium text-slate-900">{{ $doencaNome }}</div>
                                </td>

                                <td class="px-5 py-4 text-slate-700">{{ $unidadeNome }}</td>

                                <td class="px-5 py-4 text-slate-700">
                                    @if($lat !== null && $lng !== null)
                                        <a class="text-teal-700 hover:text-teal-800 font-semibold"
                                           target="_blank"
                                           href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}">
                                            Ver no mapa
                                        </a>
                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ number_format((float)$lat, 5) }}, {{ number_format((float)$lng, 5) }}
                                        </div>
                                    @else
                                        <span class="text-slate-400">Sem coordenadas</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-slate-700">
                                    {{ $dataCaso ? \Illuminate\Support\Carbon::parse($dataCaso)->format('d/m/Y') : '—' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badge }}">
                                        {{ $estado }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('casos.show', $caso) }}"
                                           class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                                            Ver
                                        </a>

                                        <a href="{{ route('casos.edit', $caso) }}"
                                           class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('casos.destroy', $caso) }}"
                                              onsubmit="return confirm('Eliminar este caso?');">
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
                                <td colspan="7" class="px-5 py-10 text-center text-slate-500">
                                    Nenhum caso encontrado.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginação --}}
                @if(isset($casos) && method_exists($casos, 'links'))
                    <div class="px-5 py-4 border-t border-slate-200">
                        {{ $casos->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        (function () {
            const data = {!! json_encode($casosMapa) !!};

            // Se não tem dados, não inicializa
            if (!data || data.length === 0) return;

            const map = L.map('casosMap');

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            const markers = L.featureGroup();

            data.forEach(item => {
                const m = L.marker([item.lat, item.lng]);
                m.bindPopup(`
                    <div style="min-width:220px">
                        <b>${item.doenca}</b><br>
                        Unidade: ${item.unidade}<br>
                        Data: ${item.data}<br>
                        Estado: ${item.estado}<br>
                        Caso ID: #${item.id}
                    </div>
                `);
                markers.addLayer(m);
            });

            markers.addTo(map);
            map.fitBounds(markers.getBounds().pad(0.2));
        })();
    </script>
</x-app-layout>
