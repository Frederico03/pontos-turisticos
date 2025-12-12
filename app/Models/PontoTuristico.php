<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PontoTuristico extends Model
{
    use HasFactory;

    protected $table = 'pontos_turisticos';

    protected $fillable = [
        'nome',
        'descricao',
        'cidade',
        'estado',
        'pais',
        'latitude',
        'longitude',
        'endereco',
        'nota_media',
        'criado_por',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'nota_media' => 'decimal:2',
    ];

    /**
     * Usuário que criou o ponto turístico
     */
    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    /**
     * Hospedagens relacionadas ao ponto
     */
    public function hospedagens(): HasMany
    {
        return $this->hasMany(Hospedagem::class, 'ponto_id');
    }

    /**
     * Avaliações do ponto
     */
    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'ponto_id');
    }

    /**
     * Usuários que favoritaram este ponto
     */
    public function favoritadoPor(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favoritos', 'ponto_id', 'usuario_id')
            ->withTimestamps();
    }

    /**
     * Verifica se o ponto foi favoritado pelo usuário
     */
    public function isFavoritadoPor(?int $usuarioId): bool
    {
        if (!$usuarioId) {
            return false;
        }

        return $this->favoritadoPor()->where('usuario_id', $usuarioId)->exists();
    }

    /**
     * Buscar pontos turísticos próximos a uma coordenada
     * Usa a fórmula de Haversine para cálculo de distância
     */
    public static function buscarProximos(float $latitude, float $longitude, int $raioKm = 50)
    {
        $earthRadius = 6371; // Raio da Terra em km

        return self::selectRaw("
                *,
                (
                    {$earthRadius} * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )
                ) AS distancia
            ", [$latitude, $longitude, $latitude])
            ->having('distancia', '<=', $raioKm)
            ->orderBy('distancia', 'asc');
    }
}
