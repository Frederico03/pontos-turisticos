<?php

namespace App\Http\Controllers;

use App\Models\Hospedagem;
use App\Models\PontoTuristico;
use App\Http\Requests\StoreHospedagemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HospedagensController extends Controller
{
    /**
     * List all hospedagens
     */
    public function index(Request $request)
    {
        $query = Hospedagem::query()->with('pontoTuristico');

        // Filter by tipo
        if ($tipo = $request->input('tipo')) {
            $query->where('tipo', $tipo);
        }

        // Filter by ponto_id
        if ($pontoId = $request->input('ponto_id')) {
            $query->where('ponto_id', $pontoId);
        }

        // Filter by price range
        if ($precoMin = $request->input('preco_min')) {
            $query->where('preco_diaria', '>=', $precoMin);
        }

        if ($precoMax = $request->input('preco_max')) {
            $query->where('preco_diaria', '<=', $precoMax);
        }

        // Order
        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        $hospedagens = $query->paginate($request->input('per_page', 15));

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $hospedagens
            ]);
        }

        return view('hospedagens.index', compact('hospedagens'));
    }

    /**
     * Show form to create hospedagem
     */
    public function create(PontoTuristico $ponto)
    {
        return view('hospedagens.create', compact('ponto'));
    }

    /**
     * Store new hospedagem
     */
    public function store(Request $request, PontoTuristico $ponto)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'tipo' => 'required|in:hotel,pousada,hostel,resort,apartamento',
                'descricao' => 'nullable|string|max:2000',
                'endereco' => 'required|string|max:500',
                'preco_diaria' => 'required|numeric|min:0',
                'contato' => 'nullable|string|max:100',
                'link_reserva' => 'nullable|string|max:255',
            ]);

            $validated['ponto_id'] = $ponto->id;

            $hospedagem = Hospedagem::create($validated);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Hospedagem criada com sucesso!',
                    'data' => $hospedagem->load('pontoTuristico')
                ], 201);
            }
            return redirect()
                ->route('hospedagens.show', $hospedagem)
                ->with('success', 'Hospedagem criada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao criar hospedagem.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar hospedagem.');
        }
    }

    /**
     * Show single hospedagem
     */
    public function show(Hospedagem $hospedagem)
    {
        $hospedagem->load('pontoTuristico', 'criador');

        if (request()->wantsJson()) {
            return response()->json([
                'data' => $hospedagem
            ]);
        }

        return view('hospedagens.show', compact('hospedagem'));
    }

    /**
     * Show form to edit hospedagem
     */
    public function edit(Hospedagem $hospedagem)
    {
        $this->authorize('update', $hospedagem);
        
        $pontos = PontoTuristico::orderBy('nome')->get();
        return view('hospedagens.edit', compact('hospedagem', 'pontos'));
    }

    /**
     * Update hospedagem
     */
    public function update(Request $request, Hospedagem $hospedagem)
    {
        $this->authorize('update', $hospedagem);

        $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'tipo' => 'sometimes|required|in:hotel,pousada,hostel,resort,apartamento',
            'descricao' => 'nullable|string|max:2000',
            'endereco' => 'sometimes|required|string|max:500',
            'preco_diaria' => 'sometimes|required|numeric|min:0',
            'nota_avaliacao' => 'nullable|numeric|min:0|max:5',
            'amenidades' => 'nullable|array',
            'contato' => 'nullable|string|max:100',
            'site' => 'nullable|url|max:255',
        ]);

        DB::beginTransaction();

        try {
            $hospedagem->update($request->all());

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Hospedagem atualizada com sucesso!',
                    'data' => $hospedagem->fresh()->load('pontoTuristico')
                ]);
            }

            return redirect()
                ->route('hospedagens.show', $hospedagem)
                ->with('success', 'Hospedagem atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao atualizar hospedagem.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar hospedagem.');
        }
    }

    /**
     * Delete hospedagem
     */
    public function destroy(Hospedagem $hospedagem)
    {
        $this->authorize('delete', $hospedagem);

        DB::beginTransaction();

        try {
            $hospedagem->delete();

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Hospedagem removida com sucesso!'
                ]);
            }

            return redirect()
                ->route('hospedagens.index')
                ->with('success', 'Hospedagem removida com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao remover hospedagem.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Erro ao remover hospedagem.');
        }
    }

    /**
     * List hospedagens for a specific ponto
     */
    public function porPonto(PontoTuristico $ponto, Request $request)
    {
        $query = $ponto->hospedagens();

        // Filter by tipo
        if ($tipo = $request->input('tipo')) {
            $query->where('tipo', $tipo);
        }

        $hospedagens = $query->paginate($request->input('per_page', 10));

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $hospedagens,
                'ponto' => $ponto->only(['id', 'nome', 'cidade', 'estado'])
            ]);
        }

        return view('hospedagens.por-ponto', compact('hospedagens', 'ponto'));
    }
}

