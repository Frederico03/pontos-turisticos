<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Avaliar Ponto Turístico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Informações do Ponto -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">{{ $ponto->nome }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $ponto->cidade }}, {{ $ponto->estado }}
                    </p>
                    <div class="flex items-center mt-2">
                        @if($ponto->nota_media > 0)
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $ponto->nota_media ? 'text-yellow-400' : 'text-gray-300' }}"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        @endif
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                            ({{ $ponto->avaliacoes->count() }} {{ $ponto->avaliacoes->count() == 1 ? 'avaliação' : 'avaliações' }})
                        </span>
                    </div>
                </div>
            </div>

            <!-- Formulário de Avaliação -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Sua Avaliação</h3>

                    <form method="POST" action="{{ route('avaliacoes.store', $ponto) }}" class="space-y-6">
                        @csrf

                        <!-- Nota -->
                        <div>
                            <x-input-label for="nota" value="Nota *" />
                            <div class="mt-2">
                                <x-rating-input name="nota" :value="old('nota', 0)" />
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('nota')" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Clique nas estrelas para dar sua nota (1 a 5 estrelas)
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
                            >{{ old('comentario') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('comentario')" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Máximo de 1000 caracteres
                            </p>
                        </div>
                        <input type="hidden" id="ponto_id" name="ponto_id" value="{{ $ponto->id }}" />
                        <!-- Botões -->
                        <div class="flex items-center gap-4">
                            <x-primary-button>
                                {{ __('Publicar Avaliação') }}
                            </x-primary-button>
                            <a href="{{ route('pontos.show', $ponto) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Dica -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <strong>Dica:</strong> Avaliações honestas ajudam outros viajantes a tomar melhores decisões. Você poderá editar ou excluir sua avaliação posteriormente.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
