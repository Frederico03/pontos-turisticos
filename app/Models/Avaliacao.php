<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'ponto_id',
        'usuario_id',
        'nota',
        'comentario',
    ];

    protected $casts = [
        'nota' => 'integer',
    ];

    /**
     * Ponto turístico avaliado
     */
    public function pontoTuristico(): BelongsTo
    {
        return $this->belongsTo(PontoTuristico::class, 'ponto_id');
    }

    /**
     * Usuário que fez a avaliação
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Alias para pontoTuristico()
     */
    public function ponto(): BelongsTo
    {
        return $this->pontoTuristico();
    }
}
