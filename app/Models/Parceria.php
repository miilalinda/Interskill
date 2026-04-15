<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Parceria extends Model
{
    protected $fillable = [
        'user_id',
        'solicitante_id',
        'status'
    ];

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }
}
