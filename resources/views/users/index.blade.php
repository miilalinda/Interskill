@extends('layouts.app')

@section('title', 'Lista de Usuários')

@section('content')

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Usuários</h3>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                + Novo Usuário
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>CPF</th>
                            <th>Usuário</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    @if ($user->foto_perfil)
                                        <img src="{{ asset('storage/' . $user->foto_perfil) }}" width="40"
                                            height="40" class="rounded-circle">
                                    @else
                                        <span class="badge bg-secondary">Sem foto</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('users.show', $user->id) }}">
                                        {{ $user->nome }}
                                    </a>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->cpf }}</td>
                                <td>{{ $user->user_nome }}</td>
                                <td class="text-end">

                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                        Editar
                                    </a>

                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                            Excluir
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    Nenhum usuário cadastrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
