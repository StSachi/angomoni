<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                        Registar Caso
                    </h1>
                    <p class="text-sm text-slate-600">
                        Preencha os dados do caso e associe a uma doença e unidade de saúde.
                    </p>
                </div>

                <a href="{{ route('casos.index') }}"
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

            <form method="POST" action="{{ route('casos.store') }}" class="space-y-6">
                @csrf

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-6">

                    {{-- Associação --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Doença</label>
                            <select name="doenca_id"
                                    class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                                    required>
                                <option value="">Selecionar doença...</option>

                                @foreach(($doencas ?? []) as $d)
                                    <option value="{{ $d->id }}" @selected(old('doenca_id') == $d->id)>
                                        {{ $d->nome ?? $d->designacao ?? ('Doença #' . $d->id) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doenca_id')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Unidade de Saúde</label>
                            <select name="unidade_saude_id"
                                    class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                                    required>
                                <option value="">Selecionar unidade...</option>

                                @foreach(($unidades ?? []) as $u)
                                    <option value="{{ $u->id }}" @selected(old('unidade_saude_id') == $u->id)>
                                        {{ $u->nome ?? $u->designacao ?? ('Unidade #' . $u->id) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unidade_saude_id')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror

                            <p class="mt-2 text-xs text-slate-500">
                                A geolocalização do caso será obtida a partir da unidade (latitude/longitude).
                            </p>
                        </div>
                    </div>

                    {{-- Dados do caso --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Data do registo</label>
                            <input type="date" name="data_registo" value="{{ old('data_registo') }}"
                                   class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">
                            @error('data_registo')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Estado</label>
                            <select name="estado"
                                    class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">
                                @php
                                    $estadoOld = old('estado', 'ABERTO');
                                @endphp
                                <option value="ABERTO"  @selected($estadoOld === 'ABERTO')>ABERTO</option>
                                <option value="ATIVO"   @selected($estadoOld === 'ATIVO')>ATIVO</option>
                                <option value="ENCERRADO" @selected($estadoOld === 'ENCERRADO')>ENCERRADO</option>
                            </select>
                            @error('estado')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Observação --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Observações</label>
                        <textarea name="observacoes" rows="4"
                                  placeholder="Notas clínicas, contexto do caso, informações relevantes..."
                                  class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">{{ old('observacoes') }}</textarea>
                        @error('observacoes')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nota --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-sm text-slate-600">
                        Registe apenas os dados necessários. Informações sensíveis devem seguir o padrão de mascaramento e auditoria do sistema.
                    </div>

                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('casos.index') }}"
                       class="rounded-xl border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="rounded-xl bg-teal-600 px-5 py-2 text-sm font-semibold text-white hover:bg-teal-700 transition">
                        Guardar caso
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
