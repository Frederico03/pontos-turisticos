<?php

namespace App\Observers;

use App\Models\Avaliacao;
use App\Models\PontoTuristico;

class AvaliacaoObserver
{
    /**
     * Handle the Avaliacao "created" event.
     */
    public function created(Avaliacao $avaliacao): void
    {
        $this->recalcularMedia($avaliacao->ponto_id);
    }

    /**
     * Handle the Avaliacao "updated" event.
     */
    public function updated(Avaliacao $avaliacao): void
    {
        $this->recalcularMedia($avaliacao->ponto_id);
    }

    /**
     * Handle the Avaliacao "deleted" event.
     */
    public function deleted(Avaliacao $avaliacao): void
    {
        $this->recalcularMedia($avaliacao->ponto_id);
    }

    /**
     * Recalcula a média de avaliações de um ponto turístico
     */
    private function recalcularMedia(int $pontoId): void
    {
        $ponto = PontoTuristico::find($pontoId);
        
        if (!$ponto) {
            return;
        }

        $media = $ponto->avaliacoes()->avg('nota') ?? 0;
        
        $ponto->update([
            'nota_media' => round($media, 2)
        ]);
    }
}
