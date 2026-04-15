@extends('layouts.app')

@section('content')

<h2 class="mb-4">🤝 Solicitações de Parceria</h2>

@forelse ($parcerias as $parceria)

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
               <strong>{{ $parceria->solicitante->nome }}</strong>
                <p class="mb-0">Quer fazer parceria com você</p>
            </div>

            <div class="d-flex gap-2">

                <!-- ACEITAR -->
                <form method="POST" action="{{ route('parceria.aceitar', $parceria->id) }}">
                    @csrf
                    <button class="btn btn-success btn-sm">Aceitar</button>
                </form>

                <!-- RECUSAR -->
                <form method="POST" action="{{ route('parceria.recusar', $parceria->id) }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">Recusar</button>
                </form>

            </div>

        </div>
    </div>

@empty
    <p>Nenhuma solicitação pendente.</p>
@endforelse

@endsection
