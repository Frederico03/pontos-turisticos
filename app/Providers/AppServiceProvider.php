<?php

namespace App\Providers;

use App\Models\Avaliacao;
use App\Models\PontoTuristico;
use App\Observers\AvaliacaoObserver;
use App\Observers\PontoTuristicoObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar observers
        Avaliacao::observe(AvaliacaoObserver::class);
        PontoTuristico::observe(PontoTuristicoObserver::class);
    }
}
