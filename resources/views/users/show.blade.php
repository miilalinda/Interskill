@extends('layouts.app')

@section('title', 'Perfil')

@section('content')

    <div class="container profile-page">

        <div class="profile-card">

            <img src="{{ $user->foto_perfil
                ? asset('storage/' . $user->foto_perfil)
                : 'https://ui-avatars.com/api/?name=' . urlencode($user->nome) }}"
                class="profile-img">

            <div class="profile-content">

                <div class="profile-top">
                    <div>
                        <h2>{{ $user->nome }}</h2>
                        <span>{{ '@' . $user->user_nome }}</span>
                    </div>

                    <a href="{{ route('users.edit', $user->id) }}" class="edit-btn">
                        Editar perfil
                    </a>
                </div>

                <div class="profile-stats">
                    <div>
                        <strong>{{ $user->posts_count }}</strong>
                        <span>Posts</span>
                    </div>

                    <div style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#followersModal">
                        <strong>{{ $user->followers_count }}</strong>
                        <span>Seguidores</span>
                    </div>

                    <div style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#followingModal">
                        <strong>{{ $user->following_count }}</strong>
                        <span>Seguindo</span>
                    </div>
                </div>

                @if ($user->bio)
                    <p class="bio">{{ $user->bio }}</p>
                @endif

                <div class="profile-skills">
                    @forelse($user->skills as $skill)
                        <span class="skill-badge">
                            {{ $skill->nome }}
                            <small>{{ nivelTexto($skill->pivot->nivel) }}</small>
                        </span>
                    @empty
                        <p class="empty-skills">Este usuário ainda não adicionou habilidades.</p>
                    @endforelse
                </div>

            </div>
        </div>

        <div class="post-create-card">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <textarea name="corpo" class="form-control post-input" placeholder="O que você está pensando?" rows="2"></textarea>

                <input type="file" name="arquivos[]" multiple class="form-control file-input">

                <button class="publish-btn">🚀 Publicar</button>
            </form>
        </div>

        <h4 class="section-title">Postagens</h4>

        @foreach ($posts as $post)
            <div class="post-card">

                <div class="post-header">
                    <img
                        src="{{ $post->user->foto_perfil
                            ? asset('storage/' . $post->user->foto_perfil)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->nome) }}">

                    <div>
                        <strong>{{ $post->user->nome }}</strong>
                        <small>{{ $post->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                @if ($post->medias->count())
                    <div class="post-img-container">
                        <img src="{{ asset('storage/' . $post->medias->first()->caminho) }}" class="post-img"
                            ondblclick="likeAjax({{ $post->id }}, this)">
                    </div>
                @endif

                <div class="post-body">
                    <div class="post-actions">
                        <button onclick="likeAjax({{ $post->id }}, this)" class="like-btn">
                            ❤️ <span class="like-count">{{ $post->likes->count() }}</span>
                        </button>

                        <span>💬 {{ $post->comments->count() }}</span>
                    </div>

                    @if ($post->corpo)
                        <p class="post-text">
                            <strong>{{ $post->user->nome }}</strong>
                            {{ $post->corpo }}
                        </p>
                    @endif

                    <input type="text" class="form-control comment-input" placeholder="Comente...">
                </div>
            </div>

            <!-- MODAL SEGUIDORES -->
            <div class="modal fade" id="followersModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark text-white">

                        <div class="modal-header">
                            <h5 class="modal-title">Seguidores</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            @forelse($user->followers as $follower)
                                <div class="d-flex justify-content-between align-items-center mb-3">

                                    <div>
                                        <strong>{{ $follower->nome }}</strong><br>
                                        <small>{{ '@' . $follower->user_nome }}</small>
                                    </div>

                                    @if (auth()->id() == $user->id)
                                        <form method="POST" action="{{ route('remove.follower', $follower->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Remover</button>
                                        </form>
                                    @endif

                                </div>
                            @empty
                                <p class="text-muted">Nenhum seguidor ainda.</p>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>


            <!-- MODAL SEGUIDORES -->
            <div class="modal fade" id="followersModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content insta-modal">

                        <div class="modal-header insta-modal-header">
                            <h5 class="modal-title">Seguidores</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body insta-modal-body">

                            <input type="text" class="insta-search" placeholder="🔍 Pesquisar">

                            @forelse($user->followers as $follower)
                                <div class="insta-user-row">

                                    <a href="{{ route('users.show', $follower->id) }}" class="insta-user-info">
                                        <img src="{{ $follower->foto_perfil
                                            ? asset('storage/' . $follower->foto_perfil)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($follower->nome) }}"
                                            class="insta-avatar">

                                        <div>
                                            <strong>{{ $follower->user_nome }}</strong>
                                            <span>{{ $follower->nome }}</span>
                                        </div>
                                    </a>

                                    @if (auth()->id() == $user->id)
                                        <form method="POST" action="{{ route('remove.follower', $follower->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="insta-remove-btn">Remover</button>
                                        </form>
                                    @endif

                                </div>
                            @empty
                                <p class="text-muted text-center mt-3">Nenhum seguidor ainda.</p>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>


            <!-- MODAL SEGUINDO -->
            <div class="modal fade" id="followingModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content insta-modal">

                        <div class="modal-header insta-modal-header">
                            <h5 class="modal-title">Seguindo</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body insta-modal-body">

                            <input type="text" class="insta-search" placeholder="🔍 Pesquisar">

                            @forelse($user->following as $following)
                                <div class="insta-user-row">

                                    <a href="{{ route('users.show', $following->id) }}" class="insta-user-info">
                                        <img src="{{ $following->foto_perfil
                                            ? asset('storage/' . $following->foto_perfil)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($following->nome) }}"
                                            class="insta-avatar">

                                        <div>
                                            <strong>{{ $following->user_nome }}</strong>
                                            <span>{{ $following->nome }}</span>
                                        </div>
                                    </a>

                                    @if (auth()->id() == $user->id)
                                        <form method="POST" action="{{ route('unfollow', $following->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="insta-remove-btn">Remover</button>
                                        </form>
                                    @endif

                                </div>
                            @empty
                                <p class="text-muted text-center mt-3">Você não está seguindo ninguém.</p>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    @php
        function nivelTexto($nivel)
        {
            return match ((int) $nivel) {
                0 => 'Aprendendo',
                1 => 'Básico',
                2 => 'Médio',
                3 => 'Avançado',
                4 => 'Pro',
                5 => 'Mestre',
                default => 'Não informado',
            };
        }
    @endphp

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
                    element.style.transform = "scale(1.2)";
                    setTimeout(() => element.style.transform = "scale(1)", 200);
                    element.style.color = data.liked ? "red" : "#cbd5f5";
                });
        }
    </script>

    <style>
        .profile-page {
            max-width: 850px;
        }

        .profile-card {
            display: grid;
            grid-template-columns: 170px 1fr;
            gap: 32px;
            padding: 35px 10px 30px;
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            color: white;
        }

        .profile-img {
            width: 145px;
            height: 145px;
            border-radius: 50%;
            object-fit: cover;
            padding: 4px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }

        .profile-content {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .profile-top {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .profile-top h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .profile-top span {
            color: #94a3b8;
            font-size: 14px;
        }

        .edit-btn {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-size: 14px;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .profile-stats {
            display: flex;
            gap: 35px;
            font-size: 15px;
        }

        .profile-stats strong {
            font-weight: 800;
        }

        .bio {
            margin: 0;
            color: #e5e7eb;
        }

        .profile-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-badge {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 999px;
            padding: 7px 12px;
            display: inline-flex;
            gap: 6px;
            align-items: center;
            font-size: 13px;
            font-weight: 600;
        }

        .skill-badge small {
            color: #a5b4fc;
            font-size: 12px;
        }

        .empty-skills {
            color: #94a3b8;
            font-size: 14px;
        }

        .post-create-card,
        .post-card {
            background: rgba(15, 23, 42, 0.78);
            backdrop-filter: blur(14px);
            border-radius: 22px;
            color: white;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.28);
        }

        .post-input,
        .file-input,
        .comment-input {
            background: rgba(255, 255, 255, 0.06);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            margin-bottom: 10px;
        }

        .post-input:focus,
        .comment-input:focus {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            box-shadow: none;
        }

        .publish-btn {
            width: 100%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 11px;
        }

        .section-title {
            color: white;
            font-weight: 800;
            margin-bottom: 16px;
        }

        .post-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .post-header img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
        }

        .post-header small {
            display: block;
            color: #94a3b8;
        }

        .post-img {
            width: 100%;
            border-radius: 18px;
            margin-bottom: 14px;
        }

        .post-actions {
            display: flex;
            gap: 18px;
            align-items: center;
            margin-bottom: 10px;
            color: #cbd5e1;
        }

        .like-btn {
            background: none;
            border: none;
            color: #cbd5e1;
            transition: 0.2s;
        }

        .post-text {
            color: #e5e7eb;
        }

        @media (max-width: 700px) {
            .profile-card {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .profile-img {
                margin: 0 auto;
            }

            .profile-top,
            .profile-stats,
            .profile-skills {
                justify-content: center;
            }

            .profile-top {
                flex-direction: column;
            }
        }
    </style>

@endsection
