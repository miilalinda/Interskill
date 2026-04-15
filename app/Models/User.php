<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nome',
        'user_nome',
        'email',
        'cpf',
        'password',
        'foto_perfil'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // POSTS
    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    // QUEM EU SIGO
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // QUEM ME SEGUE
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // PARCERIAS RECEBIDAS
public function parceriasRecebidas()
{
    return $this->hasMany(\App\Models\Parceria::class, 'user_id');
}

// PARCERIAS ENVIADAS
public function parceriasEnviadas()
{
    return $this->hasMany(\App\Models\Parceria::class, 'solicitante_id');
}
}
