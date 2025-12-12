<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hospedagem extends Model
{
    use HasFactory;

    protected $table = 'hospedagens';

    protected $fillable = [
        'ponto_id',
        'criado_por',
        'nome',
        'tipo',
        'descricao',
        'endereco',
        'telefone',
        'site',
        'preco_diaria',
        'nota_avaliacao',
        'amenidades',
    ];

    protected $casts = [
        'preco_diaria' => 'decimal:2',
        'nota_avaliacao' => 'decimal:1',
        'amenidades' => 'array',
    ];

    /**
     * Ponto turístico ao qual a hospedagem pertence
     */
    public function pontoTuristico(): BelongsTo
    {
        return $this->belongsTo(PontoTuristico::class, 'ponto_id');
    }

    /**
     * Alias para pontoTuristico (facilita uso nas views)
     */
    public function ponto(): BelongsTo
    {
        return $this->pontoTuristico();
    }

    /**
     * Usuário que criou a hospedagem
     */
    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }
}
