@extends('auth.template')

@section('title', 'Cadastro')

@section('content')

<div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>

    <div class="glass-panel">
        <h1>INTERSKILL</h1>
        <p class="subtitle">Bem-vindo de volta, conecte-se para trocar habilidades.</p>

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <div class="form-group">
                <label for="email">Endereço de Email</label>
                <input type="email" name="email" id="email" class="form-input" placeholder="seu@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" class="form-input" required>
            </div>

            <button type="submit" class="btn-primary">Entrar na Plataforma</button>

            <div class="links-container">
                <a href="#">Esqueceu a senha?</a>
                <a href="{{ route('users.create') }}">Criar nova conta</a>
            </div>
        </form>
    </div>

@endsection
