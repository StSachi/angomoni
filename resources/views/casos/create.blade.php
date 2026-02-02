<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Registar Caso</h1>
                    <p class="text-sm text-slate-600">Preencha os dados do caso e associe a uma doença e unidade de saúde.</p>
                </div>

                <a href="{{ route('casos.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    Voltar
                </a>
            </div>

            {{-- Errors --}}
            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                    <div class="text-sm font-semibold text-red-700">Há erros no formulário</div>
                    <ul class="mt-2 list-disc pl-5 text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('casos.store') }}" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
                @csrf

                {{-- Doença --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900">Doença</label>
                    <select name="doenca_id" required
                            class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                        <option value="">-- Selecione --</option>
                        @foreach($doencas as $d)
                            <option value="{{ $d->id }}" @selected(old('doenca_id') == $d->id)>
                                {{ $d->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Unidade de Saúde --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900">Unidade de Saúde</label>
                    <select name="unidade_saude_id" required
                            class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                        <option value="">-- Selecione --</option>
                        @foreach($unidades as $u)
                            <option value="{{ $u->id }}" @selected(old('unidade_saude_id') == $u->id)>
                                {{ $u->nome }}
                            </option>
                        @endforeach
                    </select>

                    <p class="mt-2 text-xs text-slate-500">
                        A geolocalização do caso será obtida a partir da unidade (latitude/longitude).
                    </p>
                </div>

                {{-- Data de notificação --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900">Data da notificação</label>
                    <input type="date" name="data_notificacao" required
                           value="{{ old('data_notificacao', now()->toDateString()) }}"
                           class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    {{-- Idade --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Idade</label>
                        <input type="number" name="idade" min="0" max="130"
                               value="{{ old('idade') }}"
                               class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600" />
                    </div>

                    {{-- Sexo --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Sexo</label>
                        <select name="sexo" required
                                class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">-- Selecione --</option>
                            <option value="M" @selected(old('sexo') === 'M')>Masculino</option>
                            <option value="F" @selected(old('sexo') === 'F')>Feminino</option>
                        </select>
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Estado</label>
                        <select name="estado" required
                                class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            <option value="">-- Selecione --</option>
                            <option value="SUSPEITO" @selected(old('estado') === 'SUSPEITO')>Suspeito</option>
                            <option value="CONFIRMADO" @selected(old('estado') === 'CONFIRMADO')>Confirmado</option>
                            <option value="DESCARTADO" @selected(old('estado') === 'DESCARTADO')>Descartado</option>
                        </select>
                    </div>
                </div>

                {{-- Observações --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900">Observações</label>
                    <textarea name="observacoes" rows="4"
                              class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600"
                              placeholder="Notas relevantes (sem dados sensíveis desnecessários)">{{ old('observacoes') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Iniciais do paciente --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Iniciais do paciente</label>
                        <input type="text" name="paciente_iniciais" maxlength="10"
                               value="{{ old('paciente_iniciais') }}"
                               class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600" />
                        <p class="mt-2 text-xs text-slate-500">Opcional. Evite dados sensíveis completos.</p>
                    </div>

                    {{-- Telefone de contacto --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Telefone de contacto</label>
                        <input type="text" name="telefone_contacto" maxlength="30"
                               value="{{ old('telefone_contacto') }}"
                               class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600" />
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('casos.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700 transition">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
