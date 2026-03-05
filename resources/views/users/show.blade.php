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
                            <img src="https://via.placeholder.com/150" class="rounded-circle img-fluid">
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
                        <button class="btn btn-primary mb-2 w-100">
                            Solicitar Parceria
                        </button>

                        <button class="btn btn-outline-secondary w-100">
                            Enviar Mensagem
                        </button>
                    </div>

                </div>
            </div>
        </div>


        <!-- FEED DO USUÁRIO -->
        <h4 class="mb-3">Postagens</h4>

        <!-- POST 1 -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold">{{ $user->nome }}</h6>
                <p class="text-muted small">Publicado há 2 horas</p>

                <p>
                    🚀 Começando um novo projeto hoje! Muito animado com essa nova fase.
                    Em breve novidades!
                </p>

                <img src="https://picsum.photos/800/400?random=1" class="img-fluid rounded mb-3">

                <div class="d-flex justify-content-between">
                    <span>❤️ 25 Curtidas</span>
                    <span>💬 8 Comentários</span>
                </div>
            </div>
        </div>

        <!-- POST 2 -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold">{{ $user->nome }}</h6>
                <p class="text-muted small">Publicado ontem</p>

                <p>
                    💡 Dica do dia: nunca pare de aprender. O conhecimento abre portas!
                </p>

                <div class="d-flex justify-content-between">
                    <span>❤️ 42 Curtidas</span>
                    <span>💬 15 Comentários</span>
                </div>
            </div>
        </div>

        <!-- POST 3 -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold">{{ $user->nome }}</h6>
                <p class="text-muted small">Publicado há 3 dias</p>

                <p>
                    🌎 Trabalhando em novas conexões e parcerias incríveis!
                    Quem aí topa colaborar?
                </p>

                <img src="https://picsum.photos/800/400?random=2" class="img-fluid rounded mb-3">

                <div class="d-flex justify-content-between">
                    <span>❤️ 67 Curtidas</span>
                    <span>💬 21 Comentários</span>
                </div>
            </div>
        </div>

    </div>

@endsection
