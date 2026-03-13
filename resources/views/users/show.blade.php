@extends('layouts.app')

@section('title', 'Perfil')

@section('content')

    <div class="container">

        <!-- PERFIL HEADER -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">

                    <!-- Foto -->
                    <div class="col-md-3 text-center">
                        @if ($user->foto_perfil)
                            <img src="{{ asset('storage/' . $user->foto_perfil) }}" class="rounded-circle img-fluid"
                                style="max-width:150px;">
                        @else
                            <img src="{{ asset('imagens/neymar1.jpg') }}" class="rounded-circle img-fluid"
                                style="max-width:150px;">
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="col-md-6">
                        <h3 class="mb-0">{{ $user->nome }}</h3>
                        <p class="text-muted">@ {{ $user->user_nome }}</p>

                        <div class="d-flex gap-4 mt-3">
                            <div><strong>{{ $user->posts->count() }}</strong> Postagens</div>
                            <div><strong>340</strong> Parcerias</div>
                            <div><strong>180</strong> Seguindo</div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="col-md-3 text-md-end mt-3 mt-md-0">
                        <button class="btn btn-primary mb-2 w-100">Solicitar Parceria</button>
                        <button class="btn btn-outline-secondary w-100">Enviar Mensagem</button>
                    </div>

                </div>
            </div>
        </div>

        {{-- FORM DE POSTAGEM --}}
        @if (auth()->id() == $user->id)
            <div class="card mb-4">
                <div class="card-body">

                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <textarea name="corpo" class="form-control mb-3" placeholder="O que você está pensando?"></textarea>

                        <input type="file" name="arquivos[]" multiple accept="image/*,video/*" class="form-control mb-3"
                            onchange="previewMidia(event)">

                        <div id="preview" class="row"></div>

                        <button class="btn btn-primary">
                            Publicar
                        </button>

                    </form>

                </div>
            </div>
        @endif


        <h4 class="mb-3">Postagens</h4>

        @foreach ($posts as $post)
            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <h6 class="fw-bold">{{ $user->nome }}</h6>

                        @auth
                            @if (auth()->id() === $post->user_id)
                                <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm text-danger">Excluir</button>
                                </form>
                            @endif
                        @endauth

                    </div>

                    <p class="text-muted small">
                        Publicado em {{ $post->created_at->format('d/m/Y H:i') }}
                    </p>

                    {{-- TEXTO DO POST --}}
                    @if ($post->corpo)
                        <p>{{ $post->corpo }}</p>
                    @endif

                    {{-- MIDIAS DO POST --}}
                    <div id="carousel{{ $post->id }}" class="carousel slide">

                        <div class="carousel-inner">

                            @foreach ($post->medias as $key => $midia)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">

                                    @if ($midia->tipo == 'imagem')
                                        <img src="{{ asset('storage/' . $midia->caminho) }}" class="d-block w-100">
                                    @else
                                        <video src="{{ asset('storage/' . $midia->caminho) }}" controls
                                            class="d-block w-100"></video>
                                    @endif

                                </div>
                            @endforeach

                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{ $post->id }}"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>

                        <button class="carousel-control-next" type="button" data-bs-target="#carousel{{ $post->id }}"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>

                    </div>

                    <div class="d-flex justify-content-between mt-3">

                        <span>👁️ {{ $post->visualizacoes }} visualizações</span>

                        <form action="{{ route('posts.like', $post) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-light">
                                ❤️ {{ $post->likes->count() }}
                            </button>
                        </form>

                    </div>

                    <form action="{{ route('posts.comment', $post) }}" method="POST" class="mt-3">
                        @csrf

                        <input type="text" name="texto" class="form-control mb-2"
                            placeholder="Escreva um comentário...">

                        <button class="btn btn-sm btn-primary">
                            Comentar
                        </button>

                    </form>

                </div>
            </div>
        @endforeach

        <script>
            function previewMidia(event) {

                const preview = document.getElementById('preview');
                preview.innerHTML = '';

                const arquivos = event.target.files;

                for (let i = 0; i < arquivos.length; i++) {

                    const url = URL.createObjectURL(arquivos[i]);

                    const col = document.createElement("div");
                    col.className = "col-4 mb-2";

                    if (arquivos[i].type.startsWith("image")) {

                        col.innerHTML = `<img src="${url}" class="img-fluid rounded">`;

                    } else {

                        col.innerHTML = `<video src="${url}" controls class="img-fluid rounded"></video>`;

                    }

                    preview.appendChild(col);
                }
            }
        </script>
    </div>

@endsection
