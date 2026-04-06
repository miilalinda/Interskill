<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Midia extends Model
{
    protected $fillable = ['caminho', 'tipo', 'post_id'];

    public function post()
{
    return $this->belongsTo(Post::class);
}
}
