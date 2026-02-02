<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Adicionar Utilizador
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded bg-red-50 text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="mt-1 w-full rounded border-gray-300"
                            required
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="mt-1 w-full rounded border-gray-300"
                            required
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Papel</label>
                        <select name="papel" class="mt-1 w-full rounded border-gray-300" required>
                            <option value="" disabled @selected(old('papel')===null)>-- escolher --</option>
                            <option value="PROFISSIONAL" @selected(old('papel')==='PROFISSIONAL')>PROFISSIONAL</option>
                            <option value="ADMIN" @selected(old('papel')==='ADMIN')>ADMIN</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="ativo" class="mt-1 w-full rounded border-gray-300">
                            <option value="1" @selected(old('ativo','1')=='1')>Ativo</option>
                            <option value="0" @selected(old('ativo')=='0')>Inativo</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Se não escolher, por padrão fica Ativo.</p>
                    </div>

                    <div class="border-t pt-4">
                        <p class="text-sm text-gray-700 font-medium">Senha (opcional)</p>
                        <p class="text-xs text-gray-500 mb-3">
                            Se deixares vazio, o sistema cria uma senha temporária automaticamente.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Senha</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="mt-1 w-full rounded border-gray-300"
                                    autocomplete="new-password"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Confirmar senha</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="mt-1 w-full rounded border-gray-300"
                                    autocomplete="new-password"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-4 py-2 rounded bg-black text-white">
                            Criar
                        </button>

                        <a href="{{ route('users.index') }}" class="px-4 py-2 rounded border">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
