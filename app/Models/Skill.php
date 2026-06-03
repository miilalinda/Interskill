<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['nome', 'category'];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'skill_user'
        )->withPivot('nivel');
    }
}
