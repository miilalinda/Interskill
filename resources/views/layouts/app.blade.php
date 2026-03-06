<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Interskill')</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .sidebar a:hover {
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        .content-area {
            padding: 30px;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">INTERSKILL</a>

            <div class="ms-auto">

                @auth
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-light btn-sm">
                            Sair
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light btn-sm">
                        Login
                    </a>

                    <a href="{{ route('users.create') }}" class="btn btn-light btn-sm">
                        Cadastre-se
                    </a>
                @endauth

            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse show">
                <div class="pt-3">
                    <a href="{{ route('home') }}">🏠 Home</a>
                    <a href="{{ route('users.show', auth()->user()->id) }}">👤 Perfil</a>
                    <a href="#">🔎 Explorar</a>
                    <a href="#">⚙️ Configurações</a>
                </div>
            </div>

            <!-- CONTEÚDO -->
            <main class="col-md-9 ms-sm-auto col-lg-10 content-area">
                @yield('content')
            </main>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
