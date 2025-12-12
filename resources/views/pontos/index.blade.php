<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Pontos Turísticos') }}
            </h2>
            @auth
                <a href="{{ route('pontos.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Adicionar Ponto
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Barra de Pesquisa Mobile --}}
            <div class="mb-6 lg:hidden px-4">
                <x-search-bar :action="route('pontos.index')" :value="request('search')" />
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                {{-- Sidebar de Filtros --}}
                <aside class="lg:sticky lg:top-6 h-fit px-4 lg:px-0">
                    <x-filter-sidebar :cidades="$cidades ?? []" :estados="$estados ?? []" />
                </aside>

                {{-- Conteúdo Principal --}}
                <div class="flex-1 px-4 lg:px-0">
                    {{-- Barra de Pesquisa Desktop --}}
                    <div class="hidden lg:flex mb-6 items-center justify-between gap-4">
                        <div class="flex-1 max-w-md">
                            <x-search-bar :action="route('pontos.index')" :value="request('search')" />
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $pontos->total() }} ponto{{ $pontos->total() != 1 ? 's' : '' }} encontrado{{ $pontos->total() != 1 ? 's' : '' }}
                        </p>
                    </div>

                    {{-- Grid de Cards --}}
                    @if ($pontos->count() > 0)
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
                            @foreach ($pontos as $ponto)
                                <x-ponto-card :ponto="$ponto" />
                            @endforeach
                        </div>

                        {{-- Paginação --}}
                        <div class="mt-8">
                            {{ $pontos->links() }}
                        </div>
                    @else
                        {{-- Estado Vazio --}}
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum ponto encontrado
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tente ajustar os filtros ou fazer
                                uma nova busca.</p>
                            <div class="mt-6">
                                <a href="{{ route('pontos.index') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Limpar filtros
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
