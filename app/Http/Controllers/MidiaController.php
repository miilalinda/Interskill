<?php

namespace App\Http\Controllers;

use App\Models\Midia;
use Illuminate\Support\Facades\Storage;

class MidiaController extends Controller
{
    public function destroy($id)
    {
        $midia = Midia::findOrFail($id);

        // 🔒 segurança (só dono apaga)
        if ($midia->post->user_id != auth()->id()) {
            abort(403);
        }

        // 🗑️ apaga do storage
        if (Storage::exists('public/' . $midia->caminho)) {
            Storage::delete('public/' . $midia->caminho);
        }

        // 🗑️ apaga do banco
        $midia->delete();

        return back()->with('success', 'Mídia apagada!');
    }
}
