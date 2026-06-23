<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use App\Models\HighlightItem;
use Illuminate\Http\Request;

class HighlightController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:50',
            'capa' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'imagens' => 'required|array|min:1',
            'imagens.*' => 'image|mimes:jpg,jpeg,png|max:5048',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10000',
        ]);

        $capa = null;
        if ($request->hasFile('capa')) {
            $capa = $request->file('capa')->store('highlights', 'public');
        }

        $audio = null;
        if ($request->hasFile('audio')) {
            $audio = $request->file('audio')->store('highlights/audio', 'public');
        }

        $highlight = Highlight::create([
            'user_id' => auth()->id(),
            'titulo' => $request->titulo,
            'imagem' => $capa,
            'audio' => $audio,
        ]);

        foreach ($request->file('imagens') as $i => $imagem) {
            $caminho = $imagem->store('highlights/items', 'public');

            HighlightItem::create([
                'highlight_id' => $highlight->id,
                'caminho' => $caminho,
                'ordem' => $i,
            ]);
        }

        return back();
    }
}
