<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use Illuminate\Http\Request;

class HighlightController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:50',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10000',
        ]);

        $audio = null;

        if ($request->hasFile('audio')) {
            $audio = $request->file('audio')->store('highlights/audio', 'public');
        }

        $imagem = null;

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem')->store('highlights', 'public');
        }

        Highlight::create([
            'user_id' => auth()->id(),
            'titulo' => $request->titulo,
            'imagem' => $imagem,
            'audio' => $audio,
        ]);

        return back();
    }
}
