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
                        <img src="{{ asset('storage/' . $user->foto_perfil) }}" class="rounded-circle img-fluid" style="max-width:150px;">
                    @else
                        <img src="{{ asset('imagens/neymar1.jpg') }}" class="rounded-circle img-fluid" style="max-width:150px;">
                    @endif
                </div>

                <!-- Info -->
                <div class="col-md-6">
                    <h3 class="mb-0">{{ $user->nome }}</h3>
                    <p class="text-muted">@ {{ $user->user_nome }}</p>

                    <div class="d-flex gap-4 mt-3">
                        <div><strong>12</strong> Postagens</div>
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

    @auth
    @if(auth()->id() === $user->id)
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <textarea name="corpo" class="form-control mb-2" placeholder="O que você está pensando?" rows="3"></textarea>
                <input type="file" name="arquivos[]" class="form-control mb-2" multiple accept="image/*,video/*">
                <button type="submit" class="btn btn-primary">Publicar</button>
            </form>
        </div>
    </div>
    @endif
@endauth

<h4 class="mb-3">Postagens</h4>

@foreach($posts as $post)
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h6 class="fw-bold">{{ $user->nome }}</h6>
                @auth
                    @if(auth()->id() === $post->user_id)
                    <form action="{{ route('posts.destroy', $post) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm text-danger">Excluir</button>
                    </form>
                    @endif
                @endauth
            </div>

            <p class="text-muted small">Publicado em {{ $post->created_at->format('d/m/Y H:i') }}</p>
            <p>{{ $post->corpo }}</p>

            <div class="row g-2">
                @foreach($post->media as $midia)
                    <div class="col-6">
                        @if($midia->tipo == 'imagem')
                            <img src="{{ asset('storage/' . $midia->caminho) }}" class="img-fluid rounded border">
                        @else
                            <video src="{{ asset('storage/' . $midia->caminho) }}" controls class="img-fluid rounded border"></video>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between mt-3">
                <span>👁️ {{ $post->visualizacoes }} visualizações</span>
                <span>❤️ 0 Curtidas</span>
            </div>
        </div>
    </div>
@endforeach

</div>

@endsection
