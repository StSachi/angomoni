<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Registar Caso</h1>
                    <p class="text-sm text-slate-600">
                        Preencha os dados do caso e associe a um paciente, doença e unidade de saúde (conforme permissões).
                    </p>
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

            <form method="POST" action="{{ route('casos.store') }}"
                  class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
                @csrf

                {{-- Paciente --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900">Paciente</label>
                    <select name="paciente_id" required
                            class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-700 focus:ring-teal-600">
                        <option value="">-- Selecione --</option>
                        @foreach($pacientes as $p)
                            <option value="{{ $p->id }}" @selected(old('paciente_id') == $p->id)>
                                {{ $p->nome }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-slate-500">
                        Dados pessoais do paciente são geridos no módulo Pacientes. Evite duplicar dados aqui.
                    </p>
                </div>

                {{-- Doença --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900">Doença</label>
                    <select name="doenca_id" required
                            class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-700 focus:ring-teal-600">
                        <option value="">-- Selecione --</option>
                        @foreach($doencas as $d)
                            <option value="{{ $d->id }}" @selected(old('doenca_id') == $d->id)>
                                {{ $d->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Unidade de Saúde (Registo / Origem) --}}
                @if(($papel ?? null) === 'ADMIN')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Unidade de Registo --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Unidade de Registo</label>
                            <select name="unidade_registo_id" required
                                    class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-700 focus:ring-teal-600">
                                <option value="">-- Selecione --</option>
                                @foreach($unidades as $u)
                                    <option value="{{ $u->id }}" @selected(old('unidade_registo_id') == $u->id)>
                                        {{ $u->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-slate-500">
                                A localização institucional do caso será derivada da unidade de registo (província/município).
                            </p>
                        </div>

                        {{-- Unidade de Origem (opcional) --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Unidade de Origem (opcional)</label>
                            <select name="unidade_origem_id"
                                    class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-700 focus:ring-teal-600">
                                <option value="">— Igual à unidade de registo —</option>
                                @foreach($unidades as $u)
                                    <option value="{{ $u->id }}" @selected(old('unidade_origem_id') == $u->id)>
                                        {{ $u->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-slate-500">
                                Use quando o caso foi referenciado de outra unidade.
                            </p>
                        </div>
                    </div>
                @else
                    {{-- PROFISSIONAL: unidade é fixa (só informativo) --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-900">Unidade de Registo</div>
                        <div class="mt-1 text-sm text-slate-600">
                            A unidade é definida automaticamente com base na sua conta.
                        </div>
                        <div class="mt-2 text-xs text-slate-500">
                            Regra institucional: profissionais só podem registar casos na sua própria unidade.
                        </div>
                    </div>
                @endif

                {{-- Datas / classificação --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Data da notificação</label>
                        <input type="date" name="data_notificacao" required
                               value="{{ old('data_notificacao', now()->toDateString()) }}"
                               class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-700 focus:ring-teal-600" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Data de início dos sintomas (opcional)</label>
                        <input type="date" name="data_inicio_sintomas"
                               value="{{ old('data_inicio_sintomas') }}"
                               class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-700 focus:ring-teal-600" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Classificação do caso</label>
                        <input type="text" name="classificacao_caso" maxlength="100"
                               value="{{ old('classificacao_caso') }}"
                               placeholder="Ex: SUSPEITO / PROVÁVEL / CONFIRMADO"
                               class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-700 focus:ring-teal-600" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Tipo de detecção</label>
                        <input type="text" name="tipo_deteccao" maxlength="100"
                               value="{{ old('tipo_deteccao') }}"
                               placeholder="Ex: TRIAGEM / LAB"
                               class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-700 focus:ring-teal-600" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Fonte da notificação</label>
                        <input type="text" name="fonte_notificacao" maxlength="100"
                               value="{{ old('fonte_notificacao') }}"
                               placeholder="Ex: HOSPITAL / COMUNIDADE"
                               class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-700 focus:ring-teal-600" />
                    </div>
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900">Estado</label>
                    <select name="estado" required
                            class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-700 focus:ring-teal-600">
                        <option value="">-- Selecione --</option>
                        <option value="SUSPEITO" @selected(old('estado') === 'SUSPEITO')>Suspeito</option>
                        <option value="CONFIRMADO" @selected(old('estado') === 'CONFIRMADO')>Confirmado</option>
                        <option value="DESCARTADO" @selected(old('estado') === 'DESCARTADO')>Descartado</option>
                    </select>
                </div>

                {{-- Parecer técnico --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-900">Parecer técnico (opcional)</label>
                    <textarea name="parecer_tecnico" rows="4"
                              class="mt-2 w-full rounded-xl border-slate-300 focus:border-teal-700 focus:ring-teal-600"
                              placeholder="Notas técnicas (evitar dados sensíveis desnecessários)">{{ old('parecer_tecnico') }}</textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('casos.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800 transition">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
