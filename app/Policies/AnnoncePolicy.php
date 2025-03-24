<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Annonce;

class AnnoncePolicy
{
    /**
     * Determine if the user can create an annonce.
     */
    public function create(User $user)
    {
        return $user->role === 'recruteur';
    }

    /**
     * Determine if the user can update an annonce.
     */
    public function update(User $user, Annonce $annonce)
    {
        return $user->role === 'recruteur' && $user->id === $annonce->user_id;
    }

    /**
     * Determine if the user can delete an annonce.
     */
    public function delete(User $user, Annonce $annonce)
    {
        return $user->role === 'recruteur' && $user->id === $annonce->user_id;
    }
}
