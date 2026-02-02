<x-guest-layout>
    <div class="min-h-[80vh] flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            {{-- Cabeçalho --}}
            <div class="text-center mb-6">
                <div class="flex items-center justify-center gap-3">
                    <img src="{{ asset('images/angomoni-logo.png') }}" alt="AngoMoni" class="h-12 w-auto">
                </div>
                <h1 class="mt-4 text-2xl font-semibold tracking-tight text-slate-900">
                    Entrar no AngoMoni
                </h1>
                <p class="mt-1 text-sm text-slate-600">
                    Acesso institucional. Use as suas credenciais autorizadas.
                </p>
            </div>

            {{-- Card --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-7">
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="ex: profissional@hospital.ao"
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                        >
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-slate-700">Palavra-passe</label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="text-sm text-teal-700 hover:text-teal-800 font-medium">
                                    Esqueci a palavra-passe
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
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                        >
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="rounded border-slate-300 text-teal-600 shadow-sm focus:ring-teal-500"
                            >
                            <span class="ms-2 text-sm text-slate-700">Manter sessão iniciada</span>
                        </label>
                    </div>

                    {{-- Botão --}}
                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition"
                    >
                        Entrar
                    </button>

                    {{-- Rodapé do card --}}
                    <div class="pt-4 border-t border-slate-200 text-xs text-slate-500 leading-relaxed">
                        Uso restrito a entidades autorizadas. As ações no sistema podem ser registadas para auditoria.
                    </div>
                </form>
            </div>

            <div class="mt-4 text-center text-xs text-slate-500">
                Problemas de acesso? Contacte o administrador do sistema.
            </div>
        </div>
    </div>
</x-guest-layout>
