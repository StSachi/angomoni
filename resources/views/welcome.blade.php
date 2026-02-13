<x-guest-layout>
    @php
        $user = auth()->user();
        $isAuth = (bool) $user;
    @endphp

    <style>
        .fade-in { opacity: 0; transform: translateY(18px); transition: all .8s ease-out; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }
    </style>

    {{-- Navbar --}}
    <nav class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="h-16 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/angomoni-logo.png') }}" alt="AngoMoni" class="h-9 w-auto">
                    <span class="text-lg font-semibold tracking-tight text-slate-900">
                        AngoMoni
                    </span>
                </a>

                <div class="hidden md:flex items-center gap-7 text-sm">
                    <a href="#sobre" class="text-slate-700 hover:text-teal-700 transition">Sobre</a>
                    <a href="#objetivos" class="text-slate-700 hover:text-teal-700 transition">Objetivos</a>
                    <a href="#funcionalidades" class="text-slate-700 hover:text-teal-700 transition">Funcionalidades</a>

                    @if($isAuth)
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center justify-center rounded-xl bg-teal-700 px-4 py-2 text-white font-semibold hover:bg-teal-800 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center rounded-xl bg-teal-700 px-4 py-2 text-white font-semibold hover:bg-teal-800 transition">
                            Entrar
                        </a>
                    @endif
                </div>

                {{-- Mobile menu button --}}
                <button id="mobile-menu-btn"
                        class="md:hidden inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-700 hover:bg-slate-50"
                        aria-label="Abrir menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-200 bg-white">
            <div class="max-w-6xl mx-auto px-4 py-4 flex flex-col gap-3 text-sm">
                <a href="#sobre" class="text-slate-700 hover:text-teal-700">Sobre</a>
                <a href="#objetivos" class="text-slate-700 hover:text-teal-700">Objetivos</a>
                <a href="#funcionalidades" class="text-slate-700 hover:text-teal-700">Funcionalidades</a>

                <div class="pt-2 flex gap-3">
                    @if($isAuth)
                        <a href="{{ route('dashboard') }}"
                           class="flex-1 text-center rounded-xl bg-teal-700 px-4 py-2 text-white font-semibold hover:bg-teal-800 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="flex-1 text-center rounded-xl bg-teal-700 px-4 py-2 text-white font-semibold hover:bg-teal-800 transition">
                            Entrar
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="flex-1 text-center rounded-xl border border-teal-700 px-4 py-2 text-teal-700 font-semibold hover:bg-teal-50 transition">
                                Criar conta
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="bg-gradient-to-br from-teal-50 via-white to-slate-50">
        <div class="max-w-6xl mx-auto px-4 py-14 md:py-20">
            <div class="grid lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7">
                    <div class="fade-in inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1 text-xs text-slate-600">
                        <span class="h-2 w-2 rounded-full bg-teal-700"></span>
                        Sistema institucional de vigilância epidemiológica
                    </div>

                    <h1 class="fade-in mt-5 text-4xl md:text-5xl font-bold tracking-tight text-slate-900 leading-tight">
                        Monitorização e resposta rápida<br class="hidden md:block">
                        a <span class="text-teal-700">doenças endémicas</span>
                    </h1>

                    <p class="fade-in mt-5 text-lg text-slate-600 leading-relaxed max-w-2xl">
                        Registo estruturado de casos, análise por <b>província e município</b>,
                        dashboards institucionais, relatórios e auditoria — com proteção de dados sensíveis.
                    </p>

                    <div class="fade-in mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="#funcionalidades"
                           class="inline-flex items-center justify-center rounded-xl bg-teal-700 px-6 py-3 text-white font-semibold hover:bg-teal-800 transition">
                            Ver funcionalidades
                        </a>

                        @if($isAuth)
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex items-center justify-center rounded-xl border border-teal-700 px-6 py-3 text-teal-700 font-semibold hover:bg-teal-50 transition">
                                Abrir dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center rounded-xl border border-teal-700 px-6 py-3 text-teal-700 font-semibold hover:bg-teal-50 transition">
                                Entrar
                            </a>
                        @endif
                    </div>

                    <div class="fade-in mt-8 flex flex-wrap gap-2 text-xs">
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-2 text-slate-700">
                            RBAC (ADMIN / PROFISSIONAL)
                        </span>
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-2 text-slate-700">
                            Auditoria de ações
                        </span>
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-2 text-slate-700">
                            Mascaramento de dados
                        </span>
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-2 text-slate-700">
                            Mapa institucional
                        </span>
                    </div>
                </div>

                {{-- Card lateral --}}
                <div class="lg:col-span-5">
                    <div class="fade-in bg-white border border-slate-200 rounded-2xl shadow-sm p-6 md:p-7">
                        <h2 class="text-lg font-semibold text-slate-900">Acesso institucional</h2>
                        <p class="mt-1 text-sm text-slate-600">
                            O sistema é destinado a entidades autorizadas. O acesso é controlado por papéis e políticas.
                        </p>

                        <div class="mt-5 grid gap-3 text-sm">
                            <div class="flex gap-3">
                                <span class="mt-0.5 h-8 w-8 rounded-xl bg-teal-50 flex items-center justify-center text-teal-700 font-bold">1</span>
                                <div>
                                    <div class="font-medium text-slate-900">Registo de casos</div>
                                    <div class="text-slate-600">Casos vinculados à unidade e localização institucional.</div>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <span class="mt-0.5 h-8 w-8 rounded-xl bg-teal-50 flex items-center justify-center text-teal-700 font-bold">2</span>
                                <div>
                                    <div class="font-medium text-slate-900">Análise e relatórios</div>
                                    <div class="text-slate-600">Filtros por doença, província, município e período.</div>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <span class="mt-0.5 h-8 w-8 rounded-xl bg-teal-50 flex items-center justify-center text-teal-700 font-bold">3</span>
                                <div>
                                    <div class="font-medium text-slate-900">Rastreabilidade</div>
                                    <div class="text-slate-600">Ações registadas para auditoria e conformidade.</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-5 border-t border-slate-200 text-xs text-slate-500 leading-relaxed">
                            Uso restrito. Dados sensíveis podem ser mascarados em relatórios e exportações.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Sobre --}}
    <section id="sobre" class="bg-white">
        <div class="max-w-6xl mx-auto px-4 py-14 md:py-20">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="fade-in text-3xl md:text-4xl font-bold text-slate-900">Sobre o Projeto</h2>
                <p class="fade-in mt-4 text-slate-600 leading-relaxed">
                    O AngoMoni é um sistema web para monitorização e controlo de doenças endémicas,
                    orientado à realidade institucional, com foco em segurança, integridade e apoio à decisão.
                </p>
            </div>

            <div class="mt-12 grid md:grid-cols-2 gap-8 items-start">
                <div class="fade-in bg-slate-50 border border-slate-200 rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Problema</h3>
                    <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                        A falta de registo estruturado e análise rápida compromete a resposta a surtos.
                        O sistema centraliza dados, reduz erros e aumenta rastreabilidade.
                    </p>
                </div>

                <div class="fade-in bg-slate-50 border border-slate-200 rounded-2xl p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Solução</h3>
                    <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                        Registo de casos, gestão de doenças e unidades sanitárias,
                        dashboards e relatórios com RBAC, políticas e auditoria.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Objetivos --}}
    <section id="objetivos" class="bg-slate-50 border-y border-slate-200">
        <div class="max-w-6xl mx-auto px-4 py-14 md:py-20">
            <div class="text-center">
                <h2 class="fade-in text-3xl md:text-4xl font-bold text-slate-900">Objetivos do Sistema</h2>
                <p class="fade-in mt-4 text-slate-600">Três pilares institucionais para vigilância epidemiológica.</p>
            </div>

            <div class="mt-12 grid md:grid-cols-3 gap-6">
                <div class="fade-in bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="h-12 w-12 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-700 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-slate-900">Monitorização</h3>
                    <p class="mt-2 text-sm text-slate-600">Registo e acompanhamento de casos com consistência institucional.</p>
                </div>

                <div class="fade-in bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="h-12 w-12 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-700 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 20l-5.447-2.724A2 2 0 013 15.382V6.618a2 2 0 011.553-1.894L9 2m0 18l6-3m-6 3V2m6 15l5.447 2.724A2 2 0 0021 17.382V8.618a2 2 0 00-1.553-1.894L15 4m0 13V4m0 0L9 2" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-slate-900">Análise</h3>
                    <p class="mt-2 text-sm text-slate-600">Estatísticas por província/município, doença e período.</p>
                </div>

                <div class="fade-in bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="h-12 w-12 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-700 font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="mt-4 font-semibold text-slate-900">Resposta</h3>
                    <p class="mt-2 text-sm text-slate-600">Relatórios e rastreabilidade para suporte à decisão.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Funcionalidades --}}
    <section id="funcionalidades" class="bg-white">
        <div class="max-w-6xl mx-auto px-4 py-14 md:py-20">
            <div class="text-center">
                <h2 class="fade-in text-3xl md:text-4xl font-bold text-slate-900">Principais Funcionalidades</h2>
                <p class="fade-in mt-4 text-slate-600">Módulos centrais do AngoMoni.</p>
            </div>

            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $cards = [
                        ['title' => 'Autenticação & RBAC', 'desc' => 'Papéis, middleware e políticas para controlo de acesso.'],
                        ['title' => 'Gestão de Doenças', 'desc' => 'CRUD completo de doenças e classificação institucional.'],
                        ['title' => 'Unidades de Saúde', 'desc' => 'Registo e gestão de unidades, província e município.'],
                        ['title' => 'Registo de Casos', 'desc' => 'Casos com snapshot de localização e vínculo institucional.'],
                        ['title' => 'Mapa Institucional', 'desc' => 'Filtros por província, município, doença e período.'],
                        ['title' => 'Auditoria', 'desc' => 'Registo de acessos e ações para rastreabilidade.'],
                    ];
                @endphp

                @foreach($cards as $c)
                    <div class="fade-in bg-slate-50 border border-slate-200 rounded-2xl p-6">
                        <h3 class="font-semibold text-slate-900">{{ $c['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $c['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="fade-in mt-10 text-center">
                @if($isAuth)
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-teal-700 px-6 py-3 text-white font-semibold hover:bg-teal-800 transition">
                        Abrir dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-teal-700 px-6 py-3 text-white font-semibold hover:bg-teal-800 transition">
                        Entrar no sistema
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-white border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 py-10 text-center">
            <div class="flex items-center justify-center gap-3">
                <img src="{{ asset('images/angomoni-logo.png') }}" alt="AngoMoni" class="h-9 w-auto">
                <span class="text-base font-semibold text-slate-900">AngoMoni</span>
            </div>

            <p class="mt-3 text-sm text-slate-600">
                Projeto académico — Sistema institucional de apoio à monitorização e controlo de doenças endémicas.
            </p>

            <p class="mt-3 text-xs text-slate-500">
                © {{ date('Y') }} • Desenvolvido por {{ config('app.owner_name', 'Agostinho Sachi') }}
            </p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        btn?.addEventListener('click', () => menu.classList.toggle('hidden'));

        // Close menu on click (mobile)
        document.querySelectorAll('#mobile-menu a[href^="#"]').forEach(a => {
            a.addEventListener('click', () => menu.classList.add('hidden'));
        });

        // Fade-in on scroll
        const fadeElements = document.querySelectorAll('.fade-in');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('visible');
            });
        }, { threshold: 0.1 });
        fadeElements.forEach(el => observer.observe(el));
    </script>
</x-guest-layout>
