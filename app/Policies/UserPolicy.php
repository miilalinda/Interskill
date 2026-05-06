<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update($usuarioLogado, User $usuarioAlvo)
    {
        return $usuarioLogado->id === $usuarioAlvo->id
            ? Response::allow()
            : Response::deny('Você não tem permissão para editar este perfil.');
    }
}
