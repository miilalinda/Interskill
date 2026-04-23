@extends('layouts.app')

@section('title', 'Perfil')

@section('content')

<div class="container">

    <!-- PERFIL -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <div class="row align-items-center">

                <!-- FOTO -->
                <div class="col-md-3 text-center">
                    <img src="{{ $user->foto_perfil
                        ? asset('storage/' . $user->foto_perfil)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->nome) . '&background=0D6EFD&color=fff' }}"
                        class="profile-img"
                        width="100" height="100">
                </div>

                <!-- INFO -->
                <div class="col-md-6 d-flex flex-column justify-content-center">
                    <h3 class="mb-0 fw-bold">{{ $user->nome }}</h3>
                    <small class="text-muted">@{{ $user->user_nome }}</small>

                    <div class="d-flex gap-4 mt-3">

                        <div class="text-center">
                            <h5 class="fw-bold mb-0">{{ $user->posts_count }}</h5>
                            <small class="text-muted">Posts</small>
                        </div>

                        <div class="text-center">
                            <h5 class="fw-bold mb-0">{{ $user->followers_count }}</h5>
                            <small class="text-muted">Seguidores</small>
                        </div>

                        <div class="text-center">
                            <h5 class="fw-bold mb-0">{{ $user->following_count }}</h5>
                            <small class="text-muted">Seguindo</small>
                        </div>

                    </div>
                </div>

                <!-- BOTÕES -->
                <div class="col-md-3 text-md-end">

                    @if (auth()->id() == $user->id)
                        <a href="{{ route('users.edit', $user->id) }}"
                           class="btn btn-dark btn-sm px-4 rounded-pill shadow-sm">
                           ✏️ Editar
                        </a>
                    @else
                        <form method="POST" action="{{ route('parceria.solicitar', $user->id) }}">
                            @csrf
                            <button class="btn btn-primary mb-2 w-100">
                                Solicitar Parceria
                            </button>
                        </form>

                        <a href="{{ route('chat', $user->id) }}" class="btn btn-outline-secondary w-100">
                            Enviar Mensagem
                        </a>
                    @endif

                </div>

            </div>
        </div>
    </div>

    {{-- POSTAR --}}
    @if (auth()->id() == $user->id)
        <div class="card p-3 shadow-sm mb-4 border-0">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <textarea name="corpo" class="form-control mb-2"
                    placeholder="O que você está pensando?" rows="2"></textarea>

                <input type="file" name="arquivos[]" multiple class="form-control mb-2"
                    onchange="previewMidia(event)">

                <div id="preview" class="row"></div>

                <button class="btn btn-primary w-100">
                    🚀 Publicar
                </button>
            </form>
        </div>
    @endif

    <h4 class="mb-3">Postagens</h4>

    @foreach ($posts as $post)

        <div class="card mb-4 shadow-sm border-0">

            <!-- HEADER -->
            <div class="d-flex align-items-center p-3">

                <img src="{{ $post->user->foto_perfil
                    ? asset('storage/' . $post->user->foto_perfil)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->nome) }}"
                    class="rounded-circle me-2"
                    width="40" height="40">

                <div>
                    <strong>{{ $post->user->nome }}</strong>
                    <div class="text-muted" style="font-size:12px;">
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>

            </div>

            <!-- MIDIA -->
            @if ($post->medias->count())
                <div class="post-img-container">

                    <img src="{{ asset('storage/' . $post->medias->first()->caminho) }}"
                        class="w-100 post-img"
                        ondblclick="likeAjax({{ $post->id }}, this)">

                    {{-- DELETE --}}
                    @if (auth()->id() === $post->user_id)
                        <form action="{{ route('posts.destroy', $post->id) }}"
                            method="POST"
                            class="delete-btn">
                            @csrf
                            @method('DELETE')

                            <button onclick="return confirm('Apagar essa postagem?')">
                                🗑️
                            </button>
                        </form>
                    @endif

                </div>
            @endif

            <!-- AÇÕES -->
            <div class="p-3">

                <div class="d-flex gap-3 mb-2">

                    <button onclick="likeAjax({{ $post->id }}, this)"
                        class="btn btn-sm border-0 like-btn">

                        ❤️ <span class="like-count">
                            {{ $post->likes->count() }}
                        </span>

                    </button>

                    <span class="text-muted">
                        💬 {{ $post->comments->count() }}
                    </span>

                </div>

                @if ($post->corpo)
                    <p class="mb-2 text-dark">
                        <strong>{{ $post->user->nome }}</strong> {{ $post->corpo }}
                    </p>
                @endif

                <!-- COMENTAR -->
                <form action="{{ route('posts.comment', $post) }}" method="POST">
                    @csrf
                    <input type="text" name="texto"
                        class="form-control form-control-sm comment-input"
                        placeholder="Adicione um comentário...">
                </form>

            </div>

        </div>

    @endforeach

</div>

{{-- JS --}}
<script>
function previewMidia(event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';

    for (let file of event.target.files) {
        const url = URL.createObjectURL(file);

        const col = document.createElement("div");
        col.className = "col-4 mb-2";

        if (file.type.startsWith("image")) {
            col.innerHTML = `<img src="${url}" class="img-fluid rounded">`;
        } else {
            col.innerHTML = `<video src="${url}" controls class="img-fluid rounded"></video>`;
        }

        preview.appendChild(col);
    }
}

// ❤️ LIKE AJAX + ANIMAÇÃO
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

        // atualiza número
        element.closest('.p-3').querySelector('.like-count').innerText = data.likes;

        // animação botão
        element.style.transform = "scale(1.3)";
        setTimeout(() => element.style.transform = "scale(1)", 200);

        // cor
        element.style.color = data.liked ? "red" : "black";

        // ❤️ coração gigante
        const heart = document.createElement('div');
        heart.innerHTML = "❤️";
        heart.className = "heart-animation";

        element.closest('.post-img-container')?.appendChild(heart);

        setTimeout(() => heart.remove(), 800);
    });
}
</script>

<style>

/* PERFIL */
.profile-img {
    border-radius: 50%;
    padding: 3px;
    background: linear-gradient(45deg, #0d6efd, #6f42c1);
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    transition: 0.3s;
}
.profile-img:hover {
    transform: scale(1.08);
}

/* CARD */
.card {
    border-radius: 15px;
    transition: 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}

/* IMAGEM */
.post-img-container {
    position: relative;
}
.post-img {
    border-radius: 12px;
    transition: 0.3s;
}
.post-img:hover {
    filter: brightness(95%);
}

/* DELETE */
.delete-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0;
    transition: 0.3s;
}
.delete-btn button {
    background: rgba(0,0,0,0.6);
    border: none;
    color: white;
    padding: 8px;
    border-radius: 50%;
}
.post-img-container:hover .delete-btn {
    opacity: 1;
}

/* LIKE */
.like-btn {
    transition: 0.2s;
}

/* INPUT */
.comment-input {
    background: #f0f2f5;
    border-radius: 20px;
    padding: 10px;
    border: none;
}

/* ❤️ ANIMAÇÃO */
.heart-animation {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(1);
    font-size: 70px;
    animation: pop 0.8s ease forwards;
    pointer-events: none;
}

@keyframes pop {
    0% { transform: translate(-50%, -50%) scale(0.5); opacity: 0; }
    50% { transform: translate(-50%, -50%) scale(1.3); opacity: 1; }
    100% { transform: translate(-50%, -50%) scale(1); opacity: 0; }
}

</style>

@endsection
