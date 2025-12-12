<?php

namespace App\Http\Controllers;

use App\Services\ComentarioService;
use App\Http\Requests\StoreComentarioRequest;
use App\Models\PontoTuristico;
use Illuminate\Http\Request;

class ComentariosController extends Controller
{
    protected $comentarioService;

    public function __construct(ComentarioService $comentarioService)
    {
        $this->comentarioService = $comentarioService;
    }

    /**
     * Get all comments for a specific ponto
     */
    public function index(PontoTuristico $ponto, Request $request)
    {
        $limite = $request->input('limite', 50);
        
        $comentarios = $this->comentarioService->listarPorPonto($ponto->id, $limite);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $comentarios,
                'ponto' => $ponto->only(['id', 'nome']),
            ]);
        }

        return view('comentarios.index', compact('comentarios', 'ponto'));
    }

    /**
     * Store a new comment
     */
    public function store(StoreComentarioRequest $request, PontoTuristico $ponto)
    {
        try {
            $comentario = $this->comentarioService->criar(
                $ponto->id,
                auth()->id(),
                $request->input('texto'),
                $request->input('metadata', [])
            );

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Comentário criado com sucesso!',
                    'data' => $comentario
                ], 201);
            }

            return redirect()
                ->back()
                ->with('success', 'Comentário adicionado com sucesso!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao criar comentário.',
                    'error' => $e->getMessage()
                ], 400);
            }

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Get a single comment
     */
    public function show(string $id)
    {
        try {
            $comentario = $this->comentarioService->buscarPorId($id);

            if (!$comentario) {
                return response()->json([
                    'message' => 'Comentário não encontrado.'
                ], 404);
            }

            return response()->json([
                'data' => $comentario
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar comentário.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Add a reply to a comment
     */
    public function adicionarResposta(Request $request, string $comentarioId)
    {
        $request->validate([
            'texto' => 'required|string|min:3|max:500',
        ]);

        try {
            $success = $this->comentarioService->adicionarResposta(
                $comentarioId,
                auth()->id(),
                $request->input('texto')
            );

            if (!$success) {
                return response()->json([
                    'message' => 'Comentário não encontrado ou erro ao adicionar resposta.'
                ], 404);
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Resposta adicionada com sucesso!'
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'Resposta adicionada com sucesso!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao adicionar resposta.',
                    'error' => $e->getMessage()
                ], 400);
            }

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Delete a comment
     */
    public function destroy(string $comentarioId)
    {
        try {
            // Buscar comentário para verificar permissão
            $comentario = $this->comentarioService->buscarPorId($comentarioId);
            
            if (!$comentario) {
                return response()->json([
                    'message' => 'Comentário não encontrado.'
                ], 404);
            }

            // Verificar se o usuário é o dono do comentário ou admin
            if ($comentario['usuarioId'] != auth()->id() && !auth()->user()->isAdmin()) {
                return response()->json([
                    'message' => 'Você não tem permissão para deletar este comentário.'
                ], 403);
            }

            $success = $this->comentarioService->deletar($comentarioId);

            if (!$success) {
                return response()->json([
                    'message' => 'Erro ao deletar comentário.'
                ], 500);
            }

            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Comentário deletado com sucesso!'
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'Comentário deletado com sucesso!');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao deletar comentário.',
                    'error' => $e->getMessage()
                ], 400);
            }

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}

