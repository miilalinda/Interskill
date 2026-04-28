@extends('layouts.app')

@section('content')
    <div class="container" style="max-width:800px;"> <!-- AUMENTEI LARGURA -->

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-primary">Feed</h4>

            <!-- BOTÃO ATUALIZAR -->
            <button onclick="location.reload()" class="btn btn-outline-primary btn-sm">
                🔄 Atualizar
            </button>
        </div>

        @foreach ($posts as $post)
            <div class="card mb-4 shadow-sm rounded-4">

                <!-- HEADER -->
                <div class="d-flex justify-content-between p-3">

                    <div class="d-flex align-items-center">

                        <!-- LINK PERFIL -->
                        <a href="{{ route('users.show', $post->user->id) }}">
                            <img src="{{ $post->user->foto_perfil
                                ? asset('storage/' . $post->user->foto_perfil)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->nome) }}"
                                class="rounded-circle me-2" width="45" height="45">
                        </a>

                        <div>
                            <!-- LINK PERFIL -->
                            <a href="{{ route('users.show', $post->user->id) }}" class="text-decoration-none text-dark">
                                <strong>{{ $post->user->nome }}</strong>
                            </a><br>

                            <small class="text-muted">
                                {{ $post->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>

                    <!-- EXCLUIR -->
                    @if ((int) $post->user_id === (int) auth()->id())
                        <form method="POST" action="{{ route('posts.destroy', $post->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    @endif

                </div>

                <!-- IMAGEM COM LINK -->
                @if ($post->medias->count())
                    <img src="{{ asset('storage/' . $post->medias->first()->caminho) }}" class="w-100"
                        style="cursor:pointer;" ondblclick="likeAjax({{ $post->id }}, this)">
                @endif

                <!-- CONTEÚDO -->
                <div class="p-3">

                    <!-- DESCRIÇÃO -->
                    <p class="mt-2" style="color:#333;">
                        <a href="{{ route('users.show', $post->user->id) }}" class="text-decoration-none text-dark">
                            <strong>{{ $post->user->nome }}</strong>
                        </a>
                        {{ $post->corpo }}
                    </p>

                    <!-- CURTIDAS (AGORA DEPOIS DA DESCRIÇÃO) -->
                    <button onclick="likeAjax({{ $post->id }}, this)" class="btn border-0 p-0 mb-2 text-danger">
                        ❤️ <span class="like-count">{{ $post->likes->count() }}</span> curtidas
                    </button>

                    <!-- COMENTÁRIOS -->
                    <div class="mt-3">
                        @foreach ($post->comments as $comment)
                            <div class="d-flex mb-2">

                                <img src="{{ $comment->user->foto_perfil
                                    ? asset('storage/' . $comment->user->foto_perfil)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->nome) }}"
                                    class="rounded-circle me-2" width="30" height="30">

                                <div>
                                    <strong style="color:#222;">{{ $comment->user->nome }}</strong>
                                    <p class="mb-0" style="color:#555;">{{ $comment->texto }}</p>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- FORM COMENTAR -->
                    <form action="{{ route('posts.comment', $post->id) }}" method="POST">
                        @csrf
                        <input type="text" name="texto" class="form-control mt-2" placeholder="Comente..." required>
                    </form>

                </div>

            </div>
        @endforeach

    </div>

    <script>
        function likeAjax(postId, element) {

            fetch(`/posts/${postId}/like`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => {

                    let count = element.querySelector('.like-count');

                    if (!count) {
                        count = element.parentElement.querySelector('.like-count');
                    }

                    count.innerText = data.likes;
                });
        }
    </script>
@endsection
