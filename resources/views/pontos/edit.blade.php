<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Ponto Turístico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('pontos.update', $ponto) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nome -->
                        <div>
                            <x-input-label for="nome" :value="__('Nome do Ponto Turístico')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" 
                                :value="old('nome', $ponto->nome)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>

                        <!-- Descrição -->
                        <div>
                            <x-input-label for="descricao" :value="__('Descrição')" />
                            <textarea id="descricao" name="descricao" rows="4" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>{{ old('descricao', $ponto->descricao) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
                        </div>

                        <!-- Localização -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="cidade" :value="__('Cidade')" />
                                <x-text-input id="cidade" name="cidade" type="text" class="mt-1 block w-full" 
                                    :value="old('cidade', $ponto->cidade)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('cidade')" />
                            </div>

                            <div>
                                <x-input-label for="estado" :value="__('Estado (UF)')" />
                                <x-text-input id="estado" name="estado" type="text" class="mt-1 block w-full" 
                                    :value="old('estado', $ponto->estado)" maxlength="2" required />
                                <x-input-error class="mt-2" :messages="$errors->get('estado')" />
                            </div>

                            <div>
                                <x-input-label for="pais" :value="__('País')" />
                                <x-text-input id="pais" name="pais" type="text" class="mt-1 block w-full" 
                                    :value="old('pais', $ponto->pais)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('pais')" />
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div>
                            <x-input-label for="endereco" :value="__('Endereço Completo')" />
                            <x-text-input id="endereco" name="endereco" type="text" class="mt-1 block w-full" 
                                :value="old('endereco', $ponto->endereco)" />
                            <x-input-error class="mt-2" :messages="$errors->get('endereco')" />
                        </div>

                        <!-- Coordenadas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="latitude" :value="__('Latitude')" />
                                <x-text-input id="latitude" name="latitude" type="number" step="0.0000001" 
                                    class="mt-1 block w-full" :value="old('latitude', $ponto->latitude)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('latitude')" />
                            </div>

                            <div>
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" name="longitude" type="number" step="0.0000001" 
                                    class="mt-1 block w-full" :value="old('longitude', $ponto->longitude)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('longitude')" />
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex items-center justify-between">
                            <div class="flex gap-4">
                                <x-primary-button>{{ __('Salvar Alterações') }}</x-primary-button>
                                <a href="{{ route('pontos.show', $ponto) }}" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Cancelar') }}
                                </a>
                            </div>

                            @can('delete', $ponto)
                                <form method="POST" action="{{ route('pontos.destroy', $ponto) }}" onsubmit="return confirm('Tem certeza que deseja deletar este ponto turístico?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button type="submit">
                                        {{ __('Deletar Ponto') }}
                                    </x-danger-button>
                                </form>
                            @endcan
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
