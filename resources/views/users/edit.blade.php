@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0">Editar Usuário</h4>
                    </div>

                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control"
                                    value="{{ old('nome', $user->nome) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">CPF</label>
                                <input type="text" name="cpf" class="form-control"
                                    value="{{ old('cpf', $user->cpf) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nova Senha (opcional)</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nome de Usuário</label>
                                <input type="text" name="user_nome" class="form-control"
                                    value="{{ old('user_nome', $user->user_nome) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto de Perfil</label>
                                <input type="file" name="foto_perfil" class="form-control">

                                @if ($user->foto_perfil)
                                    <div class="mt-3">
                                        <img src="{{ asset('storage/' . $user->foto_perfil) }}" width="80"
                                            class="rounded-circle">
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    Voltar
                                </a>

                                <button type="submit" class="btn btn-warning">
                                    Atualizar
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
