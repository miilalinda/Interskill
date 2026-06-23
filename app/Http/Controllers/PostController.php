<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'corpo' => 'required_without:arquivos|nullable|string',
            'arquivos' => 'nullable|array',
            'arquivos.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:50000',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'corpo' => $request->corpo,
        ]);

        if ($request->hasFile('arquivos')) {
            foreach ($request->file('arquivos') as $arquivo) {
                $caminho = $arquivo->store('posts', 'public');

                $tipo = str_contains($arquivo->getMimeType(), 'video') ? 'video' : 'imagem';

                PostMedia::create([
                    'post_id' => $post->id,
                    'caminho' => $caminho,
                    'tipo' => $tipo,
                ]);
            }
        }

        return back();
    }

    public function destroy(Post $post)
    {
        if ((int)$post->user_id !== (int)auth()->id()) {
            abort(403);
        }

        foreach ($post->medias as $media) {
            Storage::disk('public')->delete($media->caminho);
        }

        $post->delete();

        return back();
    }

    public function like(Post $post)
    {
        $post->likes()->toggle(auth()->id());

        return response()->json([
            'likes' => $post->likes()->count()
        ]);
    }

    public function comment(Request $request, Post $post)
{
    $request->validate([
        'texto' => 'required|string|max:500'
    ]);

    $comment = Comment::create([
        'user_id' => auth()->id(),
        'post_id' => $post->id,
        'texto' => $request->texto,
    ]);

    return response()->json([
        'success' => true,
        'user' => auth()->user()->user_nome,
        'texto' => $comment->texto
    ]);
}

    public function feed()
    {
        $posts = Post::with([
            'user',
            'medias',
            'likes',
            'comments.user'
        ])->latest()->get();

        return view('posts.feed', compact('posts'));
    }

    public function show($post)
    {
        return "Página do post " . $post;
    }
}
