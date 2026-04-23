@extends('layouts.app')

@section('title', 'Solicitações')

@section('content')

<div class="container">

    <h3 class="mb-4">🔔 Solicitações de Parceria</h3>

    @forelse ($parcerias as $parceria)

        <div class="card mb-3 shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center">

                    <img src="{{ $parceria->solicitante->foto_perfil
                        ? asset('storage/'.$parceria->solicitante->foto_perfil)
                        : 'https://ui-avatars.com/api/?name='.urlencode($parceria->solicitante->nome) }}"
                        class="rounded-circle me-3"
                        width="50" height="50">

                    <div>
                        <strong>{{ $parceria->solicitante->nome }}</strong><br>
                        <small class="text-muted">Quer fazer parceria com você</small>
                    </div>

                </div>

                <div class="d-flex gap-2">

                    <form method="POST" action="{{ route('parceria.aceitar', $parceria->id) }}">
                        @csrf
                        <button class="btn btn-success btn-sm">Aceitar</button>
                    </form>

                    <form method="POST" action="{{ route('parceria.recusar', $parceria->id) }}">
                        @csrf
                        <button class="btn btn-danger btn-sm">Recusar</button>
                    </form>

                </div>

            </div>
        </div>

    @empty
        <p class="text-muted">Nenhuma solicitação no momento.</p>
    @endforelse

</div>

@endsection
