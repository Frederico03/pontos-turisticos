<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Hospedagens em {{ $ponto->nome }}
            </h2>
            <a href="{{ route('pontos.show', $ponto) }}" 
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ← Voltar ao Ponto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Informações do Ponto -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $ponto->nome }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $ponto->cidade }}, {{ $ponto->estado }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $hospedagens->total() }} {{ $hospedagens->total() == 1 ? 'hospedagem' : 'hospedagens' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Hospedagens -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Opções de Hospedagem</h3>
                @auth
                    <a href="{{ route('hospedagens.create', $ponto) }}" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        + Adicionar Hospedagem
                    </a>
                @endauth
            </div>

            @if($hospedagens->count() > 0)
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($hospedagens as $hospedagem)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $hospedagem->nome }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 capitalize mt-1">
                                            {{ $hospedagem->tipo }}
                                        </p>
                                    </div>
                                    @if($hospedagem->nota_avaliacao)
                                        <div class="ml-2 flex items-center">
                                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ number_format($hospedagem->nota_avaliacao, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                @if($hospedagem->descricao)
                                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                        {{ $hospedagem->descricao }}
                                    </p>
                                @endif

                                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $hospedagem->endereco }}
                                </p>

                                @if($hospedagem->amenidades && count($hospedagem->amenidades) > 0)
                                    <div class="mt-3 flex flex-wrap gap-1">
                                        @foreach(array_slice($hospedagem->amenidades, 0, 3) as $amenidade)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $amenidade }}
                                            </span>
                                        @endforeach
                                        @if(count($hospedagem->amenidades) > 3)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                +{{ count($hospedagem->amenidades) - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                    <div>
                                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                            R$ {{ number_format($hospedagem->preco_diaria, 2, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">por noite</p>
                                    </div>
                                    <div class="flex gap-2">
                                        @auth
                                            @if(auth()->id() === $hospedagem->criado_por)
                                                <a href="{{ route('hospedagens.edit', $hospedagem) }}" 
                                                    class="inline-flex items-center px-3 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Editar
                                                </a>
                                            @endif
                                        @endauth
                                        <a href="{{ route('hospedagens.show', $hospedagem) }}" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Ver
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="mt-6">
                    {{ $hospedagens->links() }}
                </div>
            @else
                <div class="text-center py-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                        Nenhuma hospedagem cadastrada para este ponto turístico.
                    </p>
                    @auth
                        <div class="mt-4">
                            <a href="{{ route('hospedagens.create', $ponto) }}" 
                                class="text-blue-600 hover:text-blue-800 font-medium">
                                Adicionar a primeira hospedagem
                            </a>
                        </div>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
