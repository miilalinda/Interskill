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
                        <h2 class="profile-name">
                            {{ $user->nome }}
                        </h2>

                        <span class="profile-username">
                            {{ '@' . $user->user_nome }}
                        </span>
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

                <div class="profile-meta">
                    <span>💻 Desenvolvedor</span>
                    <span>📍 São Paulo</span>
                    <span>🌐 interskill.com/{{ $user->user_nome }}</span>
                </div>

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

        {{-- DESTAQUES --}}
        <div class="stories-row">

            @can('update', $user)
                <button class="story-item story-button" data-bs-toggle="modal" data-bs-target="#createHighlightModal">

                    <div class="story-circle">
                        <div class="story-plus">+</div>
                    </div>

                    <span>Novo</span>
                </button>
            @endcan

            @foreach ($user->highlights as $highlight)
                <div class="modal fade" id="highlightModal{{ $highlight->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered highlight-dialog">
                        <div class="modal-content highlight-modal">

                            <div class="highlight-header">
                                <h5>{{ $highlight->titulo }}</h5>

                                <button type="button" class="highlight-close" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <div class="highlight-content">

                                @if ($highlight->imagem)
                                    <img src="{{ asset('storage/' . $highlight->imagem) }}" class="highlight-image">
                                @else
                                    <div class="highlight-empty">
                                        <i class="bi bi-star"></i>
                                        <p>Sem imagem</p>
                                    </div>
                                @endif

                                @if (auth()->id() == $user->id)
                                    <form method="POST" action="{{ route('highlight.remove', $highlight->id) }}"
                                        class="highlight-actions">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn-remove-highlight">
                                            <i class="bi bi-trash"></i>
                                            Remover destaque
                                        </button>
                                    </form>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

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

        <div class="profile-tabs">
            <button class="active">
                <i class="bi bi-grid-3x3"></i>
                POSTS
            </button>

            <button>
                <i class="bi bi-play-btn"></i>
                VÍDEOS
            </button>

            <button>
                <i class="bi bi-bookmark"></i>
                SALVOS
            </button>
        </div>

        <div class="posts-grid">

            @forelse($posts as $post)
                <div class="grid-post">

                    @if ($post->medias->count())
                        <img src="{{ asset('storage/' . $post->medias->first()->caminho) }}">
                    @else
                        <div class="grid-post-text">
                            {{ Str::limit($post->corpo, 80) }}
                        </div>
                    @endif

                    <div class="grid-overlay">
                        <span>❤️ {{ $post->likes->count() }}</span>
                        <span>💬 {{ $post->comments->count() }}</span>
                    </div>

                </div>

            @empty
                <p class="text-muted">Nenhuma postagem ainda.</p>
            @endforelse

        </div>

    </div>

    <!-- MODAL CRIAR DESTAQUE -->
    <div class="modal fade" id="createHighlightModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content insta-modal">

                <div class="insta-header">
                    <h5>Novo destaque</h5>
                    <button type="button" class="insta-x" data-bs-dismiss="modal">×</button>
                </div>

                <div class="insta-body">
                    <form method="POST" action="{{ route('highlights.store') }}" enctype="multipart/form-data">
                        @csrf

                        <input type="text" name="titulo" class="form-control mb-3" placeholder="Nome do destaque"
                            required>

                        <input type="file" name="imagem" class="form-control mb-3" accept="image/*">

                        <input type="file" name="audio" class="form-control mb-3" accept="audio/*">

                        <button class="publish-btn" type="submit">
                            Criar destaque
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAIS DOS DESTAQUES --}}
@foreach($user->highlights as $highlight)

<div class="modal fade" id="highlightModal{{ $highlight->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered highlight-dialog">

        <div class="modal-content highlight-modal">

            <div class="highlight-header">

                <h5>{{ $highlight->titulo }}</h5>

                <button type="button"
                        class="highlight-close"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-lg"></i>

                </button>

            </div>

            <div class="highlight-content">

                @if($highlight->imagem)

                    <img src="{{ asset('storage/' . $highlight->imagem) }}"
                         class="highlight-image">

                @else

                    <div class="highlight-empty">

                        <i class="bi bi-star"></i>

                        <p>Sem imagem</p>

                    </div>

                @endif

                @if(auth()->id() == $user->id)

                    <form method="POST"
                          action="{{ route('highlight.remove', $highlight->id) }}"
                          class="highlight-actions">

                        @csrf
                        @method('DELETE')

                        <button class="btn-remove-highlight">

                            <i class="bi bi-trash"></i>

                            Remover destaque

                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>
</div>

@endforeach
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

                            @if (auth()->id() == $user->id)
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

                            @if (auth()->id() == $user->id)
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

    <style>
        .profile-page {
            max-width: 950px;
        }

        .profile-header {
            display: flex;
            gap: 55px;
            align-items: center;
            padding: 45px 0 35px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            margin-bottom: 25px;
        }

        .profile-avatar {
            width: 155px;
            height: 155px;
            border-radius: 50%;
            object-fit: cover;
            padding: 4px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            animation: glow 3s infinite linear;
        }

        @keyframes glow {
            0% {
                box-shadow: 0 0 10px #8b5cf6;
            }

            50% {
                box-shadow: 0 0 25px #6366f1;
            }

            100% {
                box-shadow: 0 0 10px #8b5cf6;
            }
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
            background: rgba(255, 255, 255, 0.08);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.12);
            text-decoration: none;
            border-radius: 10px;
            padding: 8px 18px;
            font-size: 14px;
        }

        .btn-edit-profile:hover {
            background: rgba(255, 255, 255, 0.14);
            color: white;
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

        .profile-meta {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 10px;
            margin-bottom: 14px;
            color: #94a3b8;
            font-size: 14px;
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

        .empty-skills {
            color: #94a3b8;
            font-size: 14px;
        }

        .stories-row {
            display: flex;
            gap: 22px;
            margin: 28px 0 30px;
            padding-bottom: 10px;
            overflow-x: auto;
        }

        .story-item {
            width: 82px;
            text-align: center;
            color: white;
            font-size: 13px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .story-button {
            background: transparent;
            border: none;
            padding: 0;
        }

        .story-circle {
            width: 78px;
            height: 78px;
            border-radius: 50%;
            background: #111827;
            border: 2px solid rgba(255, 255, 255, 0.35);
            padding: 3px;
            margin-bottom: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .story-circle img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .story-plus {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: white;
        }

        .story-item span {
            display: block;
            max-width: 82px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .highlight-preview {
            width: 100%;
            max-height: 480px;
            object-fit: cover;
            border-radius: 15px;
        }

        .highlight-empty {
            min-height: 220px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
        }

        .highlight-empty i {
            font-size: 42px;
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

        .profile-tabs {
            display: flex;
            justify-content: center;
            gap: 40px;
            border-top: 1px solid rgba(255, 255, 255, .08);
            margin-top: 30px;
            margin-bottom: 25px;
        }

        .profile-tabs button {
            background: none;
            border: none;
            color: #94a3b8;
            padding: 18px;
            font-weight: 700;
        }

        .profile-tabs button.active {
            color: white;
            border-top: 2px solid #8b5cf6;
        }

        .posts-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .grid-post {
            overflow: hidden;
            border-radius: 16px;
            position: relative;
            background: rgba(15, 23, 42, .82);
        }

        .grid-post img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            transition: .3s;
        }

        .grid-post-text {
            width: 100%;
            aspect-ratio: 1/1;
            padding: 18px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .grid-post:hover img {
            transform: scale(1.05);
            opacity: .85;
        }

        .grid-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            display: flex;
            gap: 18px;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: .2s;
            color: white;
            font-weight: 700;
        }

        .grid-post:hover .grid-overlay {
            opacity: 1;
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
            .profile-skills,
            .profile-meta {
                justify-content: center;
            }

            .profile-top-new {
                flex-direction: column;
            }

            .posts-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .highlight-dialog{
    max-width:760px;
}

.highlight-modal{
    background:#0b1120 !important;
    border:1px solid rgba(255,255,255,.12);
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 35px 90px rgba(0,0,0,.65);
}

.highlight-header{
    height:68px;
    display:flex;
    align-items:center;
    justify-content:center;
    position:relative;
    border-bottom:1px solid rgba(255,255,255,.08);
    background:rgba(255,255,255,.02);
}

.highlight-header h5{
    margin:0;
    font-size:24px;
    font-weight:800;
    color:white;
}

.highlight-close{
    position:absolute;
    right:18px;
    top:14px;
    width:40px;
    height:40px;
    border:none;
    border-radius:12px;
    background:rgba(255,255,255,.08);
    color:white;
    transition:.2s;
}

.highlight-close:hover{
    background:rgba(255,255,255,.14);
    transform:scale(1.05);
}

.highlight-content{
    padding:18px;
}

.highlight-image{
    width:100%;
    max-height:650px;
    object-fit:cover;
    border-radius:18px;
    display:block;
}

.highlight-actions{
    margin-top:18px;
}

.btn-remove-highlight{
    width:100%;
    border:none;
    border-radius:16px;
    background:linear-gradient(135deg,#dc2626,#e11d48);
    color:white;
    padding:15px;
    font-weight:700;
    font-size:15px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    transition:.2s;
}

.btn-remove-highlight:hover{
    transform:translateY(-2px);
    opacity:.92;
}
        }
    </style>

@endsection
