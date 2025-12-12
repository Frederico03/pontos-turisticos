<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Pontos turísticos criados pelo usuário
     */
    public function pontosTuristicos()
    {
        return $this->hasMany(PontoTuristico::class, 'criado_por');
    }

    /**
     * Pontos turísticos favoritados pelo usuário
     */
    public function favoritos()
    {
        return $this->belongsToMany(PontoTuristico::class, 'favoritos', 'usuario_id', 'ponto_id')
            ->withTimestamps();
    }

    /**
     * Avaliações feitas pelo usuário
     */
    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'usuario_id');
    }

    /**
     * Verifica se o usuário é administrador
     */
    public function isAdmin(): bool
    {
        return $this->role === 'ADMIN';
    }

    /**
     * Verifica se o usuário é comum
     */
    public function isUser(): bool
    {
        return $this->role === 'USER';
    }
}
