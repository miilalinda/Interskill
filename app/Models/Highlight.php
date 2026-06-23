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

    public function reactions()
    {
        return $this->hasMany(HighlightReaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(HighlightItem::class)->orderBy('ordem');
    }
}
