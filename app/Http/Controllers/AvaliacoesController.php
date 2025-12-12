<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\PontoTuristico;
use App\Http\Requests\StoreAvaliacaoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvaliacoesController extends Controller
{
    /**
     * List all reviews for a ponto
     */
    public function index(PontoTuristico $ponto, Request $request)
    {
        $avaliacoes = $ponto->avaliacoes()
            ->with('usuario')
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $avaliacoes,
                'ponto' => $ponto->only(['id', 'nome', 'nota_media']),
            ]);
        }

        return view('avaliacoes.index', compact('avaliacoes', 'ponto'));
    }

    /**
     * Show form to create a new review
     */
    public function create(PontoTuristico $ponto)
    {
        // Verificar se o usuário já avaliou este ponto
        $avaliacaoExistente = Avaliacao::where('ponto_id', $ponto->id)
            ->where('usuario_id', auth()->id())
            ->first();

        if ($avaliacaoExistente) {
            return redirect()
                ->route('avaliacoes.edit', $avaliacaoExistente)
                ->with('info', 'Você já avaliou este ponto. Edite sua avaliação existente.');
        }

        $ponto->load('avaliacoes');
        return view('avaliacoes.create', compact('ponto'));
    }

    /**
     * Store a new review
     */
    public function store(StoreAvaliacaoRequest $request, PontoTuristico $ponto)
    {
        // Verificar se o usuário já avaliou este ponto
        $avaliacaoExistente = Avaliacao::where('ponto_id', $ponto->id)
            ->where('usuario_id', auth()->id())
            ->first();

        if ($avaliacaoExistente) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Você já avaliou este ponto turístico. Use PUT para atualizar sua avaliação.',
                    'avaliacao_id' => $avaliacaoExistente->id
                ], 422);
            }

            return redirect()
                ->back()
                ->with('error', 'Você já avaliou este ponto turístico.');
        }

        DB::beginTransaction();

        try {
            $avaliacao = Avaliacao::create([
                'ponto_id' => $ponto->id,
                'usuario_id' => auth()->id(),
                'nota' => $request->input('nota'),
                'comentario' => $request->input('comentario'),
            ]);

            // Atualizar média do ponto
            $this->atualizarMediaPonto($ponto);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Avaliação criada com sucesso!',
                    'data' => $avaliacao->load('usuario')
                ], 201);
            }

            return redirect()
                ->route('pontos.show', $ponto)
                ->with('success', 'Avaliação adicionada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao criar avaliação.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Erro ao criar avaliação.');
        }
    }

    /**
     * Show a single review
     */
    public function show(Avaliacao $avaliacao)
    {
        $avaliacao->load(['usuario', 'ponto']);

        return response()->json([
            'data' => $avaliacao
        ]);
    }

    /**
     * Show form to edit a review
     */
    public function edit(Avaliacao $avaliacao)
    {
        $this->authorize('update', $avaliacao);
        $avaliacao->load('ponto');

        return view('avaliacoes.edit', compact('avaliacao'));
    }

    /**
     * Update a review
     */
    public function update(Request $request, Avaliacao $avaliacao)
    {
        $this->authorize('update', $avaliacao);

        $request->validate([
            'nota' => 'sometimes|required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $avaliacao->update($request->only(['nota', 'comentario']));

            // Atualizar média do ponto
            $this->atualizarMediaPonto($avaliacao->ponto);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Avaliação atualizada com sucesso!',
                    'data' => $avaliacao->fresh()->load('usuario')
                ]);
            }

            return redirect()
                ->route('pontos.show', $avaliacao->ponto)
                ->with('success', 'Avaliação atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao atualizar avaliação.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Erro ao atualizar avaliação.');
        }
    }

    /**
     * Delete a review
     */
    public function destroy(Avaliacao $avaliacao)
    {
        $this->authorize('delete', $avaliacao);

        DB::beginTransaction();

        try {
            $ponto = $avaliacao->ponto;
            $avaliacao->delete();

            // Atualizar média do ponto
            $this->atualizarMediaPonto($ponto);

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Avaliação removida com sucesso!'
                ]);
            }

            return redirect()
                ->route('pontos.show', $ponto)
                ->with('success', 'Avaliação removida com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao remover avaliação.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Erro ao remover avaliação.');
        }
    }

    /**
     * Get user's review for a specific ponto
     */
    public function minhaAvaliacao(PontoTuristico $ponto)
    {
        $avaliacao = Avaliacao::where('ponto_id', $ponto->id)
            ->where('usuario_id', auth()->id())
            ->with('usuario')
            ->first();

        if (!$avaliacao) {
            return response()->json([
                'message' => 'Você ainda não avaliou este ponto.',
                'has_review' => false
            ], 404);
        }

        return response()->json([
            'data' => $avaliacao,
            'has_review' => true
        ]);
    }

    /**
     * Update average rating of a ponto
     */
    private function atualizarMediaPonto(PontoTuristico $ponto)
    {
        $media = $ponto->avaliacoes()->avg('nota');
        $ponto->update(['nota_media' => $media ?? 0]);
    }
}

