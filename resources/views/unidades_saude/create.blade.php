<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">
                    Nova Unidade de Saúde
                </h1>
                <p class="text-sm text-slate-600">
                    Preencha os dados institucionais e, se possível, a localização geográfica.
                </p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('unidades-saude.store') }}" class="space-y-6">
                @csrf

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-6">

                    {{-- Nome --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Nome da unidade
                        </label>
                        <input
                            name="nome"
                            value="{{ old('nome') }}"
                            required
                            placeholder="Ex: Hospital Municipal do Lubango"
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                        >
                        @error('nome')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Código --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Código (opcional)
                        </label>
                        <input
                            name="codigo"
                            value="{{ old('codigo') }}"
                            placeholder="Ex: HML-001"
                            class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                        >
                        @error('codigo')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Localização administrativa --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Província
                            </label>
                            <input
                                name="provincia"
                                value="{{ old('provincia') }}"
                                placeholder="Ex: Huíla"
                                class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                            >
                            @error('provincia')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Município
                            </label>
                            <input
                                name="municipio"
                                value="{{ old('municipio') }}"
                                placeholder="Ex: Lubango"
                                class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                            >
                            @error('municipio')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Geolocalização --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Latitude
                            </label>
                            <input
                                name="latitude"
                                value="{{ old('latitude') }}"
                                placeholder="-14.9173"
                                class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                            >
                            @error('latitude')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Longitude
                            </label>
                            <input
                                name="longitude"
                                value="{{ old('longitude') }}"
                                placeholder="13.4925"
                                class="mt-1 w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500"
                            >
                            @error('longitude')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Nota --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-4 text-sm text-slate-600">
                        A latitude e longitude permitem visualizar a unidade no mapa de casos e surtos.
                        Caso não saiba os valores, pode adicioná-los mais tarde.
                    </div>

                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('unidades-saude.index') }}"
                       class="rounded-xl border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="rounded-xl bg-teal-600 px-5 py-2 text-sm font-semibold text-white hover:bg-teal-700 transition">
                        Guardar unidade
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
