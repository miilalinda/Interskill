@extends('layouts.app')

@section('title', 'Perfil')

@section('content')

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

<div class="container profile-page">

    <div class="profile-header">

        <div class="profile-avatar-area">
            <img src="{{ $user->foto_perfil
                ? asset('storage/' . $user->foto_perfil)
                : 'https://ui-avatars.com/api/?name=' . urlencode($user->nome) }}"
                class="profile-avatar">
        </div>

        <div class="profile-info-area">

            <div class="profile-top-new">
                <div>
                    <h2 class="profile-name">{{ $user->nome }}</h2>
                    <span class="profile-username">{{ '@' . $user->user_nome }}</span>
                </div>

                @can('update', $user)
                    <a href="{{ route('users.edit', $user->id) }}" class="btn-edit-profile">
                        Editar perfil
                    </a>
                @endcan
            </div>

            <div class="profile-stats-new">
                <div>
                    <strong>{{ $user->posts_count }}</strong>
                    <span>Posts</span>
                </div>

                <div class="stat-click" data-bs-toggle="modal" data-bs-target="#followersModal">
                    <strong>{{ $user->followers_count }}</strong>
                    <span>Seguidores</span>
                </div>

                <div class="stat-click" data-bs-toggle="modal" data-bs-target="#followingModal">
                    <strong>{{ $user->following_count }}</strong>
                    <span>Seguindo</span>
                </div>
            </div>

            @if ($user->bio)
                <p class="profile-bio">{{ $user->bio }}</p>
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

    @can('update', $user)
        <div class="post-create-card">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <textarea name="corpo" class="form-control post-input" placeholder="O que você está pensando?" rows="2"></textarea>

                <input type="file" name="arquivos[]" multiple class="form-control file-input">

                <button class="publish-btn">🚀 Publicar</button>
            </form>
        </div>
    @endcan

    <h4 class="section-title">Postagens</h4>

    @foreach ($posts as $post)

        <div class="instagram-post">

            <div class="instagram-header">

                <div class="d-flex align-items-center gap-2">

                    <img src="{{ $post->user->foto_perfil
                        ? asset('storage/' . $post->user->foto_perfil)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->nome) }}"
                        class="insta-post-avatar">

                    <div>
                        <strong class="post-username">
                            {{ $post->user->user_nome }}
                        </strong>

                        <small class="d-block text-muted">
                            {{ $post->created_at->diffForHumans() }}
                        </small>
                    </div>

                </div>

                <button class="post-options">
                    <i class="bi bi-three-dots"></i>
                </button>

            </div>

            @if ($post->medias->count())
                <div class="insta-post-media">
                    <img src="{{ asset('storage/' . $post->medias->first()->caminho) }}"
                        class="insta-post-image"
                        ondblclick="likeAjax({{ $post->id }}, this)">
                </div>
            @endif

            <div class="insta-post-actions">

                <div class="d-flex gap-3">

                    <button onclick="likeAjax({{ $post->id }}, this)" class="insta-action-btn">
                        <i class="bi bi-heart"></i>
                    </button>

                    <button class="insta-action-btn">
                        <i class="bi bi-chat"></i>
                    </button>

                    <button class="insta-action-btn">
                        <i class="bi bi-send"></i>
                    </button>

                </div>

                <button class="insta-action-btn">
                    <i class="bi bi-bookmark"></i>
                </button>

            </div>

            <div class="insta-likes">
                <strong>
                    <span class="like-count">{{ $post->likes->count() }}</span>
                    curtidas
                </strong>
            </div>

            @if ($post->corpo)
                <div class="insta-caption">
                    <strong>{{ $post->user->user_nome }}</strong>
                    {{ $post->corpo }}
                </div>
            @endif

            <div class="insta-comments">
                @foreach($post->comments->take(2) as $comment)
                    <div class="comment-row">
                        <strong>{{ $comment->user->user_nome }}</strong>
                        {{ $comment->texto }}
                    </div>
                @endforeach
            </div>

            <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="comment-form">
                @csrf

                <input type="text"
                    name="texto"
                    placeholder="Adicione um comentário..."
                    class="comment-input-insta"
                    required>

                <button class="publish-comment">
                    Publicar
                </button>
            </form>

        </div>

    @endforeach

</div>

<!-- MODAL SEGUIDORES -->
<div class="modal fade" id="followersModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered insta-dialog">
        <div class="modal-content insta-modal">

            <div class="insta-header">
                <h5>Seguidores</h5>
                <button type="button" class="insta-x" data-bs-dismiss="modal">×</button>
            </div>

            <div class="insta-body">
                <input type="text" class="insta-search" placeholder="🔍 Pesquisar">

                @forelse($user->followers as $follower)
                    <div class="insta-row">

                        <a href="{{ route('users.show', $follower->id) }}" class="insta-left">
                            <img src="{{ $follower->foto_perfil
                                ? asset('storage/' . $follower->foto_perfil)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($follower->nome) }}"
                                class="insta-avatar">

                            <div>
                                <strong>{{ $follower->user_nome }}</strong>
                                <span>{{ $follower->nome }}</span>
                            </div>
                        </a>

                        @if(auth()->id() == $user->id)
                            <form method="POST" action="{{ route('remove.follower', $follower->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="insta-btn">Remover</button>
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
    <div class="modal-dialog modal-dialog-centered insta-dialog">
        <div class="modal-content insta-modal">

            <div class="insta-header">
                <h5>Seguindo</h5>
                <button type="button" class="insta-x" data-bs-dismiss="modal">×</button>
            </div>

            <div class="insta-body">
                <input type="text" class="insta-search" placeholder="🔍 Pesquisar">

                @forelse($user->following as $following)
                    <div class="insta-row">

                        <a href="{{ route('users.show', $following->id) }}" class="insta-left">
                            <img src="{{ $following->foto_perfil
                                ? asset('storage/' . $following->foto_perfil)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($following->nome) }}"
                                class="insta-avatar">

                            <div>
                                <strong>{{ $following->user_nome }}</strong>
                                <span>{{ $following->nome }}</span>
                            </div>
                        </a>

                        @if(auth()->id() == $user->id)
                            <form method="POST" action="{{ route('unfollow', $following->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="insta-btn">Remover</button>
                            </form>
                        @endif

                    </div>
                @empty
                    <p class="text-muted text-center mt-3">Você não segue ninguém.</p>
                @endforelse
            </div>

        </div>
    </div>
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
            const count = element.closest('.instagram-post').querySelector('.like-count');

            if (count) {
                count.innerText = data.likes;
            }

            element.style.transform = "scale(1.2)";
            setTimeout(() => element.style.transform = "scale(1)", 200);
        });
    }
</script>

<style>
.profile-page {
    max-width: 950px;
}

.profile-header {
    display: flex;
    gap: 55px;
    align-items: center;
    padding: 45px 0 35px;
    border-bottom: 1px solid rgba(255,255,255,0.12);
    margin-bottom: 25px;
}

.profile-avatar {
    width: 155px;
    height: 155px;
    border-radius: 50%;
    object-fit: cover;
    padding: 4px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
}

.profile-info-area {
    flex: 1;
}

.profile-top-new {
    display: flex;
    align-items: center;
    gap: 22px;
    margin-bottom: 22px;
}

.profile-name {
    font-size: 30px;
    font-weight: 700;
    margin: 0;
}

.profile-username {
    color: #94a3b8;
    font-size: 15px;
}

.btn-edit-profile {
    background: rgba(255,255,255,0.08);
    color: white;
    border: 1px solid rgba(255,255,255,0.12);
    text-decoration: none;
    border-radius: 10px;
    padding: 8px 18px;
    font-size: 14px;
}

.profile-stats-new {
    display: flex;
    gap: 40px;
    margin-bottom: 18px;
}

.profile-stats-new div {
    display: flex;
    gap: 6px;
    align-items: center;
}

.stat-click {
    cursor: pointer;
}

.profile-bio {
    margin: 0 0 14px;
    color: #e5e7eb;
}

.profile-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.skill-badge {
    background: rgba(255, 255, 255, 0.08);
    border-radius: 999px;
    padding: 7px 12px;
    display: inline-flex;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
}

.skill-badge small {
    color: #a5b4fc;
}

.post-create-card {
    background: rgba(15, 23, 42, 0.78);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    padding: 20px;
    margin-bottom: 24px;
}

.post-input,
.file-input {
    background: rgba(255, 255, 255, 0.06);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 14px;
    margin-bottom: 10px;
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

/* POSTS ESTILO INSTAGRAM */
.instagram-post {
    background: rgba(15,23,42,.82);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 24px;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 15px 40px rgba(0,0,0,.28);
}

.instagram-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 18px;
}

.insta-post-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
}

.post-options {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
}

.insta-post-media {
    background: black;
}

.insta-post-image {
    width: 100%;
    max-height: 700px;
    object-fit: cover;
}

.insta-post-actions {
    display: flex;
    justify-content: space-between;
    padding: 14px 16px 8px;
}

.insta-action-btn {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    transition: .2s;
}

.insta-action-btn:hover {
    transform: scale(1.1);
    color: #a78bfa;
}

.insta-likes {
    padding: 0 18px;
    margin-bottom: 6px;
}

.insta-caption {
    padding: 0 18px;
    margin-bottom: 12px;
    color: #f1f5f9;
}

.insta-comments {
    padding: 0 18px;
    margin-bottom: 14px;
}

.comment-row {
    margin-bottom: 6px;
    color: #e2e8f0;
    font-size: 14px;
}

.comment-form {
    border-top: 1px solid rgba(255,255,255,.08);
    display: flex;
    align-items: center;
    padding: 12px 14px;
}

.comment-input-insta {
    flex: 1;
    background: none;
    border: none;
    outline: none;
    color: white;
}

.publish-comment {
    background: none;
    border: none;
    color: #8b5cf6;
    font-weight: 700;
}

/* MODAL */
.insta-dialog {
    max-width: 560px;
}

.insta-modal {
    background: #202124 !important;
    color: white;
    border-radius: 8px !important;
    border: 1px solid #303136;
}

.insta-header {
    height: 52px;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    border-bottom: 1px solid #36373b;
}

.insta-x {
    position: absolute;
    right: 14px;
    top: 6px;
    background: transparent;
    border: 2px solid white;
    color: white;
    font-size: 32px;
    line-height: 26px;
    width: 38px;
    height: 38px;
}

.insta-body {
    padding: 12px 16px;
    max-height: 350px;
    overflow-y: auto;
}

.insta-search {
    width: 100%;
    background: #363636 !important;
    border: none;
    border-radius: 8px;
    color: white;
    padding: 9px 12px;
    margin-bottom: 12px;
}

.insta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 0;
}

.insta-left {
    display: flex;
    align-items: center;
    gap: 12px;
    color: white;
    text-decoration: none;
}

.insta-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}

.insta-left strong {
    display: block;
    font-size: 14px;
}

.insta-left span {
    display: block;
    font-size: 13px;
    color: #a8a8a8;
}

.insta-btn {
    background: #2f3035 !important;
    color: white !important;
    border: none !important;
    border-radius: 8px;
    padding: 7px 17px;
    font-weight: 700;
}

@media (max-width: 700px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }

    .profile-top-new,
    .profile-stats-new,
    .profile-skills {
        justify-content: center;
    }

    .profile-top-new {
        flex-direction: column;
    }
}
</style>

@endsection
