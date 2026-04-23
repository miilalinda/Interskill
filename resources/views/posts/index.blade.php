@extends('layouts.app')

@section('title', 'Feed')

@section('content')

<div class="container">

    <h3 class="mb-4">Feed</h3>

    @foreach ($posts as $post)
        <div class="card mb-4">

            <div class="card-body">

                <!-- NOME DO USUÁRIO -->
                <h6>{{ $post->user->nome }}</h6>

                <!-- DATA -->
                <p class="text-muted small">
                    {{ $post->created_at->format('d/m/Y H:i') }}
                </p>

                <!-- TEXTO DO POST -->
                @if ($post->corpo)
                    <p>{{ $post->corpo }}</p>
                @endif

                <!-- MIDIAS -->
                <div class="row g-2">
                    @foreach ($post->medias as $midia)

                        <div class="col-12">

                            @if ($midia->tipo == 'imagem')
                                <img src="{{ asset('storage/' . $midia->caminho) }}"
                            class="img-fluid rounded media-post">
                            @else
                                <video src="{{ asset('storage/' . $midia->caminho) }}"
                                    class="img-fluid rounded media-post" controls>
                                </video>
                            @endif

                            <!-- 🔥 BOTÃO DE EXCLUIR -->
                            <form action="{{ route('midia.destroy', $midia->id) }}" method="POST" class="mt-1">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger">
                                    🗑️ Excluir
                                </button>
                            </form>

                        </div>

                    @endforeach
                </div>

                <!-- ❤️ BOTÃO DE LIKE -->
                <button onclick="likePost({{ $post->id }})">
                    ❤️ <span id="like-count-{{ $post->id }}">
                        {{ $post->likes->count() }}
                    </span>
                </button>

                <!-- 💬 FORMULÁRIO DE COMENTÁRIO -->
                <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="mt-3">
                    @csrf

                    <textarea name="texto" class="form-control mb-2" placeholder="Digite um comentário..." required></textarea>

                    <button type="submit" class="btn btn-sm btn-primary">
                        Comentar
                    </button>
                </form>

                <!-- 📄 LISTA DE COMENTÁRIOS -->
                <div class="mt-3">
                    @foreach ($post->comments as $comment)
                        <p class="mb-1">
                            <strong>{{ $comment->user->nome ?? 'Usuário' }}:</strong>
                            {{ $comment->texto }}
                        </p>
                    @endforeach
                </div>

            </div>

        </div>
    @endforeach

</div>

<script>
    function likePost(postId) {

        fetch(`/posts/${postId}/like`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById(`like-count-${postId}`).innerText = data.likes;
        });

    }
    </script>

@endsection
