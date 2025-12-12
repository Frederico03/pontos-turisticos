@props(['ponto', 'showFavoriteButton' => true])

<div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    {{-- Imagem --}}
    <div class="h-56 w-full">
        <a href="{{ route('pontos.show', $ponto->id) }}">
            @if ($ponto->foto_principal ?? null)
                <img class="mx-auto h-full w-full object-cover rounded-lg" src="{{ $ponto->foto_principal }}"
                    alt="{{ $ponto->nome }}" />
            @else
                <div class="flex items-center justify-center h-full bg-gray-200 dark:bg-gray-700 rounded-lg">
                    <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            @endif
        </a>
    </div>

    <div class="pt-6">
        {{-- Header com badge e ações --}}
        <div class="mb-4 flex items-center justify-between gap-4">
            <span class="rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                {{ $ponto->cidade }}, {{ $ponto->estado }}
            </span>

            <div class="flex items-center justify-end gap-1">
                {{-- Botão Ver Detalhes --}}
                <a href="{{ route('pontos.show', $ponto->id) }}"
                    class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    <span class="sr-only">Ver detalhes</span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>

                {{-- Botão Favoritar --}}
                @if ($showFavoriteButton && auth()->check())
                    <form action="{{ route('pontos.favoritar', $ponto->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-red-600 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-red-500">
                            <span class="sr-only">Favoritar</span>
                            <svg class="h-5 w-5 {{ $ponto->isFavoritadoPor(auth()->id()) ? 'fill-current text-red-600' : '' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Título --}}
        <a href="{{ route('pontos.show', $ponto->id) }}"
            class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white line-clamp-2">
            {{ $ponto->nome }}
        </a>

        {{-- Rating --}}
        <div class="mt-2 flex items-center gap-2">
            <x-rating-stars :rating="$ponto->nota_media ?? 0" />
            <p class="text-sm font-medium text-gray-900 dark:text-white">
                {{ number_format($ponto->nota_media ?? 0, 1) }}
            </p>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                ({{ $ponto->avaliacoes_count ?? 0 }})
            </p>
        </div>

        {{-- Descrição --}}
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
            {{ $ponto->descricao }}
        </p>

        {{-- Footer com Ações --}}
        <div class="mt-4 flex items-center justify-between gap-4">
            @if ($ponto->hospedagens_count ?? 0 > 0)
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    <svg class="inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    {{ $ponto->hospedagens_count }} hospedagen{{ $ponto->hospedagens_count > 1 ? 's' : 'm' }}
                </p>
            @endif

            <a href="{{ route('pontos.show', $ponto->id) }}"
                class="inline-flex items-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Ver Detalhes
            </a>
        </div>
    </div>
</div>
