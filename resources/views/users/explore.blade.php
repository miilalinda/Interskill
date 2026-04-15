@extends('layouts.app')

@section('content')

    <h2 class="mb-4">🔎 Explorar Perfis</h2>

    <div class="row">

        @forelse ($users as $user)
            @php
                $seguindo = auth()->user()->following->contains($user->id);
            @endphp

            <div class="col-md-4 mb-4">

                <div class="card shadow-sm">

                    <div class="card-body text-center">

                        <!-- FOTO -->
                        @if ($user->foto_perfil)
                            <img src="{{ asset('storage/' . $user->foto_perfil) }}" class="rounded-circle mb-3" width="80"
                                height="80" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width:80px;height:80px;">
                                {{ strtoupper(substr($user->nome, 0, 1)) }}
                            </div>
                        @endif

                        <!-- NOME -->
                        <h5>{{ $user->nome }}</h5>
                        <small class="text-muted">{{ '@' . $user->user_nome }}</small>

                        <!-- CONTADORES -->
                        <div class="d-flex justify-content-around mt-3">
                            <div>
                                <strong>{{ $user->posts_count }}</strong><br>
                                <small>Posts</small>
                            </div>
                            <div>
                                <strong>{{ $user->followers_count }}</strong><br>
                                <small>Seguidores</small>
                            </div>
                            <div>
                                <strong>{{ $user->following_count }}</strong><br>
                                <small>Seguindo</small>
                            </div>
                        </div>

                        <!-- BOTÕES -->
                        <div class="mt-3 d-grid gap-2">

                            @if (auth()->id() != $user->id)
                                @if ($seguindo)
                                    <form method="POST" action="{{ route('unfollow', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-secondary btn-sm">Seguindo</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('follow', $user->id) }}">
                                        @csrf
                                        <button class="btn btn-primary btn-sm">Seguir</button>
                                    </form>
                                @endif

                                @php
                                    $jaSolicitou = \App\Models\Parceria::where('user_id', $user->id)
                                        ->where('solicitante_id', auth()->id())
                                        ->exists();
                                @endphp

                                @if ($jaSolicitou)
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        ✔️ Solicitação enviada
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('parceria.solicitar', $user->id) }}">
                                        @csrf
                                        <button class="btn btn-outline-primary btn-sm">
                                            Solicitar Parceria
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('chat', $user->id) }}" class="btn btn-outline-secondary btn-sm">
                                    Enviar Mensagem
                                </a>
                            @endif

                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-dark btn-sm">
                                Ver Perfil
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        @empty
            <p>Nenhum usuário encontrado.</p>
        @endforelse

    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

@endsection
