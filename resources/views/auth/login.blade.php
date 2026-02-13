<x-guest-layout>
    <div class="min-h-screen flex">

        {{-- Lado Institucional --}}
        <div class="hidden lg:flex lg:w-1/2 bg-teal-700 text-white p-12 flex-col justify-between">
            
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/angomoni-logo.png') }}" 
                         alt="AngoMoni" 
                         class="h-14 w-auto">
                    <span class="text-2xl font-bold tracking-wide">
                        AngoMoni
                    </span>
                </div>

                <h2 class="mt-16 text-4xl font-bold leading-tight">
                    Monitorização e Controlo<br>
                    de Doenças Endémicas
                </h2>

                <p class="mt-6 text-teal-100 text-lg leading-relaxed max-w-md">
                    Plataforma institucional para registo, análise estatística 
                    e apoio à decisão em saúde pública.
                </p>

                <div class="mt-10 space-y-3 text-sm text-teal-100">
                    <div>• Acesso baseado em papéis (RBAC)</div>
                    <div>• Auditoria de ações</div>
                    <div>• Proteção de dados sensíveis</div>
                    <div>• Relatórios institucionais</div>
                </div>
            </div>

            <div class="text-xs text-teal-200">
                © {{ date('Y') }} AngoMoni • Sistema Institucional
            </div>
        </div>

        {{-- Lado Login --}}
        <div class="flex flex-1 items-center justify-center px-6 py-12 bg-slate-50">
            <div class="w-full max-w-md">

                {{-- Cabeçalho Mobile --}}
                <div class="lg:hidden text-center mb-8">
                    <img src="{{ asset('images/angomoni-logo.png') }}" 
                         alt="AngoMoni" 
                         class="h-12 mx-auto">
                    <h1 class="mt-4 text-2xl font-semibold text-slate-900">
                        Entrar no AngoMoni
                    </h1>
                </div>

                {{-- Card --}}
                <div class="bg-white border border-slate-200 rounded-2xl shadow-lg p-8">

                    <h1 class="hidden lg:block text-2xl font-semibold text-slate-900 mb-2">
                        Acesso Institucional
                    </h1>

                    <p class="hidden lg:block text-sm text-slate-600 mb-6">
                        Utilize as suas credenciais autorizadas.
                    </p>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">
                                Email Institucional
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="profissional@hospital.ao"
                                class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-teal-700 focus:ring-teal-600"
                            >
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <div class="flex items-center justify-between">
                                <label for="password" class="block text-sm font-medium text-slate-700">
                                    Palavra-passe
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                       class="text-sm text-teal-700 hover:text-teal-800 font-medium">
                                        Esqueceu?
                                    </a>
                                @endif
                            </div>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="mt-2 w-full rounded-xl border-slate-300 shadow-sm focus:border-teal-700 focus:ring-teal-600"
                            >
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        {{-- Remember --}}
                        <div class="flex items-center">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="rounded border-slate-300 text-teal-700 focus:ring-teal-600"
                            >
                            <label for="remember_me" class="ms-2 text-sm text-slate-700">
                                Manter sessão iniciada
                            </label>
                        </div>

                        {{-- Botão --}}
                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center rounded-xl bg-teal-700 px-4 py-3 text-sm font-semibold text-white hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 transition duration-200"
                        >
                            Entrar no Sistema
                        </button>

                        {{-- Rodapé --}}
                        <div class="pt-5 border-t border-slate-200 text-xs text-slate-500 leading-relaxed">
                            Uso restrito a entidades autorizadas. Todas as ações podem ser registadas para auditoria.
                        </div>
                    </form>
                </div>

                <div class="mt-6 text-center text-xs text-slate-500">
                    Problemas de acesso? Contacte o administrador do sistema.
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
