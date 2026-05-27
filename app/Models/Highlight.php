<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    protected $fillable = [
        'user_id',
        'titulo',
        'imagem',
        'audio',
    ];
}
