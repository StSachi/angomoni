<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                        Dashboard
                    </h1>
                    <p class="text-sm text-slate-600">
                        Visão geral operacional do sistema AngoMoni.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('casos.index') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700 transition">
                        Ver casos
                    </a>

                    {{-- Se tiveres mapa pronto depois, troca o # para route('mapa.index') --}}
                    <a href="#"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Abrir mapa
                    </a>
                </div>
            </div>

            {{-- KPI cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <div class="text-xs text-slate-500">Casos registados</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">
                        {{ $kpis['casos_total'] ?? 0 }}
                    </div>

                    <div class="mt-2 text-xs text-slate-500">Total no sistema</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <div class="text-xs text-slate-500">Casos (últimos 7 dias)</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">
                        {{ $kpis['casos_7d'] ?? 0 }}
                    </div>

                    <div class="mt-2 text-xs text-slate-500">Tendência semanal</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <div class="text-xs text-slate-500">Unidades de saúde</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">
                        {{ $kpis['unidades_total'] ?? 0 }}
                    </div>
                    <div class="mt-2 text-xs text-slate-500">Cadastradas (ADMIN)</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <div class="text-xs text-slate-500">Doenças</div>
                    <div class="mt-2 text-3xl font-semibold text-slate-900">
                        {{ $kpis['doencas_total'] ?? 0 }}
                    </div>
                    <div class="mt-2 text-xs text-slate-500">Catálogo (ADMIN)</div>
                </div>
            </div>

            {{-- Main grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- Atalhos / Ações --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-base font-semibold text-slate-900">
                        Ações rápidas
                    </h2>
                    <p class="mt-1 text-sm text-slate-600">
                        Acesso direto às operações mais comuns.
                    </p>

                    <div class="mt-5 grid grid-cols-1 gap-3">
                        <a href="{{ route('casos.create') }}"
                           class="rounded-xl border border-slate-200 p-4 hover:bg-slate-50 transition">
                            <div class="text-sm font-semibold text-slate-900">Registar novo caso</div>
                            <div class="text-xs text-slate-600 mt-1">Criar registo com validação.</div>
                        </a>

                        <a href="{{ route('casos.index') }}"
                           class="rounded-xl border border-slate-200 p-4 hover:bg-slate-50 transition">
                            <div class="text-sm font-semibold text-slate-900">Consultar casos</div>
                            <div class="text-xs text-slate-600 mt-1">Pesquisar e filtrar.</div>
                        </a>

                        {{-- Links ADMIN (opcionais). Se não existir auth()->user(), remove os @if --}}
                        @if(auth()->check() && auth()->user()->papel === 'ADMIN')
                            <a href="{{ route('doencas.index') }}"
                               class="rounded-xl border border-slate-200 p-4 hover:bg-slate-50 transition">
                                <div class="text-sm font-semibold text-slate-900">Gerir doenças</div>
                                <div class="text-xs text-slate-600 mt-1">Catálogo e classificações.</div>
                            </a>

                            <a href="{{ route('unidades-saude.index') }}"
                               class="rounded-xl border border-slate-200 p-4 hover:bg-slate-50 transition">
                                <div class="text-sm font-semibold text-slate-900">Gerir unidades de saúde</div>
                                <div class="text-xs text-slate-600 mt-1">Cadastrar e atualizar.</div>
                            </a>

                            {{-- Se tiveres users.index só para admin --}}
                            @if(Route::has('users.index'))
                                <a href="{{ route('users.index') }}"
                                   class="rounded-xl border border-slate-200 p-4 hover:bg-slate-50 transition">
                                    <div class="text-sm font-semibold text-slate-900">Gerir utilizadores</div>
                                    <div class="text-xs text-slate-600 mt-1">Contas e permissões.</div>
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Painel “Atividade recente” (real) --}}
<div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Atividade recente</h2>
            <p class="text-sm text-slate-600">Últimas ações críticas registadas (auditoria).</p>
        </div>

        <span class="text-xs px-3 py-1 rounded-full bg-teal-50 text-teal-700 border border-teal-100">
            Auditoria ativa
        </span>
    </div>

    <div class="mt-5 divide-y divide-slate-200">
        @forelse($ultimasAuditorias as $a)
            <div class="py-4 flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-slate-900">
                        {{ $a->acao }}
                        @if(auth()->user()->papel === 'ADMIN' && $a->user)
                            <span class="text-slate-500 font-normal">— {{ $a->user->name }}</span>
                        @endif
                    </div>

                    <div class="text-xs text-slate-600 mt-1">
                        {{ $a->descricao }}
                    </div>
                </div>

                <div class="text-xs text-slate-500 whitespace-nowrap">
                    {{ optional($a->created_at)->format('d/m H:i') }}
                </div>
            </div>
        @empty
            <div class="py-6 text-sm text-slate-500">
                Ainda não há atividade registada.
            </div>
        @endforelse
    </div>

    <div class="mt-5 flex justify-end">
        <a href="{{ route('users.index') }}"
           class="text-sm font-semibold text-teal-700 hover:text-teal-800">
            Ver utilizadores →
        </a>
    </div>
</div>


                        <div class="py-4 flex items-start justify-between gap-4">
                            <div>
                                <div class="text-sm font-semibold text-slate-900">
                                    —
                                </div>
                                <div class="text-xs text-slate-600 mt-1">
                                    Depois ligamos isto ao banco: últimos 10 casos / últimas 10 auditorias.
                                </div>
                            </div>
                            <div class="text-xs text-slate-500 whitespace-nowrap">—</div>
                        </div>
                    </div>

                    <div class="mt-5 flex justify-end">
                        <a href="{{ route('casos.index') }}"
                           class="text-sm font-semibold text-teal-700 hover:text-teal-800">
                            Ver todos os casos →
                        </a>
                    </div>
                </div>
            </div>

            {{-- Aviso / Rodapé do dashboard --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="mt-1 h-2.5 w-2.5 rounded-full bg-teal-600"></div>
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Nota institucional</div>
                        <div class="text-sm text-slate-600 mt-1">
                            Dados sensíveis devem ser tratados conforme as políticas internas. O uso do sistema é monitorizado e pode ser auditado.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
