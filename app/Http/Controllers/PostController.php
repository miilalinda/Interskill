<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // Validação: Exige texto OU arquivo. Não permite post vazio.
        $request->validate([
            'corpo' => 'required_without:arquivos|nullable|string',
            'arquivos' => 'nullable|array',
            'arquivos.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:50000', // 50MB
        ]);

        // 1. Cria o Post
        $post = Post::create([
            'user_id' => Auth::id(),
            'corpo' => $request->corpo,
        ]);

        // 2. Processa os arquivos
        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $arquivo) {
                $caminho = $arquivo->store('posts', 'public');

                // Verifica se é vídeo pelo mime-type
                $tipo = str_contains($arquivo->getMimeType(), 'video') ? 'video' : 'imagem';

                PostMedia::create([
                    'post_id' => $post->id,
                    'caminho' => $caminho,
                    'tipo' => $tipo,
                ]);
            }
        }

        return back()->with('sucesso', 'Postagem publicada com sucesso!');
    }

    public function destroy(Post $post)
    {
        // Segurança: Só o dono deleta
        if ($post->user_id !== auth()->id()) {
            return back()->with('erro', 'Ação não permitida.');
        }

        // Deleta arquivos do disco
        foreach ($post->media as $media) {
            Storage::disk('public')->delete($media->caminho);
        }

        $post->delete();
        return back()->with('sucesso', 'Postagem excluída.');
    }

    public function like(Post $post)
    {
        Like::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id
        ]);

        return back();
    }

    public function comment(Request $request, Post $post)
    {

        $request->validate([
            'texto' => 'required'
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'texto' => $request->texto
        ]);

        return back();
    }

    public function index()
{
    $posts = Post::with(['user','medias','likes','comments'])
    ->latest()
    ->get();

    return view('posts.index', compact('posts'));
}
}
