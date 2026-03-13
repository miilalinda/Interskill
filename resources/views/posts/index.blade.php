@extends('layouts.app')

@section('title', 'Feed')

@section('content')

    <div class="container">

        <h3 class="mb-4">Feed</h3>

        @foreach ($posts as $post)
            <div class="card mb-4">

                <div class="card-body">

                    <h6>{{ $post->user->nome }}</h6>

                    <p class="text-muted small">
                        {{ $post->created_at->format('d/m/Y H:i') }}
                    </p>

                    @if ($post->corpo)
                        <p>{{ $post->corpo }}</p>
                    @endif

                    <div class="row g-2">

                        @foreach ($post->medias as $midia)
                            <div class="col-6">

                                @if ($midia->tipo == 'imagem')
                                    <img src="{{ asset('storage/' . $midia->caminho) }}" class="img-fluid">
                                @else
                                    <video src="{{ asset('storage/' . $midia->caminho) }}" controls class="img-fluid"></video>
                                @endif

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>
        @endforeach

    </div>

@endsection
