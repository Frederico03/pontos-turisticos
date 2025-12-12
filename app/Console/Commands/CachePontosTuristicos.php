<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PontoTuristico;
use Illuminate\Support\Facades\Cache;

class CachePontosTuristicos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'turismo:cache-pontos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carrega os pontos turísticos do banco de dados para o cache Redis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando o carregamento dos pontos turísticos para o cache...');

        // Carrega todos os pontos turísticos com relacionamentos
        $pontos = PontoTuristico::with(['criador', 'avaliacoes'])->get();

        // Armazena no Redis com a chave 'pontos_turisticos:all'
        // Define um tempo de expiração de 24 horas (em segundos)
        Cache::store('redis')->put('pontos_turisticos:all', $pontos, 60 * 60 * 24);

        $this->info('Pontos turísticos carregados com sucesso! Total: ' . $pontos->count());
        
        // Opcional: Cachear individualmente também, se necessário
        foreach ($pontos as $ponto) {
            Cache::store('redis')->put("pontos_turisticos:{$ponto->id}", $ponto, 60 * 60 * 24);
        }
        $this->info('Pontos individuais também foram cacheados.');
    }
}
