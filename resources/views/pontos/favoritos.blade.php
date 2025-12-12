<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meus Favoritos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($favoritos->count() > 0)
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 px-4 lg:px-0">
                    @foreach ($favoritos as $ponto)
                        <x-ponto-card :ponto="$ponto" />
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="mt-8 px-4 lg:px-0">
                    {{ $favoritos->links() }}
                </div>
            @else
                <!-- Estado Vazio -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center text-gray-900 dark:text-gray-100">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium">Nenhum favorito ainda</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Comece a favoritar pontos turísticos que você gostaria de visitar!
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('pontos.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Explorar Pontos Turísticos
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
