@extends('layouts.auth')

@section('title', 'Criar Usuário')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Criar Usuário</h4>
                    </div>

                    <div class="card-body">

                        {{-- Exibição de erros --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Nome -->
                            <div class="mb-3">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome"
                                    class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}">
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            </div>

                            <!-- CPF -->
                            <div class="mb-3">
                                <label class="form-label">CPF</label>
                                <input type="text" name="cpf" class="form-control @error('cpf') is-invalid @enderror"
                                    value="{{ old('cpf') }}">
                            </div>

                            <!-- Senha -->
                            <div class="mb-3">
                                <label class="form-label">Senha</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror">
                            </div>

                            <!-- Nome de usuário -->
                            <div class="mb-3">
                                <label class="form-label">Nome de Usuário</label>
                                <input type="text" name="user_nome"
                                    class="form-control @error('user_nome') is-invalid @enderror"
                                    value="{{ old('user_nome') }}">
                            </div>

                            <!-- Foto de Perfil -->
                            <div class="mb-3">
                                <label class="form-label">Foto de Perfil</label>
                                <input type="file" name="foto_perfil" class="form-control">
                            </div>

                            <!-- Botão -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    Salvar
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
