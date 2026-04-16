@extends('layouts.app')

@section('content')

    <style>
        .profile-card {
            max-width: 500px;
            margin: auto;
            border-radius: 20px;
        }

        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            border: 3px solid #673cd4;
            padding: 3px;
        }

        .file-input {
            display: none;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-save {
            border-radius: 10px;
            padding: 10px;
        }

        .btn {
            transition: 0.2s;
        }

        .btn:hover {
            transform: scale(1.05);
        }
    </style>

    <div class="container mt-4">

        <div class="card profile-card shadow">

            <div class="card-body">

                <h4 class="mb-4 text-center">✏️ Editar Perfil</h4>

                {{-- ERROS --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- FOTO --}}
                    <div class="text-center mb-4">

                        <label for="fotoInput">

                            @if ($user->foto_perfil)
                                <img id="preview" src="{{ asset('storage/' . $user->foto_perfil) }}"
                                    class="avatar-preview">
                            @else
                                <img id="preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->nome) }}"
                                    class="avatar-preview">
                            @endif

                        </label>

                        <input type="file" name="foto_perfil" id="fotoInput" class="file-input">

                        <div class="text-muted mt-2" style="font-size: 0.85rem;">
                            Clique para trocar a foto
                        </div>
                    </div>

                    {{-- NOME --}}
                    <div class="mb-3">
                        <label>Nome</label>
                        <input type="text" name="nome" class="form-control" value="{{ old('nome', $user->nome) }}">
                    </div>

                    {{-- USERNAME --}}
                    <div class="mb-3">
                        <label>Nome de usuário</label>
                        <input type="text" name="user_nome" class="form-control"
                            value="{{ old('user_nome', $user->user_nome) }}">
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label>CPF</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->cpf }}" readonly>
                    </div>

                    {{-- SENHA --}}
                    <div class="mb-3">
                        <label>Nova senha</label>
                        <input type="password" name="password" id="senha" class="form-control">

                        <span onclick="toggleSenha()" style="position:absolute; right:10px; top:38px; cursor:pointer;">
                            👁️
                        </span>
                    </div>

                    <div class="mb-3">
                        <label>Confirmar nova senha</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    {{-- BOTÕES --}}
                    <div class="d-flex justify-content-between mt-4">

                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-outline-secondary">
                            ← Voltar
                        </a>

                        <div class="mb-3">
                            <label>Senha atual (obrigatório para salvar)</label>
                            <input type="password" name="senha_atual" class="form-control" required>
                        </div>

                        <button class="btn btn-sm btn-primary px-4">
                            💾 Salvar
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

    {{-- PREVIEW DA IMAGEM --}}
    <script>
        document.getElementById('fotoInput').addEventListener('change', function(e) {
            const [file] = this.files;
            if (file) {
                document.getElementById('preview').src = URL.createObjectURL(file);
            }
        });
    </script>

    <script>
        function toggleSenha() {
            const input = document.getElementById('senha');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

@endsection
