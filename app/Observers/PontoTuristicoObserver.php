<?php

namespace App\Observers;

use App\Models\PontoTuristico;
use Illuminate\Support\Facades\Cache;

class PontoTuristicoObserver
{
    /**
     * Handle the PontoTuristico "created" event.
     */
    public function created(PontoTuristico $pontoTuristico): void
    {
        $this->invalidarCache();
    }

    /**
     * Handle the PontoTuristico "updated" event.
     */
    public function updated(PontoTuristico $pontoTuristico): void
    {
        $this->invalidarCache();
        Cache::forget("ponto_turistico_{$pontoTuristico->id}");
    }

    /**
     * Handle the PontoTuristico "deleted" event.
     */
    public function deleted(PontoTuristico $pontoTuristico): void
    {
        $this->invalidarCache();
        Cache::forget("ponto_turistico_{$pontoTuristico->id}");
    }

    /**
     * Invalida o cache da listagem de pontos turísticos
     */
    private function invalidarCache(): void
    {
        // Limpa o cache de listagens
        Cache::forget('pontos_turisticos_listagem');
        
        // Limpa padrões de cache de filtros
        $tags = ['pontos_turisticos'];
        Cache::tags($tags)->flush();
    }
}
