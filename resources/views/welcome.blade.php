<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AngoMoni</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
<div class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- LOGO -->
                <img src="{{ asset('images/angomoni-logo.png') }}"
                     alt="AngoMoni"
                     class="h-10 w-auto">

                <div class="leading-tight">
                    <div class="text-lg font-semibold">AngoMoni</div>
                    <div class="text-xs text-slate-600">
                        Monitorização de Doenças Endémicas
                    </div>
                </div>
            </div>

            <a href="{{ route('login') }}"
               class="px-4 py-2 rounded-xl bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700 transition">
                Entrar
            </a>
        </div>
    </header>

    <!-- HERO -->
    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-6 py-16 grid lg:grid-cols-2 gap-12 items-center">
            <!-- TEXTO -->
            <div class="space-y-6">
                <h1 class="text-4xl sm:text-5xl font-semibold tracking-tight">
                    Vigilância epidemiológica orientada a decisões
                </h1>

                <p class="text-slate-600 max-w-xl">
                    Plataforma institucional para registo de casos, monitorização de surtos
                    e apoio à decisão em saúde pública.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('login') }}"
                       class="px-5 py-3 rounded-2xl bg-teal-600 text-white font-semibold hover:bg-teal-700 transition">
                        Iniciar sessão
                    </a>

                    <a href="#sobre"
                       class="px-5 py-3 rounded-2xl border border-slate-300 text-slate-700 hover:bg-slate-100 transition">
                        Ver detalhes
                    </a>
                </div>

                <p class="text-xs text-slate-500 max-w-lg">
                    Uso restrito a entidades autorizadas. As ações realizadas no sistema
                    podem ser registadas para fins de auditoria.
                </p>
            </div>

            <!-- PAINEL ILUSTRATIVO -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200">
                <div class="flex items-center justify-between">
                    <div class="font-semibold">Resumo Operacional</div>
                    <div class="text-xs text-slate-500">Sistema</div>
                </div>

                <div class="mt-5 grid grid-cols-3 gap-3">
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <div class="text-xs text-slate-500">Casos</div>
                        <div class="text-2xl font-semibold mt-1">—</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <div class="text-xs text-slate-500">Surtos</div>
                        <div class="text-2xl font-semibold mt-1">—</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <div class="text-xs text-slate-500">Alertas</div>
                        <div class="text-2xl font-semibold mt-1">—</div>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl bg-teal-50 p-4 border border-teal-100">
                    <div class="text-sm font-semibold text-teal-900">
                        Acesso institucional
                    </div>
                    <div class="text-xs text-teal-700 mt-1">
                        Perfis: ADMIN / PROFISSIONAL · Dados sensíveis protegidos
                    </div>
                </div>
            </div>
        </div>

        <!-- SOBRE -->
        <section id="sobre" class="border-t border-slate-200 bg-white">
            <div class="max-w-6xl mx-auto px-6 py-14 grid md:grid-cols-3 gap-6">
                <div class="rounded-2xl border border-slate-200 p-6">
                    <div class="font-semibold">Registo de Casos</div>
                    <p class="text-sm text-slate-600 mt-2">
                        Fluxo simples, validado e controlado por permissões.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 p-6">
                    <div class="font-semibold">Mapa de Surtos</div>
                    <p class="text-sm text-slate-600 mt-2">
                        Visualização geográfica com filtros por período e doença.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 p-6">
                    <div class="font-semibold">Auditoria</div>
                    <p class="text-sm text-slate-600 mt-2">
                        Rastreabilidade de ações críticas do sistema.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="border-t border-slate-200 bg-slate-50">
        <div class="max-w-6xl mx-auto px-6 py-6 text-xs text-slate-500 flex justify-between">
            <span>© {{ date('Y') }} AngoMoni</span>
            <span>Interface institucional · HealthTech</span>
        </div>
    </footer>

</div>
</body>
</html>
