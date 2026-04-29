@extends('auth.template')

@section('title', 'Cadastro')

@section('content')

<style>
    /* RESET */
    body {
        margin: 0;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #0f172a;
        overflow: hidden;
        position: relative;
        font-family: Arial, sans-serif;
    }

    /* BACKGROUND BLOBS */
    .bg-blob {
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.6;
        z-index: 0;
    }

    .blob-1 {
        background: #6366f1;
        top: -100px;
        left: -100px;
    }

    .blob-2 {
        background: #ec4899;
        bottom: -120px;
        right: -120px;
    }

    /* FORM CONTAINER */
    .glass-panel {
        width: 100%;
        max-width: 420px;
        padding: 30px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        color: white;
        z-index: 2;
    }

    h1 {
        text-align: center;
        margin-bottom: 5px;
    }

    .subtitle {
        text-align: center;
        margin-bottom: 20px;
        font-size: 14px;
        opacity: 0.8;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .form-input {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        border: none;
        outline: none;
    }

    .file-upload-wrapper {
        position: relative;
        padding: 15px;
        border: 1px dashed #aaa;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
    }

    .file-upload-input {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        top: 0;
        left: 0;
    }

    .btn-primary {
        width: 100%;
        padding: 12px;
        background: #6366f1;
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
        margin-top: 10px;
    }

    .btn-primary:hover {
        background: #4f46e5;
    }

    .links-container {
        text-align: center;
        margin-top: 15px;
    }

    .links-container a {
        color: #93c5fd;
        text-decoration: none;
        font-size: 14px;
    }

    .links-container a:hover {
        text-decoration: underline;
    }
</style>

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

        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" class="form-input" value="{{ old('nome') }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" class="form-input" value="{{ old('cpf') }}" required>
        </div>

        <div class="form-group">
            <label>Nome de Usuário</label>
            <input type="text" name="user_nome" class="form-input" value="{{ old('user_nome') }}" required>
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="password" class="form-input" required>
        </div>

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

{{-- MÁSCARA CPF --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cpfInput = document.getElementById('cpf');

        if (!cpfInput) {
            console.log("CPF input não encontrado");
            return;
        }

        cpfInput.addEventListener('input', function () {
            let v = cpfInput.value;

            // remove tudo que não é número
            v = v.replace(/\D/g, '');

            // limita 11 dígitos
            v = v.substring(0, 11);

            // aplica máscara
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

            cpfInput.value = v;
        });
    });
    </script>

@endsection
