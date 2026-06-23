<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HighlightItem extends Model
{
    protected $fillable = [
        'highlight_id',
        'caminho',
        'ordem',
    ];

    public function highlight()
    {
        return $this->belongsTo(Highlight::class);
    }
}
