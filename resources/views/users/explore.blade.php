@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Explorar</h3>
    </div>

    <form method="GET" action="{{ route('users.explore') }}" class="mb-4">
        <div class="position-relative">

            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                class="form-control ps-5 py-2 rounded-pill shadow-sm"
                placeholder="Buscar pessoas ou habilidades...">

            <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                🔍
            </span>

        </div>
    </form>

    @if(request('q'))

        <div class="row g-4">

            @forelse ($users as $user)

                @php
                    $seguindo = auth()->user()->following->contains($user->id);

                    $jaSolicitou = \App\Models\Parceria::where('user_id', $user->id)
                        ->where('solicitante_id', auth()->id())
                        ->exists();
                @endphp

                <div class="col-md-4">

                    <div class="card border-0 shadow-sm h-100 hover-shadow">

                        <div class="card-body text-center">

                            @if ($user->foto_perfil)
                                <img src="{{ asset('storage/' . $user->foto_perfil) }}"
                                    class="rounded-circle mb-3"
                                    width="90" height="90"
                                    style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                                    style="width:90px;height:90px;font-size:28px;">
                                    {{ strtoupper(substr($user->nome, 0, 1)) }}
                                </div>
                            @endif

                            <h5 class="fw-semibold mb-0">{{ $user->nome }}</h5>
                            <small class="text-muted d-block mb-2">{{ '@' . $user->user_nome }}</small>

                            @if($user->skills->count())
                            <div class="user-skills">
                                @foreach($user->skills->take(4) as $skill)
                                    <span class="skill-chip">
                                        {{ $skill->nome }}
                                    </span>
                                @endforeach
                            </div>
                            @endif

                            <div class="d-flex justify-content-around border-top border-bottom py-2 mb-3">
                                <div>
                                    <strong>{{ $user->posts_count }}</strong><br>
                                    <small class="text-muted">Posts</small>
                                </div>
                                <div>
                                    <strong>{{ $user->followers_count }}</strong><br>
                                    <small class="text-muted">Seguidores</small>
                                </div>
                                <div>
                                    <strong>{{ $user->following_count }}</strong><br>
                                    <small class="text-muted">Seguindo</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2">

                                @if (auth()->id() != $user->id)

                                    @if ($seguindo)
                                        <form method="POST" action="{{ route('unfollow', $user->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-secondary btn-sm rounded-pill">
                                                Seguindo
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('follow', $user->id) }}">
                                            @csrf
                                            <button class="btn btn-primary btn-sm rounded-pill">
                                                Seguir
                                            </button>
                                        </form>
                                    @endif

                                    @if ($jaSolicitou)
                                        <button class="btn btn-light btn-sm rounded-pill" disabled>
                                            ✔️ Enviado
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('parceria.solicitar', $user->id) }}">
                                            @csrf
                                            <button class="btn btn-outline-primary btn-sm rounded-pill">
                                                Parceria
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('chat', $user->id) }}"
                                       class="btn btn-light btn-sm rounded-pill">
                                        Mensagem
                                    </a>

                                @endif

                                <a href="{{ route('users.show', $user->id) }}"
                                   class="btn btn-dark btn-sm rounded-pill">
                                    Ver perfil
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center mt-5">
                    <h5 class="text-muted">Nenhum resultado encontrado</h5>
                    <p class="text-muted">Tente buscar por outra habilidade ou nome</p>
                </div>

            @endforelse

        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $users->appends(['q' => request('q')])->links() }}
        </div>

    @else

        <div class="text-center mt-5">
            <h5 class="text-muted">Pesquise um perfil ou habilidade 🔎</h5>
           <p class="text-muted">Os per fis só aparecem depois que você pesquisar.</p>
        </div>

    @endif

</div>

@endsection
