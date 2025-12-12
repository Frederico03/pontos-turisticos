<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PontosTuristicosController;
use App\Http\Controllers\ComentariosController;
use App\Http\Controllers\AvaliacoesController;
use App\Http\Controllers\HospedagensController;
use App\Http\Controllers\ImagensController;
use Illuminate\Support\Facades\Route;
use MongoDB\Client;

/*
|--------------------------------------------------------------------------
| Web & API Routes
|--------------------------------------------------------------------------
*/

// Home - redireciona para login
Route::get('/', function () {
    return redirect('login');
});

// Dashboard - exibe os pontos turísticos como página inicial
Route::get('/dashboard', [PontosTuristicosController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Pontos Turísticos Routes
|--------------------------------------------------------------------------
*/

// Busca geográfica (pública)
Route::get('/pontos-proximos', [PontosTuristicosController::class, 'buscarProximos'])->name('pontos.proximos');

// Rotas protegidas (requer auth)
Route::middleware('auth')->group(function () {
    // CRUD de Pontos
    Route::get('/pontos/create', [PontosTuristicosController::class, 'create'])->name('pontos.create');
    Route::post('/pontos', [PontosTuristicosController::class, 'store'])->name('pontos.store');
    Route::get('/pontos/{ponto}/edit', [PontosTuristicosController::class, 'edit'])->name('pontos.edit');
    Route::put('/pontos/{ponto}', [PontosTuristicosController::class, 'update'])->name('pontos.update');
    Route::delete('/pontos/{ponto}', [PontosTuristicosController::class, 'destroy'])->name('pontos.destroy');

    // Favoritos
    Route::post('/pontos/{ponto}/favoritar', [PontosTuristicosController::class, 'toggleFavorito'])->name('pontos.favoritar');
    Route::get('/meus-favoritos', [PontosTuristicosController::class, 'meusFavoritos'])->name('pontos.favoritos');
});

// Rotas públicas de visualização
Route::get('/pontos', [PontosTuristicosController::class, 'index'])->name('pontos.index');
Route::get('/pontos/{ponto}', [PontosTuristicosController::class, 'show'])->name('pontos.show');

/*
|--------------------------------------------------------------------------
| Comentários Routes (MongoDB)
|--------------------------------------------------------------------------
*/

// Visualizar comentários (público)
Route::get('/pontos/{ponto}/comentarios', [ComentariosController::class, 'index'])->name('comentarios.index');
Route::get('/comentarios/{id}', [ComentariosController::class, 'show'])->name('comentarios.show');

// Criar/gerenciar comentários (requer auth)
Route::middleware('auth')->group(function () {
    Route::post('/pontos/{ponto}/comentarios', [ComentariosController::class, 'store'])->name('comentarios.store');
    Route::post('/comentarios/{comentarioId}/responder', [ComentariosController::class, 'adicionarResposta'])->name('comentarios.responder');
    Route::delete('/comentarios/{comentarioId}', [ComentariosController::class, 'destroy'])->name('comentarios.destroy');
});

/*
|--------------------------------------------------------------------------
| Avaliações Routes
|--------------------------------------------------------------------------
*/

// Visualizar avaliações (público)
Route::get('/pontos/{ponto}/avaliacoes', [AvaliacoesController::class, 'index'])->name('avaliacoes.index');
Route::get('/avaliacoes/{avaliacao}', [AvaliacoesController::class, 'show'])->name('avaliacoes.show');

// Criar/gerenciar avaliações (requer auth)
Route::middleware('auth')->group(function () {
    Route::get('/pontos/{ponto}/avaliacoes/create', [AvaliacoesController::class, 'create'])->name('avaliacoes.create');
    Route::post('/pontos/{ponto}/avaliacoes', [AvaliacoesController::class, 'store'])->name('avaliacoes.store');
    Route::get('/avaliacoes/{avaliacao}/edit', [AvaliacoesController::class, 'edit'])->name('avaliacoes.edit');
    Route::put('/avaliacoes/{avaliacao}', [AvaliacoesController::class, 'update'])->name('avaliacoes.update');
    Route::delete('/avaliacoes/{avaliacao}', [AvaliacoesController::class, 'destroy'])->name('avaliacoes.destroy');

    // Minha avaliação para um ponto específico
    Route::get('/pontos/{ponto}/minha-avaliacao', [AvaliacoesController::class, 'minhaAvaliacao'])->name('avaliacoes.minha');
});

/*
|--------------------------------------------------------------------------
| Imagens Routes (MongoDB)
|--------------------------------------------------------------------------
*/

// Visualizar imagens (público)
Route::get('/pontos/{ponto}/imagens', [ImagensController::class, 'index'])->name('imagens.index');

// Upload e gerenciamento de imagens (requer auth)
Route::middleware('auth')->group(function () {
    Route::post('/pontos/{ponto}/imagens', [ImagensController::class, 'store'])->name('imagens.store');
    Route::delete('/pontos/{ponto}/imagens/{imagemId}', [ImagensController::class, 'destroy'])->name('imagens.destroy');
});

/*
|--------------------------------------------------------------------------
| Hospedagens Routes
|--------------------------------------------------------------------------
*/

// Visualizar hospedagens (público)
Route::get('/hospedagens', [HospedagensController::class, 'index'])->name('hospedagens.index');
Route::get('/hospedagens/{hospedagem}', [HospedagensController::class, 'show'])->name('hospedagens.show');
Route::get('/pontos/{ponto}/hospedagens', [HospedagensController::class, 'porPonto'])->name('hospedagens.por-ponto');
Route::post('/pontos/{ponto}/hospedagens', [HospedagensController::class, 'store'])->name('hospedagens.store');

// Criar/gerenciar hospedagens (requer auth)
Route::middleware('auth')->group(function () {
    Route::get('/pontos/{ponto}/hospedagens/create', [HospedagensController::class, 'create'])->name('hospedagens.create');
    Route::get('/hospedagens/{hospedagem}/edit', [HospedagensController::class, 'edit'])->name('hospedagens.edit');
    Route::put('/hospedagens/{hospedagem}', [HospedagensController::class, 'update'])->name('hospedagens.update');
    Route::delete('/hospedagens/{hospedagem}', [HospedagensController::class, 'destroy'])->name('hospedagens.destroy');
});

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

