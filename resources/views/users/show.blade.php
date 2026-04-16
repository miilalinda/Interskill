@extends('layouts.app')

@section('title', 'Perfil')

@section('content')

<div class="container">

    <!-- PERFIL -->
    <div class="card shadow-sm mb-4 border-0" style="border-radius:15px;">
        <div class="card-body">
            <div class="row align-items-center">

                <!-- FOTO -->
                <div class="col-md-3 text-center">

                    <img src="{{ $user->foto_perfil
                        ? asset('storage/' . $user->foto_perfil)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->nome) . '&background=0D6EFD&color=fff' }}"
                        class="rounded-circle shadow profile-img"
                        width="100" height="100"
                        style="object-fit:cover;">

                    <!-- BOTÃO REMOVER FOTO -->
                    @if(auth()->id() == $user->id && $user->foto_perfil)
                        <form action="{{ route('user.deleteFoto', $user->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger">
                                🗑️ Remover foto
                            </button>
                        </form>
                    @endif

                </div>

                <!-- INFO -->
                <div class="col-md-6">
                    <h3 class="mb-0 fw-bold">{{ $user->nome }}</h3>
                    <small class="text-muted">@{{ $user->user_nome }}</small>

                    <div class="d-flex gap-4 mt-3">

                        <div class="text-center">
                            <h5 class="mb-0">{{ $user->posts_count }}</h5>
                            <small class="text-muted">Postagens</small>
                        </div>

                        <div class="text-center">
                            <h5 class="mb-0">{{ $user->followers_count }}</h5>
                            <small class="text-muted">Seguidores</small>
                        </div>

                        <div class="text-center">
                            <h5 class="mb-0">{{ $user->following_count }}</h5>
                            <small class="text-muted">Seguindo</small>
                        </div>

                    </div>
                </div>

                <!-- BOTÕES -->
                <div class="col-md-3 text-md-end">

                    @if (auth()->id() == $user->id)

                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-dark w-100">
                            ✏️ Editar Perfil
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
                    : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->nome) . '&background=0D6EFD&color=fff' }}"
                    class="rounded-circle me-2"
                    width="40" height="40"
                    style="object-fit:cover;">

                <div>
                    <strong>{{ $post->user->nome }}</strong>
                    <div class="text-muted" style="font-size:12px;">
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>

            </div>

            <!-- MIDIA -->
            @if ($post->medias->count())
                <img src="{{ asset('storage/' . $post->medias->first()->caminho) }}"
                    class="w-100"
                    style="max-height:400px;object-fit:cover;">
            @endif

            <!-- AÇÕES -->
            <div class="p-3">

                <div class="d-flex gap-3 mb-2">

                    <form action="{{ route('posts.like', $post) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm border-0" style="background:none;">
                            ❤️ {{ $post->likes->count() }}
                        </button>
                    </form>

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
                        class="form-control form-control-sm border-0"
                        placeholder="Adicione um comentário..."
                        style="background:#f0f2f5;border-radius:20px;padding:10px;">
                </form>

            </div>

        </div>

    @endforeach

</div>

{{-- PREVIEW --}}
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

<style>
button:hover {
    transform: scale(1.05);
    transition: 0.2s;
}

/* FOTO PERFIL */
.profile-img {
    border: 3px solid #0d6efd;
    padding: 3px;
    transition: 0.3s;
}

.profile-img:hover {
    transform: scale(1.05);
}
</style>

@endsection
