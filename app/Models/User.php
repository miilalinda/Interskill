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
        'foto_perfil',
        'bio'
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

   // QUEM ME SEGUE
public function followers()
{
    return $this->belongsToMany(
        User::class,
        'followers',
        'user_id',
        'follower_id'
    );
}

// QUEM EU SIGO
public function following()
{
    return $this->belongsToMany(
        User::class,
        'followers',
        'follower_id',
        'user_id'
    );
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

    // HABILIDADES ✅
    public function skills()
{
    return $this->belongsToMany(
        Skill::class,
        'skill_user'
    )->withPivot('nivel');
}

    public function highlights()
{
    return $this->hasMany(\App\Models\Highlight::class);
}
}
