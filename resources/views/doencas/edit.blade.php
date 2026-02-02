<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                        Editar Doença
                    </h1>
                    <p class="text-sm text-slate-600">
                        Atualize os dados do catálogo de doenças do sistema.
                    </p>
                </div>

                <a href="{{ route('doencas.index') }}"
                   class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    Voltar
                </a>
            </div>

            {{-- Erros globais --}}
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl p-4">
                    <div class="font-semibold">Há erros no formulário</div>
                    <ul class="mt-2 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('doencas.update', $doenca) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-6">

                    {{-- Nome --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nome</label>
                        <input
                            name="nome"
                            value="{{ old('nome', data_get($doenca, 'nome') ?? data_get($doenca, 'designacao')) }}"
                            required
                            placeholder="Ex: Malária"
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                        >
                        @error('nome')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror

                        {{-- Se o teu campo for "designacao" e não "nome", troca name="nome" por name="designacao" --}}
                    </div>

                    {{-- Código / Classificação --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Código (opcional)</label>
                            <input
                                name="codigo"
                                value="{{ old('codigo', data_get($doenca, 'codigo') ?? data_get($doenca, 'code')) }}"
                                placeholder="Ex: A00 / CID / interno"
                                class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                            >
                            @error('codigo')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tipo / Classe (opcional)</label>
                            <input
                                name="tipo"
                                value="{{ old('tipo', data_get($doenca, 'tipo') ?? data_get($doenca, 'categoria') ?? data_get($doenca, 'classe')) }}"
                                placeholder="Ex: Endémica, Viral, Bacteriana..."
                                class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                            >
                            @error('tipo')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror

                            {{-- Se no teu model for "categoria" ou "classe", troca name="tipo" --}}
                        </div>
                    </div>

                    {{-- Descrição --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Descrição (opcional)</label>
                        <textarea
                            name="descricao"
                            rows="4"
                            placeholder="Descrição breve, sinais, observações..."
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                        >{{ old('descricao', data_get($doenca,'descricao')) }}</textarea>

                        @error('descricao')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nota --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-sm text-slate-600">
                        Alterações no catálogo podem afetar relatórios e filtros de casos. Confirme antes de guardar.
                    </div>

                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('doencas.index') }}"
                       class="rounded-xl border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="rounded-xl bg-teal-600 px-5 py-2 text-sm font-semibold text-white hover:bg-teal-700 transition">
                        Guardar alterações
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
