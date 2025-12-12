<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $ponto->nome }}
            </h2>
            
            @auth
                <div class="flex gap-2">
                    <!-- Favoritar -->
                    <form method="POST" action="{{ route('pontos.favoritar', $ponto) }}">
                        @csrf
                        <button type="submit" 
                            class="inline-flex items-center px-4 py-2 {{ $isFavorited ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-600 hover:bg-gray-700' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            {{ $isFavorited ? 'Favoritado' : 'Favoritar' }}
                        </button>
                    </form>

                    @can('update', $ponto)
                        <a href="{{ route('pontos.edit', $ponto) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Editar
                        </a>
                    @endcan
                </div>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Imagens do Ponto -->
            @php
                $imagens = app(\App\Services\FotoService::class)->listarPorPonto($ponto->id);
            @endphp
            
            @if(count($imagens) > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($imagens as $imagem)
                                <div class="relative group">
                                    <img src="{{ $imagem['path'] }}" 
                                         alt="{{ $imagem['titulo'] }}"
                                         class="w-full h-48 object-cover rounded-lg">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity rounded-lg flex items-center justify-center">
                                        @auth
                                            @if(auth()->id() == $ponto->criado_por || auth()->id() == $imagem['usuarioId'])
                                                <form method="POST" action="{{ route('imagens.destroy', [$ponto, $imagem['id']]) }}" 
                                                      class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Deseja remover esta imagem?')"
                                                            class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                                        Remover
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $imagem['titulo'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        @auth
                            @if(count($imagens) < 10)
                                <div class="mt-4">
                                    <form method="POST" action="{{ route('imagens.store', $ponto) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="flex items-center gap-4">
                                            <input type="file" name="imagens[]" multiple accept="image/*" max="5"
                                                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                            <button type="submit" 
                                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 whitespace-nowrap">
                                                Upload
                                            </button>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Você pode enviar até 5 imagens por vez (máx 10 total). Formatos: JPEG, PNG, WebP
                                        </p>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            @else
                @auth
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Adicionar Imagens</h3>
                            <form method="POST" action="{{ route('imagens.store', $ponto) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="flex items-center gap-4">
                                    <input type="file" name="imagens[]" multiple accept="image/*" max="5"
                                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 whitespace-nowrap">
                                        Upload
                                    </button>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Você pode enviar até 5 imagens por vez. Formatos: JPEG, PNG, WebP
                                </p>
                            </form>
                        </div>
                    </div>
                @endauth
            @endif

            <!-- Informações Principais -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informações</h3>
                            
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</dt>
                                    <dd class="mt-1">{{ $ponto->descricao }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Localização</dt>
                                    <dd class="mt-1">{{ $ponto->cidade }}, {{ $ponto->estado }} - {{ $ponto->pais }}</dd>
                                </div>

                                @if($ponto->endereco)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Endereço</dt>
                                        <dd class="mt-1">{{ $ponto->endereco }}</dd>
                                    </div>
                                @endif

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Coordenadas</dt>
                                    <dd class="mt-1">{{ $ponto->latitude }}, {{ $ponto->longitude }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Avaliação</dt>
                                    <dd class="mt-1 flex items-center">
                                        @if($ponto->nota_media > 0)
                                            <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="font-medium">{{ number_format($ponto->nota_media, 1) }}</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">
                                                ({{ $ponto->avaliacoes->count() }} {{ $ponto->avaliacoes->count() == 1 ? 'avaliação' : 'avaliações' }})
                                            </span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">Sem avaliações</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Mapa</h3>
                            <div class="bg-gray-200 dark:bg-gray-700 rounded-lg h-64 flex items-center justify-center">
                                <p class="text-gray-500 dark:text-gray-400">Integração de mapa pode ser adicionada aqui</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avaliações -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Avaliações ({{ $ponto->avaliacoes->count() }})</h3>
                        @auth
                            <a href="{{ route('avaliacoes.create', $ponto) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Avaliar
                            </a>
                        @endauth
                    </div>

                    @if($ponto->avaliacoes->count() > 0)
                        <div class="space-y-4">
                            @foreach($ponto->avaliacoes->take(5) as $avaliacao)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center">
                                                <p class="font-medium">{{ $avaliacao->usuario->name }}</p>
                                                <div class="ml-3 flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $avaliacao->nota ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                             fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $avaliacao->created_at->diffForHumans() }}
                                            </p>
                                            @if($avaliacao->comentario)
                                                <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $avaliacao->comentario }}</p>
                                            @endif
                                        </div>
                                        @auth
                                            @can('update', $avaliacao)
                                                <a href="{{ route('avaliacoes.edit', $avaliacao) }}" 
                                                   class="ml-4 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                    Editar
                                                </a>
                                            @endcan
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($ponto->avaliacoes->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('avaliacoes.index', $ponto) }}" 
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    Ver todas as avaliações →
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                            Nenhuma avaliação ainda. Seja o primeiro a avaliar!
                        </p>
                    @endif
                </div>
            </div>

            <!-- Hospedagens Próximas -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Hospedagens Próximas ({{ $ponto->hospedagens->count() }})</h3>
                        @auth
                            <a href="{{ route('hospedagens.create', $ponto) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cadastrar Hospedagem
                            </a>
                        @endauth
                    </div>
                    
                    @if($ponto->hospedagens->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($ponto->hospedagens as $hospedagem)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <h4 class="font-semibold">{{ $hospedagem->nome }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $hospedagem->tipo }}</p>
                                    
                                    @if($hospedagem->nota_avaliacao)
                                        <div class="flex items-center mt-2">
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span class="ml-1 text-sm">{{ number_format($hospedagem->nota_avaliacao, 1) }}</span>
                                        </div>
                                    @endif

                                    <p class="text-lg font-bold text-blue-600 mt-2">
                                        R$ {{ number_format($hospedagem->preco_diaria, 2, ',', '.') }}/noite
                                    </p>
                                    <a href="{{ route('hospedagens.show', $hospedagem) }}" 
                                        class="text-sm text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                        Ver detalhes →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                            Nenhuma hospedagem cadastrada ainda.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
