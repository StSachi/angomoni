<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'AngoMoni') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="min-h-screen grid lg:grid-cols-2">
        {{-- Lado esquerdo: Branding / contexto --}}
        <aside class="hidden lg:flex flex-col justify-between p-10 bg-white border-r">
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-slate-900"></div>
                    <div>
                        <div class="text-lg font-semibold leading-tight">AngoMoni</div>
                        <div class="text-sm text-slate-600">Monitorização de Doenças Endémicas</div>
                    </div>
                </div>

                <div class="mt-10 space-y-3">
                    <h2 class="text-2xl font-semibold tracking-tight">
                        Acesso institucional
                    </h2>
                    <p class="text-slate-600 max-w-md">
                        Plataforma para registo e vigilância epidemiológica. O acesso é controlado por perfis (ADMIN/PROFISSIONAL) e ações críticas podem ser auditadas.
                    </p>

                    <ul class="mt-6 space-y-2 text-sm text-slate-700">
                        <li class="flex gap-2">
                            <span class="mt-1 inline-block h-2 w-2 rounded-full bg-slate-900"></span>
                            Autenticação e autorização por perfil
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-1 inline-block h-2 w-2 rounded-full bg-slate-900"></span>
                            Proteção de dados sensíveis
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-1 inline-block h-2 w-2 rounded-full bg-slate-900"></span>
                            Alertas e relatórios operacionais
                        </li>
                    </ul>
                </div>
            </div>

            <div class="text-xs text-slate-500">
                <div>Aviso: uso restrito a entidades autorizadas.</div>
                <div class="mt-1">© {{ date('Y') }} AngoMoni</div>
            </div>
        </aside>

        {{-- Lado direito: formulário --}}
        <main class="flex items-center justify-center p-6">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
