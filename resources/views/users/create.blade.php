@extends('auth.template')

@section('title', 'Cadastro')

@section('content')

<div class="bg-blob blob-1"></div>
<div class="bg-blob blob-2"></div>

<div class="glass-panel">
    <h1>INTERSKILL</h1>
    <p class="subtitle">Crie sua conta e comece a trocar habilidades.</p>

    {{-- ERROS --}}
    @if ($errors->any())
        <div style="color: #f87171; margin-bottom: 15px;">
            @foreach ($errors->all() as $erro)
                <div>{{ $erro }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Nome -->
        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" class="form-input" value="{{ old('nome') }}" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
        </div>

        <!-- CPF -->
        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" class="form-input" value="{{ old('cpf') }}" required>
        </div>

        <!-- Username -->
        <div class="form-group">
            <label>Nome de Usuário</label>
            <input type="text" name="user_nome" class="form-input" value="{{ old('user_nome') }}" required>
        </div>

        <!-- Senha -->
        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="password" class="form-input" required>
        </div>

        <!-- Foto -->
        <div class="form-group">
            <label>Foto de Perfil</label>

            <div class="file-upload-wrapper">
                <input type="file" name="foto_perfil" class="file-upload-input">
                <div class="file-upload-text">
                    Clique para enviar uma imagem
                </div>
            </div>
        </div>

        <button type="submit" class="btn-primary">
            Criar Conta
        </button>

        <div class="links-container">
            <a href="{{ route('login') }}">Já tenho conta</a>
        </div>
    </form>
</div>

@endsection
