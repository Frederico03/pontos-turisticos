<?php

namespace App\Http\Controllers;

use App\Models\PontoTuristico;
use App\Http\Requests\StorePontoTuristicoRequest;
use App\Http\Requests\UpdatePontoTuristicoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PontosTuristicosController extends Controller
{
    /**
     * Display a listing of the resource with filters and search.
     */
    public function index(Request $request)
    {
        $query = PontoTuristico::query()->with(['criador', 'avaliacoes']);

        // Busca por texto
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'ILIKE', "%{$search}%")
                  ->orWhere('descricao', 'ILIKE', "%{$search}%")
                  ->orWhere('cidade', 'ILIKE', "%{$search}%");
            });
        }

        // Filtro por cidade
        if ($cidade = $request->input('cidade')) {
            $query->where('cidade', 'ILIKE', $cidade);
        }

        // Filtro por estado
        if ($estado = $request->input('estado')) {
            $query->where('estado', 'ILIKE', $estado);
        }

        // Filtro por nota mínima
        if ($notaMinima = $request->input('nota_minima')) {
            $query->where('nota_media', '>=', $notaMinima);
        }

        // Ordenação
        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        // Paginação
        $perPage = $request->input('per_page', 15);
        $pontos = $query->paginate($perPage);

        // Buscar estados e cidades distintos para filtros
        $estados = PontoTuristico::distinct()->pluck('estado')->sort();
        $cidades = PontoTuristico::distinct()->pluck('cidade')->sort();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $pontos,
                'filters' => [
                    'estados' => $estados,
                    'cidades' => $cidades,
                ]
            ]);
        }

        return view('pontos.index', compact('pontos', 'estados', 'cidades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pontos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePontoTuristicoRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validated();
            $validated['criado_por'] = auth()->id();

            $ponto = PontoTuristico::create($validated);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Ponto turístico criado com sucesso!',
                    'data' => $ponto->load('criador')
                ], 201);
            }

            return redirect()
                ->route('pontos.show', $ponto)
                ->with('success', 'Ponto turístico criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao criar ponto turístico.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar ponto turístico.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PontoTuristico $ponto)
    {
        $ponto->load([
            'criador',
            'avaliacoes.usuario',
            'hospedagens'
        ]);

        // Verificar se o usuário autenticado favoritou este ponto
        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = $ponto->isFavoritadoPor(auth()->id());
        }

        if (request()->wantsJson()) {
            return response()->json([
                'data' => $ponto,
                'is_favorited' => $isFavorited,
            ]);
        }

        return view('pontos.show', compact('ponto', 'isFavorited'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PontoTuristico $ponto)
    {
        // Apenas o criador ou admin pode editar
        $this->authorize('update', $ponto);

        return view('pontos.edit', compact('ponto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePontoTuristicoRequest $request, PontoTuristico $ponto)
    {
        $this->authorize('update', $ponto);

        DB::beginTransaction();
        
        try {
            $ponto->update($request->validated());
            
            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Ponto turístico atualizado com sucesso!',
                    'data' => $ponto->fresh()->load('criador')
                ]);
            }

            return redirect()
                ->route('pontos.show', $ponto)
                ->with('success', 'Ponto turístico atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao atualizar ponto turístico.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar ponto turístico.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PontoTuristico $ponto)
    {
        $this->authorize('delete', $ponto);

        DB::beginTransaction();
        
        try {
            $ponto->delete();
            
            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Ponto turístico removido com sucesso!'
                ]);
            }

            return redirect()
                ->route('pontos.index')
                ->with('success', 'Ponto turístico removido com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao remover ponto turístico.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Erro ao remover ponto turístico.');
        }
    }

    /**
     * Buscar pontos turísticos próximos a uma coordenada
     */
    public function buscarProximos(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'raio' => 'nullable|integer|min:1|max:500',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $raio = $request->input('raio', 50); // 50km por padrão

        $pontos = PontoTuristico::buscarProximos($latitude, $longitude, $raio)
            ->with(['criador', 'avaliacoes'])
            ->get();

        return response()->json([
            'data' => $pontos,
            'centro' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
            'raio_km' => $raio,
        ]);
    }

    /**
     * Adicionar/remover favorito
     */
    public function toggleFavorito(PontoTuristico $ponto)
    {
        $user = auth()->user();
        
        if ($ponto->isFavoritadoPor($user->id)) {
            $ponto->favoritadoPor()->detach($user->id);
            $isFavorited = false;
            $message = 'Ponto removido dos favoritos.';
        } else {
            $ponto->favoritadoPor()->attach($user->id);
            $isFavorited = true;
            $message = 'Ponto adicionado aos favoritos!';
        }

        if (request()->wantsJson()) {
            return response()->json([
                'message' => $message,
                'is_favorited' => $isFavorited,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Listar favoritos do usuário autenticado
     */
    public function meusFavoritos(Request $request)
    {
        $user = auth()->user();
        
        $favoritos = $user->pontosFavoritos()
            ->with(['criador', 'avaliacoes'])
            ->paginate($request->input('per_page', 15));

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $favoritos,
            ]);
        }

        return view('pontos.favoritos', compact('favoritos'));
    }
}

