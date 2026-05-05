@extends('layouts.app')

@section('content')

<style>
    .profile-container {
        max-width: 1100px;
        margin: auto;
    }

    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
        border: 3px solid #673cd4;
        padding: 3px;
    }

    .file-input {
        display: none;
    }

    .card {
        border-radius: 20px;
    }

    .form-control {
        border-radius: 10px;
    }

    .btn:hover {
        transform: scale(1.03);
        transition: 0.2s;
    }
</style>

<div class="container-fluid py-4">

    <div class="profile-container">

        <div class="card shadow">
            <div class="card-body">

                <h3 class="mb-4 text-center">✏️ Editar Perfil</h3>

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

                    <div class="row">

                        {{-- COLUNA ESQUERDA --}}
                        <div class="col-md-4 text-center mb-4">

                            <label for="fotoInput">
                                @if ($user->foto_perfil)
                                    <img id="preview" src="{{ asset('storage/' . $user->foto_perfil) }}"
                                        class="avatar-preview">
                                @else
                                    <img id="preview"
                                        src="https://ui-avatars.com/api/?name={{ urlencode($user->nome) }}"
                                        class="avatar-preview">
                                @endif
                            </label>

                            <input type="file" name="foto_perfil" id="fotoInput" class="file-input">

                            <div class="text-muted mt-2">
                                Clique para trocar a foto
                            </div>

                        </div>

                        {{-- COLUNA DIREITA --}}
                        <div class="col-md-8">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>Nome</label>
                                    <input type="text" name="nome" class="form-control"
                                        value="{{ old('nome', $user->nome) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Nome de usuário</label>
                                    <input type="text" name="user_nome" class="form-control"
                                        value="{{ old('user_nome', $user->user_nome) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" class="form-control bg-light"
                                        value="{{ $user->email }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>CPF</label>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ $user->cpf }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Nova senha</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="senha" class="form-control">
                                        <span class="input-group-text" onclick="toggleSenha()">👁️</span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Confirmar senha</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>

                                <div class="col-12 mb-3">
                                    <label>Senha atual (obrigatório)</label>
                                    <input type="password" name="senha_atual" class="form-control" required>
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- BOTÕES --}}
                    <div class="d-flex flex-column flex-md-row justify-content-between mt-4 gap-2">

                        <a href="{{ route('users.show', $user->id) }}"
                            class="btn btn-outline-secondary w-100 w-md-auto">
                            ← Voltar
                        </a>

                        <button class="btn btn-primary px-4 w-100 w-md-auto">
                            💾 Salvar
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

<script>
    document.getElementById('fotoInput').addEventListener('change', function() {
        const [file] = this.files;
        if (file) {
            document.getElementById('preview').src = URL.createObjectURL(file);
        }
    });

    function toggleSenha() {
        const input = document.getElementById('senha');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>

@endsection
