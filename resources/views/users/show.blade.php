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

        <div class="profile-header-modern">

            <div class="profile-avatar-area">
                <img src="{{ $user->foto_perfil
                    ? asset('storage/' . $user->foto_perfil)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->nome) }}"
                    class="profile-avatar-modern">
            </div>

            <div class="profile-content">

                <div class="profile-top">
                    <div>
                        <h1 class="profile-name-modern">
                            {{ $user->nome }}
                        </h1>
                        <span class="profile-username-modern">
                            {{ '@'.$user->user_nome }}
                        </span>
                    </div>

                    @if(auth()->id() == $user->id)
                        <a href="#" class="edit-profile-btn">
                            Editar perfil
                        </a>
                    @endif
                </div>

                <div class="stats-row">
                    <div class="stat-card">
                        <strong>{{ $posts->count() }}</strong>
                        <span>Posts</span>
                    </div>

                    <div class="stat-card" data-bs-toggle="modal" data-bs-target="#followersModal">
                        <strong>{{ $user->followers->count() }}</strong>
                        <span>Seguidores</span>
                    </div>

                    <div class="stat-card" data-bs-toggle="modal" data-bs-target="#followingModal">
                        <strong>{{ $user->following->count() }}</strong>
                        <span>Seguindo</span>
                    </div>
                </div>

                @if($user->biografia)
                    <div class="bio-card">
                        {{ $user->biografia }}
                    </div>
                @endif

            </div>

        </div>

        <div class="skills-section">

            <div class="skills-header">
                <h3>
                    <i class="bi bi-lightning-charge-fill"></i>
                    Habilidades
                </h3>
            </div>

            <div class="skills-grid-modern">
                @forelse($user->skills as $skill)
                    <div class="skill-modern-card">
                        <div class="skill-icon">
                            <i class="bi bi-stars"></i>
                        </div>
                        <div>
                            <div class="skill-title">
                                {{ $skill->nome }}
                            </div>
                            <small class="skill-level" style="color: #a5b4fc; font-weight: 500;">
                                {{ nivelTexto($skill->pivot->nivel) }}
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="empty-skills-modern">
                        Nenhuma habilidade cadastrada.
                    </div>
                @endforelse
            </div>

        </div>

        {{-- DESTAQUES --}}
        <div class="stories-row">
            @can('update', $user)
                <button class="story-item story-button" data-bs-toggle="modal" data-bs-target="#createHighlightModal">
                    <div class="story-circle">
                        <div class="story-plus">
                            <i class="bi bi-plus-circle-fill"></i>
                        </div>
                    </div>
                    <span>Novo</span>
                </button>
            @endcan

            @foreach ($user->highlights as $highlight)
                <button class="story-item story-button" data-bs-toggle="modal" data-bs-target="#highlightModal{{ $highlight->id }}">
                    <div class="story-circle">
                        @if ($highlight->imagem)
                            <img src="{{ asset('storage/' . $highlight->imagem) }}">
                        @else
                            <div class="story-plus"><i class="bi bi-star-fill" style="font-size: 20px; color: #8b5cf6;"></i></div>
                        @endif
                    </div>
                    <span>{{ $highlight->titulo }}</span>
                </button>
            @endforeach
        </div>

        @can('update', $user)
            <div class="post-create-card">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <textarea name="corpo" class="form-control post-input" placeholder="O que você está pensando?" rows="2" style="background: rgba(255, 255, 255, 0.06); color: white; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 14px; padding: 12px;"></textarea>

                    <div class="post-tools">
                        <label style="flex: 1; text-align: center; padding: 15px; border-radius: 14px; background: rgba(255, 255, 255, .04); border: 1px solid rgba(255, 255, 255, .08); color: white; cursor: pointer; display: block; transition: .3s;" onmouseover="this.style.borderColor='#8b5cf6'; this.style.background='rgba(139, 92, 246, .1)'" onmouseout="this.style.borderColor='rgba(255, 255, 255, .08)'; this.style.background='rgba(255, 255, 255, .04)'">
                            <i class="bi bi-cloud-arrow-up-fill" style="color: #a5b4fc; margin-right: 6px;"></i>
                            Anexar Imagens, Vídeos ou Documentos
                            <input type="file" name="arquivos[]" multiple class="form-control" style="display: none;">
                        </label>
                    </div>

                    <button class="publish-btn" style="margin-top: 10px;">🚀 Publicar</button>
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
                        <span>👁️ {{ $post->visualizacoes }}</span>
                    </div>
                </div>
            @empty
                <p class="text-muted" style="grid-column: 1/-1; text-align: center; padding: 40px 0;">Nenhuma postagem ainda.</p>
            @endforelse
        </div>

    </div>

    <div class="modal fade" id="createHighlightModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content insta-modal">
                <div class="insta-header">
                    <h5>Novo destaque</h5>
                    <button type="button" class="insta-x" data-bs-dismiss="modal" style="border:none; background:transparent; font-size:24px;">×</button>
                </div>
                <div class="insta-body">
                    <form method="POST" action="{{ route('highlights.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="titulo" class="form-control mb-3" placeholder="Nome do destaque" required style="background: #363636; border: none; color: white; border-radius: 8px; padding: 10px;">
                        <label class="mb-2 block text-sm text-muted">Imagem de capa:</label>
                        <input type="file" name="imagem" class="form-control mb-3" accept="image/*" style="background: #363636; border: none; color: white;">
                        <label class="mb-2 block text-sm text-muted">Áudio de fundo (Opcional):</label>
                        <input type="file" name="audio" class="form-control mb-3" accept="audio/*" style="background: #363636; border: none; color: white;">
                        <button type="submit" class="publish-btn" style="width: 100%; border-radius: 8px;">Criar Destaque</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAIS VISUALIZAR DESTAQUES --}}
    @foreach ($user->highlights as $highlight)
        <div class="modal fade" id="highlightModal{{ $highlight->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content highlight-modal" style="background: #0b1120; border: 1px solid rgba(255, 255, 255, .12); border-radius: 24px; overflow: hidden;">
                    <div class="highlight-header" style="height: 55px; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; border-bottom: 1px solid rgba(255, 255, 255, .08);">
                        <h5 style="margin:0; color:white; font-weight:700;">{{ $highlight->titulo }}</h5>
                        <button type="button" data-bs-dismiss="modal" style="background:none; border:none; color:white;"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="highlight-content" style="padding: 20px; text-align: center;">
                        @if ($highlight->imagem)
                            <img src="{{ asset('storage/' . $highlight->imagem) }}" class="highlight-image" style="width:100%; max-height:450px; object-fit:cover; border-radius:12px;">
                        @else
                            <div class="highlight-empty" style="padding:40px; background:rgba(255,255,255,0.05); border-radius:12px;">
                                <i class="bi bi-star" style="font-size:32px; color:#8b5cf6;"></i>
                                <p style="margin-top:10px; color:#94a3b8;">Sem imagem nesta categoria</p>
                            </div>
                        @endif

                        @if (auth()->id() == $user->id)
                            <form method="POST" action="{{ route('highlight.remove', $highlight->id) }}" style="margin-top:15px;">
                                @csrf @method('DELETE')
                                <button class="btn-remove-highlight" style="width:100%; padding:12px; background:linear-gradient(135deg, #dc2626, #e11d48); border:none; color:white; border-radius:12px; font-weight:600;">
                                    <i class="bi bi-trash"></i> Remover destaque
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="followersModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered insta-dialog">
            <div class="modal-content insta-modal">
                <div class="insta-header">
                    <h5>Seguidores</h5>
                    <button type="button" class="insta-x" data-bs-dismiss="modal" style="border:none; background:transparent; color:white; font-size:24px;">×</button>
                </div>
                <div class="insta-body">
                    <input type="text" class="insta-search" placeholder="🔍 Pesquisar">
                    @forelse($user->followers as $follower)
                        <div class="insta-row">
                            <a href="{{ route('users.show', $follower->id) }}" class="insta-left">
                                <img src="{{ $follower->foto_perfil ? asset('storage/' . $follower->foto_perfil) : 'https://ui-avatars.com/api/?name=' . urlencode($follower->nome) }}" class="insta-avatar">
                                <div>
                                    <strong>{{ $follower->user_nome }}</strong>
                                    <span>{{ $follower->nome }}</span>
                                </div>
                            </a>
                            @if (auth()->id() == $user->id)
                                <form method="POST" action="{{ route('remove.follower', $follower->id) }}">
                                    @csrf @method('DELETE')
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

    <div class="modal fade" id="followingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered insta-dialog">
            <div class="modal-content insta-modal">
                <div class="insta-header">
                    <h5>Seguindo</h5>
                    <button type="button" class="insta-x" data-bs-dismiss="modal" style="border:none; background:transparent; color:white; font-size:24px;">×</button>
                </div>
                <div class="insta-body">
                    <input type="text" class="insta-search" placeholder="🔍 Pesquisar">
                    @forelse($user->following as $following)
                        <div class="insta-row">
                            <a href="{{ route('users.show', $following->id) }}" class="insta-left">
                                <img src="{{ $following->foto_perfil ? asset('storage/' . $following->foto_perfil) : 'https://ui-avatars.com/api/?name=' . urlencode($following->nome) }}" class="insta-avatar">
                                <div>
                                    <strong>{{ $following->user_nome }}</strong>
                                    <span>{{ $following->nome }}</span>
                                </div>
                            </a>
                            @if (auth()->id() == $user->id)
                                <form method="POST" action="{{ route('unfollow', $following->id) }}">
                                    @csrf @method('DELETE')
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
        .profile-header-modern {
            display: flex;
            gap: 40px;
            align-items: center;
            margin-bottom: 40px;
        }
        .profile-avatar-modern {
            width: 170px;
            height: 170px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #8b5cf6;
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.4);
        }
        .profile-content { flex: 1; }
        .profile-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .profile-name-modern {
            font-size: 34px;
            font-weight: 800;
            color: white;
            margin: 0;
        }
        .profile-username-modern { color: #94a3b8; }
        .edit-profile-btn {
            background: #8b5cf6;
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 700;
            transition: .2s;
        }
        .edit-profile-btn:hover { background: #7c3aed; color: white; }
        .stats-row { display: flex; gap: 15px; margin-bottom: 20px; }
        .stat-card {
            flex: 1;
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: .3s;
        }
        .stat-card:hover { transform: translateY(-3px); border-color: rgba(139, 92, 246, 0.4); }
        .stat-card strong { display: block; font-size: 24px; color: white; }
        .stat-card span { color: #94a3b8; }
        .bio-card {
            background: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            padding: 18px;
            color: white;
        }
        .skills-section { margin-top: 30px; margin-bottom: 40px; }
        .skills-header h3 { color: white; margin-bottom: 20px; font-weight: 700; }
        .skills-grid-modern {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
        }
        .skill-modern-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            gap: 14px;
            align-items: center;
            transition: .3s;
        }
        .skill-modern-card:hover { transform: translateY(-2px); border-color: rgba(139, 92, 246, 0.3); }
        .skill-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(139, 92, 246, 0.2);
            border: 1px solid rgba(139, 92, 246, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a5b4fc;
            font-size: 18px;
        }
        .skill-title { color: white; font-weight: 700; font-size: 15px; }
        .stories-row {
            display: flex;
            gap: 18px;
            margin: 28px 0 30px;
            padding-bottom: 10px;
            overflow-x: auto;
        }
        .story-item { width: 78px; text-align: center; color: white; font-size: 12px; flex-shrink: 0; cursor: pointer; }
        .story-button { background: transparent; border: none; padding: 0; }
        .story-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(17, 24, 39, 0.8);
            border: 2px solid #8b5cf6;
            padding: 3px;
            margin-bottom: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 0 12px rgba(139, 92, 246, 0.2);
            transition: .3s;
        }
        .story-item:hover .story-circle { transform: scale(1.05); border-color: #a5b4fc; }
        .story-circle img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
        .story-plus { font-size: 28px; color: #8b5cf6; display: flex; align-items: center; justify-content: center; }
        .post-create-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 22px;
            padding: 20px;
            margin-bottom: 35px;
        }
        .publish-btn {
            width: 100%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 11px;
            font-weight: 700;
            transition: .2s;
        }
        .publish-btn:hover { opacity: 0.95; transform: translateY(-1px); }
        .section-title { color: white; font-weight: 800; margin-bottom: 16px; }
        .profile-tabs { display: flex; justify-content: center; gap: 40px; border-top: 1px solid rgba(255, 255, 255, .08); margin-top: 30px; margin-bottom: 25px; }
        .profile-tabs button { background: none; border: none; color: #94a3b8; padding: 18px; font-weight: 700; }
        .profile-tabs button.active { color: white; border-top: 2px solid #8b5cf6; }
        .posts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .grid-post { overflow: hidden; border-radius: 16px; position: relative; background: rgba(15, 23, 42, .82); aspect-ratio: 1/1; border: 1px solid rgba(255, 255, 255, 0.04); }
        .grid-post img { width: 100%; height: 100%; object-fit: cover; transition: .3s; }
        .grid-post-text { width: 100%; height: 100%; padding: 18px; color: white; display: flex; align-items: center; justify-content: center; text-align: center; font-size: 14px; background: rgba(255,255,255,0.02); }
        .grid-post:hover img { transform: scale(1.03); opacity: .85; }
        .grid-overlay { position: absolute; inset: 0; background: rgba(0, 0, 0, .6); display: flex; gap: 18px; align-items: center; justify-content: center; opacity: 0; transition: .2s; color: white; font-weight: 700; backdrop-filter: blur(4px); }
        .grid-post:hover .grid-overlay { opacity: 1; }
        .insta-dialog { max-width: 500px; }
        .insta-modal { background: #1e293b !important; color: white; border-radius: 18px !important; border: 1px solid rgba(255,255,255,0.08); }
        .insta-header { height: 55px; display: flex; justify-content: center; align-items: center; position: relative; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .insta-body { padding: 20px; max-height: 400px; overflow-y: auto; }
        .insta-search { width: 100%; background: rgba(0,0,0,0.2) !important; border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; color: white; padding: 10px 14px; margin-bottom: 15px; }
        .insta-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.02); }
        .insta-left { display: flex; align-items: center; gap: 12px; color: white; text-decoration: none; }
        .insta-avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; }
        .insta-btn { background: rgba(255,255,255,0.08) !important; color: white !important; border: none !important; border-radius: 8px; padding: 6px 14px; font-weight: 600; font-size: 13px; transition: .2s; }
        .insta-btn:hover { background: rgba(255,255,255,0.15) !important; }
        @media (max-width: 768px) {
            .profile-header-modern { flex-direction: column; text-align: center; gap: 20px; }
            .profile-top { flex-direction: column; gap: 15px; }
            .stats-row { width: 100%; }
            .posts-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>

@endsection
