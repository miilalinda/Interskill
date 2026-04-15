@extends('layouts.app')

@section('content')

<h3 class="mb-4">📩 Conversas</h3>

@forelse($users as $user)

    <a href="{{ route('chat', $user->id) }}" class="text-decoration-none text-dark">

        <div class="card mb-2 p-3 d-flex flex-row align-items-center">

            <!-- FOTO -->
            @if ($user->foto_perfil)
                <img src="{{ asset('storage/' . $user->foto_perfil) }}"
                     class="rounded-circle me-3"
                     width="50" height="50" style="object-fit: cover;">
            @else
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                     style="width:50px;height:50px;">
                    {{ strtoupper(substr($user->nome, 0, 1)) }}
                </div>
            @endif

            <!-- NOME -->
            <div>
                <strong>{{ $user->nome }}</strong><br>
                <small class="text-muted">Abrir conversa</small>
            </div>

        </div>

    </a>

@empty
    <p>Nenhuma conversa ainda.</p>
@endforelse

@endsection
