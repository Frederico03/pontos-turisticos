<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Adicionar Hospedagem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Informações do Ponto -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Adicionando hospedagem para: {{ $ponto->nome }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $ponto->cidade }}, {{ $ponto->estado }}
                    </p>
                </div>
            </div>

            <!-- Formulário -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('hospedagens.store', $ponto) }}" class="space-y-6">
                        @csrf

                        <!-- Nome -->
                        <div>
                            <x-input-label for="nome" value="Nome da Hospedagem *" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>

                        <!-- Tipo -->
                        <div>
                            <x-input-label for="tipo" value="Tipo *" />
                            <select id="tipo" name="tipo" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Selecione um tipo</option>
                                <option value="hotel" {{ old('tipo') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                                <option value="pousada" {{ old('tipo') == 'pousada' ? 'selected' : '' }}>Pousada</option>
                                <option value="hostel" {{ old('tipo') == 'hostel' ? 'selected' : '' }}>Hostel</option>
                                <option value="resort" {{ old('tipo') == 'resort' ? 'selected' : '' }}>Resort</option>
                                <option value="apartamento" {{ old('tipo') == 'apartamento' ? 'selected' : '' }}>Apartamento</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('tipo')" />
                        </div>

                        <!-- Descrição -->
                        <div>
                            <x-input-label for="descricao" value="Descrição" />
                            <textarea id="descricao" name="descricao" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('descricao') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
                        </div>

                        <!-- Endereço -->
                        <div>
                            <x-input-label for="endereco" value="Endereço *" />
                            <x-text-input id="endereco" name="endereco" type="text" class="mt-1 block w-full" :value="old('endereco')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('endereco')" />
                        </div>

                        <!-- Preço Diária -->
                        <div>
                            <x-input-label for="preco_diaria" value="Preço da Diária (R$) *" />
                            <x-text-input id="preco_diaria" name="preco_diaria" type="number" step="0.01" class="mt-1 block w-full" :value="old('preco_diaria')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('preco_diaria')" />
                        </div>

                        <!-- Contato -->
                        <div>
                            <x-input-label for="contato" value="Contato (Telefone/Email)" />
                            <x-text-input id="contato" name="contato" type="text" class="mt-1 block w-full" :value="old('contato')" />
                            <x-input-error class="mt-2" :messages="$errors->get('contato')" />
                        </div>

                        <!-- Site -->
                        <div>
                            <x-input-label for="link_reserva" value="Link de Reserva" />
                            <x-text-input id="link_reserva" name="link_reserva" type="url" class="mt-1 block w-full" :value="old('link_reserva')" placeholder="https://..." />
                            <x-input-error class="mt-2" :messages="$errors->get('link_reserva')" />
                        </div>

                        <input type="hidden" name="ponto_id" value="{{ $ponto->id }}">  
                        <input type="hidden" name="criado_por" value="{{ Auth::user()->id }}">

                        <!-- Amenidades -->
                        {{-- <div>
                            <x-input-label value="Amenidades" />
                            <div class="mt-2 grid grid-cols-2 gap-4">
                                @foreach(['Wi-Fi', 'Café da Manhã', 'Piscina', 'Estacionamento', 'Ar Condicionado', 'Academia', 'Pet Friendly', 'Restaurante'] as $amenidade)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="amenidades[]" value="{{ $amenidade }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ in_array($amenidade, old('amenidades', [])) ? 'checked' : '' }}>
                                        <span class="ml-2 text-gray-600 dark:text-gray-400">{{ $amenidade }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('amenidades')" />
                        </div> --}}

                        <!-- Botões -->
                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>
                                {{ __('Salvar Hospedagem') }}
                            </x-primary-button>
                            <a href="{{ route('pontos.show', $ponto) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
