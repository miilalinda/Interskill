@extends('layouts.app')

@section('title', 'Perfil')

@section('content')

    <div class="container">

        ```
        <!-- PERFIL -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <div class="row align-items-center">

                    <div class="col-md-3 text-center">
                        <img src="{{ $user->foto_perfil
                            ? asset('storage/' . $user->foto_perfil)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->nome) }}"
                            class="profile-img" width="100" height="100">
                    </div>

                    <div class="col-md-6">
                        <h3 class="fw-bold text-white mb-0">{{ $user->nome }}</h3>
                        <small class="text-muted">{{ '@' . $user->user_nome }}</small>
                        @if ($user->biografia)
                            <div
                                style="
        background: rgba(255,255,255,0.05);
        padding: 12px;
        border-radius: 10px;
        margin-top: 10px;
    ">
                                {{ $user->biografia }}
                            </div>
                        @endif

                        <div class="d-flex gap-4 mt-3">
                            <div>
                                <h5 class="text-white mb-0">{{ $user->posts_count }}</h5>
                                <small class="text-muted">Posts</small>
                            </div>
                            <div>
                                <h5 class="text-white mb-0">{{ $user->followers_count }}</h5>
                                <small class="text-muted">Seguidores</small>
                            </div>
                            <div>
                                <h5 class="text-white mb-0">{{ $user->following_count }}</h5>
                                <small class="text-muted">Seguindo</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 text-end">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-gradient px-4 rounded-pill">
                            ✏️ Editar
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <!-- POST -->
        <div class="card p-3 mb-4 border-0">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <textarea name="corpo" class="form-control mb-2" placeholder="O que você está pensando?" rows="2"></textarea>

                <input type="file" name="arquivos[]" multiple class="form-control mb-2">

                <button class="btn btn-gradient w-100">🚀 Publicar</button>
            </form>
        </div>

        <h4 class="mb-3 text-white">Postagens</h4>

        @foreach ($posts as $post)
            <div class="card mb-4 border-0">

                <!-- HEADER -->
                <div class="d-flex align-items-center p-3">
                    <img src="{{ $post->user->foto_perfil
                        ? asset('storage/' . $post->user->foto_perfil)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->nome) }}"
                        class="rounded-circle me-2" width="40" height="40">

                    <div>
                        <strong class="text-white">{{ $post->user->nome }}</strong>
                        <div class="text-muted small">
                            {{ $post->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <!-- IMG -->
                @if ($post->medias->count())
                    <div class="post-img-container">
                        <img src="{{ asset('storage/' . $post->medias->first()->caminho) }}" class="w-100 post-img"
                            ondblclick="likeAjax({{ $post->id }}, this)">
                    </div>
                @endif

                <!-- CONTENT -->
                <div class="p-3">

                    <!-- ACTIONS -->
                    <div class="d-flex gap-3 mb-2">
                        <button onclick="likeAjax({{ $post->id }}, this)" class="btn btn-sm border-0 like-btn">

                            ❤️ <span class="like-count">{{ $post->likes->count() }}</span>

                        </button>

                        <span class="text-muted">
                            💬 {{ $post->comments->count() }}
                        </span>
                    </div>

                    <!-- TEXTO BONITO -->
                    @if ($post->corpo)
                        <p class="post-text">
                            <strong>{{ $post->user->nome }}</strong>
                            {{ $post->corpo }}
                        </p>
                    @endif

                    <!-- INPUT -->
                    <input type="text" class="form-control comment-input" placeholder="Comente...">

                </div>

            </div>
        @endforeach
        ```

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

                    element.querySelector('.like-count').innerText = data.likes;

                    element.style.transform = "scale(1.3)";
                    setTimeout(() => element.style.transform = "scale(1)", 200);

                    element.style.color = data.liked ? "red" : "#cbd5f5";

                    const heart = document.createElement('div');
                    heart.innerHTML = "❤️";
                    heart.className = "heart-animation";

                    element.closest('.post-img-container')?.appendChild(heart);

                    setTimeout(() => heart.remove(), 800);
                });
        }
    </script>

    <style>
        /* CARD */
        .card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 18px;
            color: #fff;
        }

        /* PERFIL IMG */
        .profile-img {
            border-radius: 50%;
            padding: 3px;
            background: linear-gradient(45deg, #6366f1, #8b5cf6);
        }

        /* TEXTO */
        .post-text {
            color: #f1f5f9;
            line-height: 1.7;
            font-size: 15px;
            margin-top: 10px;
        }

        .post-text strong {
            color: #ffffff;
        }

        /* INPUT */
        .comment-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 20px;
            padding: 10px;
        }

        /* BOTÃO */
        .btn-gradient {
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            border: none;
            color: white;
        }

        /* IMG */
        .post-img {
            border-radius: 12px;
            transition: 0.3s;
        }

        .post-img:hover {
            filter: brightness(0.9);
        }

        /* LIKE */
        .like-btn {
            color: #cbd5f5;
            transition: 0.2s;
        }

        /* ❤️ ANIMAÇÃO */
        .heart-animation {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 70px;
            animation: pop 0.8s ease forwards;
        }

        @keyframes pop {
            0% {
                opacity: 0;
                transform: scale(0.5) translate(-50%, -50%);
            }

            50% {
                opacity: 1;
                transform: scale(1.3) translate(-50%, -50%);
            }

            100% {
                opacity: 0;
                transform: scale(1) translate(-50%, -50%);
            }
        }
    </style>

@endsection
