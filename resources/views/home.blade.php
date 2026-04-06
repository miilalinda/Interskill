@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="container">

    <!-- 🔥 FORMULÁRIO DE POST -->
    <div class="card mb-4">
        <div class="card-body">

            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- TEXTO -->
                <textarea name="corpo" class="form-control mb-2" placeholder="O que você está pensando?"></textarea>

                <!-- ARQUIVOS (VÁRIAS IMAGENS/VÍDEOS) -->
                <input type="file" name="arquivos[]" multiple class="form-control mb-2">

                <button type="submit" class="btn btn-primary">
                    Postar
                </button>
            </form>

        </div>
    </div>

</div>

@endsection
