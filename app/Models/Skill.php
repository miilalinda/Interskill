<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['nome'];
    
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('nivel');
    }
}
