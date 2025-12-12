<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Avaliação') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Informações do Ponto -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">{{ $avaliacao->ponto->nome }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $avaliacao->ponto->cidade }}, {{ $avaliacao->ponto->estado }}
                    </p>
                </div>
            </div>

            <!-- Formulário de Edição -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Editar Sua Avaliação</h3>

                    <form method="POST" action="{{ route('avaliacoes.update', $avaliacao) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nota -->
                        <div>
                            <x-input-label for="nota" value="Nota *" />
                            <div class="mt-2">
                                <x-rating-input name="nota" :value="old('nota', $avaliacao->nota)" />
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('nota')" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Clique nas estrelas para alterar sua nota (1 a 5 estrelas)
                            </p>
                        </div>

                        <!-- Comentário -->
                        <div>
                            <x-input-label for="comentario" value="Comentário (opcional)" />
                            <textarea 
                                id="comentario" 
                                name="comentario" 
                                rows="4" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                placeholder="Compartilhe sua experiência sobre este local..."
                            >{{ old('comentario', $avaliacao->comentario) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('comentario')" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Máximo de 1000 caracteres
                            </p>
                        </div>

                        <!-- Info sobre última edição -->
                        @if($avaliacao->created_at != $avaliacao->updated_at)
                            <div class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 p-3 rounded">
                                <p>Última edição: {{ $avaliacao->updated_at->format('d/m/Y \à\s H:i') }}</p>
                            </div>
                        @endif

                        <!-- Botões -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Atualizar Avaliação') }}
                            </x-primary-button>
                            <a href="{{ route('pontos.show', $avaliacao->ponto) }}" 
                                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>

                    <!-- Excluir Avaliação -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            Excluir Avaliação
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Depois de excluir sua avaliação, ela não poderá ser recuperada.
                        </p>
                        <form method="POST" action="{{ route('avaliacoes.destroy', $avaliacao) }}" 
                            onsubmit="return confirm('Tem certeza que deseja excluir esta avaliação? Esta ação não pode ser desfeita.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Excluir Avaliação
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
