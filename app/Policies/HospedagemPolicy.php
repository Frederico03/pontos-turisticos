<?php

namespace App\Policies;

use App\Models\Hospedagem;
use App\Models\User;

class HospedagemPolicy
{
    /**
     * Determine if the user can view any hospedagens.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can view the hospedagem.
     */
    public function view(?User $user, Hospedagem $hospedagem): bool
    {
        return true;
    }

    /**
     * Determine if the user can create hospedagens.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine if the user can update the hospedagem.
     */
    public function update(User $user, Hospedagem $hospedagem): bool
    {
        return $user->id === $hospedagem->criado_por;
    }

    /**
     * Determine if the user can delete the hospedagem.
     */
    public function delete(User $user, Hospedagem $hospedagem): bool
    {
        return $user->id === $hospedagem->criado_por;
    }
}
