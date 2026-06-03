<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HighlightReaction extends Model
{
    protected $fillable = [
        'highlight_id',
        'user_id',
        'emoji',
    ];
}
