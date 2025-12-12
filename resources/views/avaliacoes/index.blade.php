<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Avaliações - {{ $ponto->nome }}
            </h2>
            <a href="{{ route('pontos.show', $ponto) }}" 
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                ← Voltar ao Ponto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                            <div class="flex items-center">
                                @if($ponto->nota_media > 0)
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $ponto->nota_media ? 'text-yellow-400' : 'text-gray-300' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    <span class="ml-2 font-medium">{{ number_format($ponto->nota_media, 1) }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $avaliacoes->total() }} {{ $avaliacoes->total() == 1 ? 'avaliação' : 'avaliações' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Avaliações -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold">Todas as Avaliações</h3>
                        @auth
                            <a href="{{ route('pontos.show', $ponto) }}#avaliar" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                + Nova Avaliação
                            </a>
                        @endauth
                    </div>

                    @if($avaliacoes->count() > 0)
                        <div class="space-y-6">
                            @foreach($avaliacoes as $avaliacao)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($avaliacao->usuario->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold">{{ $avaliacao->usuario->name }}</p>
                                                    <div class="flex items-center gap-2">
                                                        <div class="flex items-center">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg class="w-4 h-4 {{ $i <= $avaliacao->nota ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                                     fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $avaliacao->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if($avaliacao->comentario)
                                                <p class="mt-3 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $avaliacao->comentario }}
                                                </p>
                                            @endif

                                            @if($avaliacao->created_at != $avaliacao->updated_at)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 italic">
                                                    Editado {{ $avaliacao->updated_at->diffForHumans() }}
                                                </p>
                                            @endif
                                        </div>

                                        @auth
                                            @if(auth()->id() === $avaliacao->usuario_id)
                                                <div class="flex gap-2 ml-4">
                                                    <a href="{{ route('avaliacoes.edit', $avaliacao) }}" 
                                                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                        Editar
                                                    </a>
                                                    <form method="POST" action="{{ route('avaliacoes.destroy', $avaliacao) }}" 
                                                        onsubmit="return confirm('Tem certeza que deseja excluir esta avaliação?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                            class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                            Excluir
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginação -->
                        <div class="mt-6">
                            {{ $avaliacoes->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">
                                Nenhuma avaliação ainda. Seja o primeiro a avaliar!
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
