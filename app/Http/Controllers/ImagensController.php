<?php

namespace App\Http\Controllers;

use App\Models\PontoTuristico;
use App\Services\FotoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImagensController extends Controller
{
    protected FotoService $fotoService;

    public function __construct(FotoService $fotoService)
    {
        $this->fotoService = $fotoService;
    }

    /**
     * Upload de uma ou mais imagens para um ponto turístico
     */
    public function store(Request $request, PontoTuristico $ponto)
    {
        $request->validate([
            'imagens' => 'required|array|max:5',
            'imagens.*' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            'titulos' => 'nullable|array',
            'titulos.*' => 'nullable|string|max:255',
        ], [
            'imagens.required' => 'Selecione pelo menos uma imagem.',
            'imagens.max' => 'Você pode enviar no máximo 5 imagens por vez.',
            'imagens.*.image' => 'O arquivo deve ser uma imagem.',
            'imagens.*.mimes' => 'Formato inválido. Use: jpeg, jpg, png ou webp.',
            'imagens.*.max' => 'Cada imagem deve ter no máximo 2MB.',
        ]);

        try {
            $uploadedImages = [];
            
            foreach ($request->file('imagens') as $index => $arquivo) {
                $titulo = $request->input("titulos.$index", null);
                
                $imagemData = $this->fotoService->upload(
                    $ponto->id,
                    auth()->id(),
                    $arquivo,
                    $titulo
                );
                
                $uploadedImages[] = $imagemData;
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => count($uploadedImages) . ' imagem(ns) enviada(s) com sucesso!',
                    'data' => $uploadedImages
                ], 201);
            }

            return redirect()
                ->route('pontos.show', $ponto)
                ->with('success', count($uploadedImages) . ' imagem(ns) enviada(s) com sucesso!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao fazer upload das imagens.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao fazer upload: ' . $e->getMessage());
        }
    }

    /**
     * Listar imagens de um ponto
     */
    public function index(PontoTuristico $ponto)
    {
        $imagens = $this->fotoService->listarPorPonto($ponto->id);

        if (request()->wantsJson()) {
            return response()->json([
                'data' => $imagens,
                'ponto' => $ponto->only(['id', 'nome']),
            ]);
        }

        return view('imagens.index', compact('ponto', 'imagens'));
    }

    /**
     * Deletar uma imagem
     */
    public function destroy(PontoTuristico $ponto, string $imagemId)
    {
        try {
            $imagem = $this->fotoService->buscarPorId($imagemId);

            if (!$imagem) {
                if (request()->wantsJson()) {
                    return response()->json(['message' => 'Imagem não encontrada.'], 404);
                }
                return redirect()->back()->with('error', 'Imagem não encontrada.');
            }

            // Verificar se o usuário pode deletar (criador do ponto ou dono da imagem)
            if (auth()->id() != $ponto->criado_por && auth()->id() != $imagem['usuarioId']) {
                if (request()->wantsJson()) {
                    return response()->json(['message' => 'Você não tem permissão para excluir esta imagem.'], 403);
                }
                return redirect()->back()->with('error', 'Você não tem permissão para excluir esta imagem.');
            }

            $this->fotoService->deletar($imagemId);

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Imagem removida com sucesso!']);
            }

            return redirect()
                ->route('pontos.show', $ponto)
                ->with('success', 'Imagem removida com sucesso!');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Erro ao remover imagem.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Erro ao remover imagem.');
        }
    }
}
