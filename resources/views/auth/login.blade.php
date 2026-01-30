<x-guest-layout>
    <div class="bg-white border rounded-2xl shadow-sm p-6 sm:p-8">
        <div class="space-y-2">
            <h1 class="text-2xl font-semibold tracking-tight">Entrar</h1>
            <p class="text-sm text-slate-600">
                Acesso restrito ao sistema de monitorização epidemiológica.
            </p>
        </div>

        <x-auth-session-status class="mt-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700">Email</label>
                <input name="email" type="email" required autofocus
                       class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-400" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Palavra-passe</label>
                <input name="password" type="password" required
                       class="mt-1 w-full rounded-xl border-slate-200 focus:border-slate-400 focus:ring-slate-400" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <button class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                Entrar
            </button>

            <div class="pt-3 border-t text-xs text-slate-500">
                Uso restrito. As ações no sistema podem ser registadas para auditoria.
            </div>
        </form>
    </div>
</x-guest-layout>
