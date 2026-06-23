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

            <div class="profile-avatar-area" data-bs-toggle="modal" data-bs-target="#avatarModal" style="cursor:pointer;">
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
                            {{ '@' . $user->user_nome }}
                        </span>
                    </div>

                    @if (auth()->id() == $user->id)
                        <a href="{{ route('users.edit', $user) }}" class="edit-profile-btn">
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

                @if ($user->biografia)
                    <div class="bio-card">
                        {{ $user->biografia }}
                    </div>
                @endif

            </div>

        </div>

        <div class="skills-section">

            <div class="skills-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h3>
                    <i class="bi bi-lightning-charge-fill"></i>
                    Habilidades
                </h3>
                @can('update', $user)
                    <button data-bs-toggle="modal" data-bs-target="#addSkillModal" class="edit-profile-btn"
                        style="border:none; cursor:pointer;">
                        <i class="bi bi-plus-lg"></i> Adicionar
                    </button>
                @endcan
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

        {{-- DESTAQUES (STORIES) --}}
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
                <button class="story-item story-button" onclick="abrirStory({{ $highlight->id }})">
                    <div class="story-circle">
                        @if ($highlight->imagem)
                            <img src="{{ asset('storage/' . $highlight->imagem) }}">
                        @else
                            <div class="story-plus"><i class="bi bi-star-fill" style="font-size: 20px; color: #8b5cf6;"></i>
                            </div>
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

                    <textarea name="corpo" class="form-control post-input" placeholder="O que você está pensando?" rows="2"
                        style="background: rgba(255, 255, 255, 0.06); color: white; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 14px; padding: 12px;"></textarea>

                    <div class="post-tools">
                        <label
                            style="flex: 1; text-align: center; padding: 15px; border-radius: 14px; background: rgba(255, 255, 255, .04); border: 1px solid rgba(255, 255, 255, .08); color: white; cursor: pointer; display: block; transition: .3s;"
                            onmouseover="this.style.borderColor='#8b5cf6'; this.style.background='rgba(139, 92, 246, .1)'"
                            onmouseout="this.style.borderColor='rgba(255, 255, 255, .08)'; this.style.background='rgba(255, 255, 255, .04)'">
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
                <div class="grid-post"
                    @if ($post->medias->count()) data-bs-toggle="modal" data-bs-target="#postModal{{ $post->id }}" @endif>
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
                <p class="text-muted" style="grid-column: 1/-1; text-align: center; padding: 40px 0;">Nenhuma postagem
                    ainda.</p>
            @endforelse
        </div>

        {{-- MODAIS DE VISUALIZAÇÃO DE POST (com foto) --}}
        @foreach ($posts as $post)
            @if ($post->medias->count())
                <div class="modal fade" id="postModal{{ $post->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content insta-modal">
                            <div class="insta-header">
                                <h5>{{ $user->user_nome }}</h5>
                                <button type="button" class="insta-x" data-bs-dismiss="modal"
                                    style="border:none;background:transparent;color:white;font-size:24px;">×</button>
                            </div>
                            <div class="insta-body" style="text-align:center;">
                                @foreach ($post->medias as $media)
                                    <img src="{{ asset('storage/' . $media->caminho) }}"
                                        style="width:100%; border-radius:12px; margin-bottom:10px;">
                                @endforeach
                                @if ($post->corpo)
                                    <p style="text-align:left; color:white;">{{ $post->corpo }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>

    {{-- MODAL: VER FOTO DE PERFIL --}}
    <div class="modal fade" id="avatarModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background:transparent;border:none;">
                <img src="{{ $user->foto_perfil ? asset('storage/' . $user->foto_perfil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nome) }}"
                    style="width:100%;border-radius:12px;">
            </div>
        </div>
    </div>

    {{-- MODAL: CRIAR DESTAQUE --}}
    <div class="modal fade" id="createHighlightModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content insta-modal">
                <div class="insta-header">
                    <h5>Novo destaque</h5>
                    <button type="button" class="insta-x" data-bs-dismiss="modal"
                        style="border:none; background:transparent; font-size:24px;">×</button>
                </div>
                <div class="insta-body">
                    <form method="POST" action="{{ route('highlights.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="titulo" class="form-control mb-3" placeholder="Nome do destaque"
                            required
                            style="background: #363636; border: none; color: white; border-radius: 8px; padding: 10px;">

                        <label class="mb-2 d-block text-sm text-muted">Capa (círculo do destaque):</label>
                        <input type="file" name="capa" class="form-control mb-3" accept="image/*"
                            style="background: #363636; border: none; color: white;">

                        <label class="mb-2 d-block text-sm text-muted">Imagens do destaque (pode escolher várias):</label>
                        <input type="file" name="imagens[]" multiple required class="form-control mb-3"
                            accept="image/*" style="background: #363636; border: none; color: white;">

                        <label class="mb-2 d-block text-sm text-muted">Áudio de fundo (Opcional):</label>
                        <input type="file" name="audio" class="form-control mb-3" accept="audio/*"
                            style="background: #363636; border: none; color: white;">

                        <button type="submit" class="publish-btn" style="width: 100%; border-radius: 8px;">Criar
                            Destaque</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: ADICIONAR HABILIDADE --}}
    <div class="modal fade" id="addSkillModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content insta-modal">
                <div class="insta-header">
                    <h5>Adicionar habilidade</h5>
                    <button type="button" class="insta-x" data-bs-dismiss="modal"
                        style="border:none;background:transparent;color:white;font-size:24px;">×</button>
                </div>
                <div class="insta-body">
                    <form method="POST" action="{{ route('skills.store') }}">
                        @csrf
                        <input type="text" id="skillSearch" class="insta-search" placeholder="🔍 Buscar habilidade"
                            autocomplete="off">
                        <div id="skillResults" style="margin-bottom:15px;"></div>
                        <input type="hidden" name="skill_id" id="skillId">

                        <label class="mb-2 d-block text-sm text-muted">Nível:</label>
                        <select name="nivel" class="form-control mb-3"
                            style="background:#363636;color:white;border:none;">
                            <option value="0">Aprendendo</option>
                            <option value="1">Básico</option>
                            <option value="2">Médio</option>
                            <option value="3">Avançado</option>
                            <option value="4">Pro</option>
                            <option value="5">Mestre</option>
                        </select>

                        <button type="submit" class="publish-btn" style="width:100%;">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- STORY VIEWER (TELA CHEIA, ESTILO INSTAGRAM) --}}
    <div id="storyViewer" class="story-viewer-overlay">
        <div class="story-viewer-bars" id="storyBars"></div>
        <div class="story-viewer-header">
            <img id="storyAvatar" src="" class="story-viewer-avatar">
            <strong id="storyTitulo"></strong>
            @can('update', $user)
                <button id="storyDeleteBtn" class="story-viewer-delete" title="Remover destaque">
                    <i class="bi bi-trash"></i>
                </button>
            @endcan
            <button class="story-viewer-close" onclick="fecharStory()">×</button>
        </div>

        <div class="story-viewer-nav story-viewer-prev" onclick="storyPrev()"></div>
        <div class="story-viewer-nav story-viewer-next" onclick="storyNext()"></div>

        <img id="storyImage" class="story-viewer-image">
        <audio id="storyAudio" loop></audio>
    </div>

    <form id="storyDeleteForm" method="POST" style="display:none;">
        @csrf @method('DELETE')
    </form>

    <div class="modal fade" id="followersModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered insta-dialog">
            <div class="modal-content insta-modal">
                <div class="insta-header">
                    <h5>Seguidores</h5>
                    <button type="button" class="insta-x" data-bs-dismiss="modal"
                        style="border:none; background:transparent; color:white; font-size:24px;">×</button>
                </div>
                <div class="insta-body">
                    <input type="text" class="insta-search" placeholder="🔍 Pesquisar">
                    @forelse($user->followers as $follower)
                        <div class="insta-row">
                            <a href="{{ route('users.show', $follower->id) }}" class="insta-left">
                                <img src="{{ $follower->foto_perfil ? asset('storage/' . $follower->foto_perfil) : 'https://ui-avatars.com/api/?name=' . urlencode($follower->nome) }}"
                                    class="insta-avatar">
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
                    <button type="button" class="insta-x" data-bs-dismiss="modal"
                        style="border:none; background:transparent; color:white; font-size:24px;">×</button>
                </div>
                <div class="insta-body">
                    <input type="text" class="insta-search" placeholder="🔍 Pesquisar">
                    @forelse($user->following as $following)
                        <div class="insta-row">
                            <a href="{{ route('users.show', $following->id) }}" class="insta-left">
                                <img src="{{ $following->foto_perfil ? asset('storage/' . $following->foto_perfil) : 'https://ui-avatars.com/api/?name=' . urlencode($following->nome) }}"
                                    class="insta-avatar">
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

    <script>
        const highlightsData = {!! json_encode(
            $user->highlights->map(
                fn($h) => [
                    'id' => $h->id,
                    'titulo' => $h->titulo,
                    'avatar' => $user->foto_perfil
                        ? asset('storage/' . $user->foto_perfil)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->nome),
                    'audio' => $h->audio ? asset('storage/' . $h->audio) : null,
                    'itens' => $h->items->map(fn($i) => asset('storage/' . $i->caminho))->values(),
                ],
            ),
        ) !!};

        let currentHighlightIndex = 0;
        let currentItemIndex = 0;
        let storyTimer = null;
        const DURATION = 5000; // 5s por imagem

        function abrirStory(highlightId) {
            currentHighlightIndex = highlightsData.findIndex(h => h.id === highlightId);
            currentItemIndex = 0;
            document.getElementById('storyViewer').style.display = 'flex';
            document.body.style.overflow = 'hidden';
            renderStory();
        }

        function fecharStory() {
            document.getElementById('storyViewer').style.display = 'none';
            document.body.style.overflow = '';
            clearTimeout(storyTimer);
            document.getElementById('storyAudio').pause();
        }

        function renderStory() {
            const highlight = highlightsData[currentHighlightIndex];
            if (!highlight || highlight.itens.length === 0) return;

            document.getElementById('storyAvatar').src = highlight.avatar;
            document.getElementById('storyTitulo').innerText = highlight.titulo;
            document.getElementById('storyImage').src = highlight.itens[currentItemIndex];

            const deleteBtn = document.getElementById('storyDeleteBtn');
            if (deleteBtn) {
                deleteBtn.onclick = () => removerDestaque(highlight.id);
            }

            const audioEl = document.getElementById('storyAudio');
            if (highlight.audio) {
                if (audioEl.src !== highlight.audio) {
                    audioEl.src = highlight.audio;
                    audioEl.play().catch(() => {});
                }
            } else {
                audioEl.pause();
                audioEl.src = '';
            }

            renderBars(highlight.itens.length, currentItemIndex);

            clearTimeout(storyTimer);
            storyTimer = setTimeout(storyNext, DURATION);
        }

        function renderBars(total, atual) {
            const container = document.getElementById('storyBars');
            container.innerHTML = '';
            for (let i = 0; i < total; i++) {
                const bar = document.createElement('div');
                bar.className = 'story-bar';
                const fill = document.createElement('div');
                fill.className = 'story-bar-fill';
                if (i < atual) fill.style.width = '100%';
                if (i === atual) {
                    fill.style.width = '0%';
                    requestAnimationFrame(() => {
                        fill.style.transition = `width ${DURATION}ms linear`;
                        fill.style.width = '100%';
                    });
                }
                bar.appendChild(fill);
                container.appendChild(bar);
            }
        }

        function storyNext() {
            const highlight = highlightsData[currentHighlightIndex];
            if (currentItemIndex < highlight.itens.length - 1) {
                currentItemIndex++;
                renderStory();
            } else if (currentHighlightIndex < highlightsData.length - 1) {
                currentHighlightIndex++;
                currentItemIndex = 0;
                renderStory();
            } else {
                fecharStory();
            }
        }

        function storyPrev() {
            if (currentItemIndex > 0) {
                currentItemIndex--;
                renderStory();
            } else if (currentHighlightIndex > 0) {
                currentHighlightIndex--;
                currentItemIndex = highlightsData[currentHighlightIndex].itens.length - 1;
                renderStory();
            }
        }

        function removerDestaque(highlightId) {
            if (!confirm('Remover este destaque?')) return;
            const form = document.getElementById('storyDeleteForm');
            form.action = `/highlight/${highlightId}`;
            form.submit();
        }

        const skillSearch = document.getElementById('skillSearch');
        const skillResults = document.getElementById('skillResults');
        const skillId = document.getElementById('skillId');

        if (skillSearch) {
            skillSearch.addEventListener('input', function() {
                const query = this.value;
                if (!query) {
                    skillResults.innerHTML = '';
                    return;
                }

                fetch(`/skills?search=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        skillResults.innerHTML = data.map(skill => `
                            <div class="insta-row" style="cursor:pointer;" onclick="selecionarSkill(${skill.id}, '${skill.nome}')">
                                <span>${skill.nome}</span>
                            </div>
                        `).join('');
                    });
            });
        }

        function selecionarSkill(id, nome) {
            skillId.value = id;
            skillSearch.value = nome;
            skillResults.innerHTML = '';
        }
    </script>

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

        .profile-content {
            flex: 1;
        }

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

        .profile-username-modern {
            color: #94a3b8;
        }

        .edit-profile-btn {
            background: #8b5cf6;
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .edit-profile-btn:hover {
            background: #7c3aed;
            color: white;
        }

        .stats-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

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

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: rgba(139, 92, 246, 0.4);
        }

        .stat-card strong {
            display: block;
            font-size: 24px;
            color: white;
        }

        .stat-card span {
            color: #94a3b8;
        }

        .bio-card {
            background: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            padding: 18px;
            color: white;
        }

        .skills-section {
            margin-top: 30px;
            margin-bottom: 40px;
        }

        .skills-header h3 {
            color: white;
            margin-bottom: 20px;
            font-weight: 700;
        }

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

        .skill-modern-card:hover {
            transform: translateY(-2px);
            border-color: rgba(139, 92, 246, 0.3);
        }

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

        .skill-title {
            color: white;
            font-weight: 700;
            font-size: 15px;
        }

        .stories-row {
            display: flex;
            gap: 18px;
            margin: 28px 0 30px;
            padding-bottom: 10px;
            overflow-x: auto;
        }

        .story-item {
            width: 78px;
            text-align: center;
            color: white;
            font-size: 12px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .story-button {
            background: transparent;
            border: none;
            padding: 0;
        }

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

        .story-item:hover .story-circle {
            transform: scale(1.05);
            border-color: #a5b4fc;
        }

        .story-circle img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .story-plus {
            font-size: 28px;
            color: #8b5cf6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

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

        .publish-btn:hover {
            opacity: 0.95;
            transform: translateY(-1px);
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
            gap: 12px;
        }

        .grid-post {
            overflow: hidden;
            border-radius: 16px;
            position: relative;
            background: rgba(15, 23, 42, .82);
            aspect-ratio: 1/1;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }

        .grid-post img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .3s;
        }

        .grid-post-text {
            width: 100%;
            height: 100%;
            padding: 18px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.02);
        }

        .grid-post:hover img {
            transform: scale(1.03);
            opacity: .85;
        }

        .grid-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            display: flex;
            gap: 18px;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: .2s;
            color: white;
            font-weight: 700;
            backdrop-filter: blur(4px);
        }

        .grid-post:hover .grid-overlay {
            opacity: 1;
        }

        .insta-dialog {
            max-width: 500px;
        }

        .insta-modal {
            background: #1e293b !important;
            color: white;
            border-radius: 18px !important;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .insta-header {
            height: 55px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .insta-body {
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }

        .insta-search {
            width: 100%;
            background: rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            color: white;
            padding: 10px 14px;
            margin-bottom: 15px;
        }

        .insta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        }

        .insta-left {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
        }

        .insta-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
        }

        .insta-btn {
            background: rgba(255, 255, 255, 0.08) !important;
            color: white !important;
            border: none !important;
            border-radius: 8px;
            padding: 6px 14px;
            font-weight: 600;
            font-size: 13px;
            transition: .2s;
        }

        .insta-btn:hover {
            background: rgba(255, 255, 255, 0.15) !important;
        }

        .story-viewer-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: #000;
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .story-viewer-bars {
            position: absolute;
            top: 12px;
            left: 12px;
            right: 12px;
            display: flex;
            gap: 4px;
            z-index: 2;
        }

        .story-bar {
            flex: 1;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
            overflow: hidden;
        }

        .story-bar-fill {
            height: 100%;
            width: 0;
            background: white;
        }

        .story-viewer-header {
            position: absolute;
            top: 28px;
            left: 12px;
            right: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 2;
            color: white;
        }

        .story-viewer-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .story-viewer-header strong {
            flex: 1;
        }

        .story-viewer-close,
        .story-viewer-delete {
            background: none;
            border: none;
            color: white;
            font-size: 22px;
            cursor: pointer;
        }

        .story-viewer-image {
            max-width: 100%;
            max-height: 100vh;
            object-fit: contain;
        }

        .story-viewer-nav {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 30%;
            z-index: 1;
            cursor: pointer;
        }

        .story-viewer-prev {
            left: 0;
        }

        .story-viewer-next {
            right: 0;
        }

        @media (max-width: 768px) {
            .profile-header-modern {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .profile-top {
                flex-direction: column;
                gap: 15px;
            }

            .stats-row {
                width: 100%;
            }

            .posts-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

@endsection
