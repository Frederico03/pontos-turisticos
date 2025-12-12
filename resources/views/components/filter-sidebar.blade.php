@props(['cidades' => [], 'estados' => []])

<div class="w-full md:w-64 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filtros</h3>

    <form method="GET" action="{{ route('pontos.index') }}">
        {{-- Busca --}}
        <div class="mb-4">
            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Buscar
            </label>
            <input type="text" name="search" id="search" value="{{ request('search') }}"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                placeholder="Nome do ponto...">
        </div>

        {{-- Estado --}}
        <div class="mb-4">
            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Estado
            </label>
            <select name="estado" id="estado"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todos os estados</option>
                @foreach ($estados as $estado)
                    <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                        {{ $estado }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Cidade --}}
        <div class="mb-4">
            <label for="cidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Cidade
            </label>
            <select name="cidade" id="cidade"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todas as cidades</option>
                @foreach ($cidades as $cidade)
                    <option value="{{ $cidade }}" {{ request('cidade') == $cidade ? 'selected' : '' }}>
                        {{ $cidade }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Avaliação Mínima --}}
        <div class="mb-4">
            <label for="min_rating" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Avaliação Mínima
            </label>
            <select name="min_rating" id="min_rating"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                <option value="">Qualquer avaliação</option>
                <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ estrelas</option>
                <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ estrelas</option>
                <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>2+ estrelas</option>
                <option value="1" {{ request('min_rating') == '1' ? 'selected' : '' }}>1+ estrela</option>
            </select>
        </div>

        {{-- Ordenação --}}
        <div class="mb-4">
            <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Ordenar por
            </label>
            <select name="sort" id="sort"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Mais recentes</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Melhor avaliados</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nome (A-Z)</option>
            </select>
        </div>

        {{-- Botões --}}
        <div class="flex gap-2">
            <button type="submit"
                class="flex-1 bg-blue-600 text-white rounded-lg px-4 py-2 text-sm font-medium hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                Aplicar
            </button>
            <a href="{{ route('pontos.index') }}"
                class="flex-1 bg-gray-200 text-gray-700 rounded-lg px-4 py-2 text-sm font-medium hover:bg-gray-300 text-center dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                Limpar
            </a>
        </div>
    </form>
</div>
