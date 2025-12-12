<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $hospedagem->nome }}
            </h2>
            
            @auth
                @can('update', $hospedagem)
                    <div class="flex gap-2">
                        <a href="{{ route('hospedagens.edit', $hospedagem) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Editar
                        </a>
                        
                        <form method="POST" action="{{ route('hospedagens.destroy', $hospedagem) }}" 
                              onsubmit="return confirm('Tem certeza que deseja excluir esta hospedagem?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Excluir
                            </button>
                        </form>
                    </div>
                @endcan
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Informações Principais -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Informações da Hospedagem -->
                        <div class="md:col-span-2">
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 capitalize">
                                    {{ $hospedagem->tipo }}
                                </span>
                            </div>

                            <h3 class="text-lg font-semibold mb-4">Sobre</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-6">
                                {{ $hospedagem->descricao ?? 'Sem descrição disponível.' }}
                            </p>

                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Endereço
                                    </dt>
                                    <dd class="ml-7 text-gray-900 dark:text-gray-100">{{ $hospedagem->endereco }}</dd>
                                </div>

                                @if($hospedagem->telefone)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            Telefone
                                        </dt>
                                        <dd class="ml-7 text-gray-900 dark:text-gray-100">
                                            <a href="tel:{{ $hospedagem->telefone }}" class="text-blue-600 hover:text-blue-800">
                                                {{ $hospedagem->telefone }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif

                                @if($hospedagem->site)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                            Website
                                        </dt>
                                        <dd class="ml-7 text-gray-900 dark:text-gray-100">
                                            <a href="{{ $hospedagem->site }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                {{ $hospedagem->site }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif

                                @if($hospedagem->nota_avaliacao)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            Avaliação
                                        </dt>
                                        <dd class="ml-7 text-gray-900 dark:text-gray-100">
                                            <span class="font-medium">{{ number_format($hospedagem->nota_avaliacao, 1) }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">/5.0</span>
                                        </dd>
                                    </div>
                                @endif
                            </div>

                            <!-- Amenidades -->
                            @if($hospedagem->amenidades && count($hospedagem->amenidades) > 0)
                                <div class="mt-6">
                                    <h4 class="text-md font-semibold mb-3">Comodidades</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($hospedagem->amenidades as $amenidade)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ $amenidade }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Card de Preço -->
                        <div>
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 sticky top-4">
                                <div class="text-center mb-4">
                                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                        R$ {{ number_format($hospedagem->preco_diaria, 2, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">por noite</p>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                                    <h5 class="text-sm font-semibold mb-2 text-gray-700 dark:text-gray-300">Informações de Reserva</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Entre em contato diretamente com a hospedagem para fazer sua reserva.
                                    </p>
                                    
                                    @if($hospedagem->telefone)
                                        <a href="tel:{{ $hospedagem->telefone }}"
                                           class="mt-4 w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            Ligar
                                        </a>
                                    @endif
                                    
                                    @if($hospedagem->site)
                                        <a href="{{ $hospedagem->site }}" target="_blank"
                                           class="mt-2 w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                            Visitar Site
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ponto Turístico Associado -->
            @if($hospedagem->ponto)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Ponto Turístico Próximo</h3>
                        
                        <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-semibold text-lg">{{ $hospedagem->ponto->nome }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $hospedagem->ponto->cidade }}, {{ $hospedagem->ponto->estado }}
                                </p>
                                
                                @if($hospedagem->ponto->nota_media > 0)
                                    <div class="flex items-center mt-2">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="ml-1 text-sm font-medium">{{ number_format($hospedagem->ponto->nota_media, 1) }}</span>
                                    </div>
                                @endif
                                
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">
                                    {{ $hospedagem->ponto->descricao }}
                                </p>
                            </div>
                            
                            <a href="{{ route('pontos.show', $hospedagem->ponto) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Ver Ponto
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Mapa -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Localização</h3>
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg h-96 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">{{ $hospedagem->endereco }}</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Integração de mapa pode ser adicionada aqui</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
