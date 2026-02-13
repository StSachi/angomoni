<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                        Dashboard
                    </h1>
                    <p class="text-sm text-slate-600">
                        Bem-vindo, <span class="font-semibold text-slate-900">{{ $user->name }}</span>
                        <span class="mx-1">•</span>
                        Perfil:
                        <span class="font-semibold text-teal-700">{{ $papel ?? '—' }}</span>
                    </p>
                </div>

                <div class="text-xs text-slate-500">
                    Última atualização:
                    <span class="font-semibold text-slate-700">
                        {{ $ultima_atualizacao ? \Carbon\Carbon::parse($ultima_atualizacao)->format('d/m/Y H:i') : '—' }}
                    </span>
                </div>
            </div>

            {{-- Feedback --}}
            @if (session('success'))
                <div class="rounded-2xl border border-teal-200 bg-teal-50 p-4 text-sm text-teal-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                    <div class="text-sm font-semibold text-red-700">Ocorreram erros</div>
                    <ul class="mt-2 list-disc pl-5 text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Cards métricas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                    <div class="text-xs text-slate-500">Casos (Total)</div>
                    <div class="mt-2 text-2xl font-semibold text-slate-900">{{ $casos_total }}</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                    <div class="text-xs text-slate-500">Casos (Últimos 7 dias)</div>
                    <div class="mt-2 text-2xl font-semibold text-slate-900">{{ $casos_7d }}</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                    <div class="text-xs text-slate-500">Doenças</div>
                    <div class="mt-2 text-2xl font-semibold text-slate-900">{{ $doencas_total }}</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                    <div class="text-xs text-slate-500">Unidades de Saúde</div>
                    <div class="mt-2 text-2xl font-semibold text-slate-900">{{ $unidades_total }}</div>
                </div>
            </div>

            {{-- Ações rápidas --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900">Ações rápidas</h2>
                <p class="text-sm text-slate-600 mt-1">Atalhos para tarefas frequentes no sistema.</p>

                <div class="mt-4 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('casos.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Ver casos
                    </a>

                    @if(in_array($papel, ['REGISTADOR','ADMIN'], true))
                        <a href="{{ route('casos.create') }}"
                           class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-700 transition">
                            Registar novo caso
                        </a>
                    @endif

                    @if(in_array($papel, ['ADMIN'], true))
                        <a href="{{ route('doencas.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            Gerir doenças
                        </a>

                        <a href="{{ route('unidades-saude.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            Gerir unidades
                        </a>

                        <a href="{{ route('users.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            Utilizadores
                        </a>
                    @endif
                </div>
            </div>

            {{-- Atividade recente --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-900">Atividade recente</h2>
                    <span class="text-xs text-slate-500">Últimos registos</span>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-600">
                                <th class="py-2 pr-4">Utilizador</th>
                                <th class="py-2 pr-4">Ação</th>
                                <th class="py-2 pr-4">Descrição</th>
                                <th class="py-2 pr-4">Data</th>
                                <th class="py-2">IP</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @forelse($atividades as $a)
                                <tr class="text-slate-700 align-top">
                                    <td class="py-2 pr-4 whitespace-nowrap">
                                        {{ $a->user?->name ?? '—' }}
                                    </td>

                                    <td class="py-2 pr-4 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-full bg-teal-50 px-2 py-1 text-xs font-semibold text-teal-700">
                                            {{ $a->acao }}
                                        </span>
                                    </td>

                                    <td class="py-2 pr-4 text-slate-600">
                                        {{ $a->descricao ?? '—' }}
                                    </td>

                                    <td class="py-2 pr-4 whitespace-nowrap">
                                        {{ optional($a->created_at)->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="py-2 whitespace-nowrap">
                                        {{ $a->ip_address ?? '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-slate-500">
                                        Sem atividade registada ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-xs text-slate-500">
                    Nota: As ações podem ser registadas para auditoria institucional.
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
