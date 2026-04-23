<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Interskill')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* 🔥 BACKGROUND IGUAL LOGIN */
        body {
            background: linear-gradient(135deg, #0f172a, #1e293b, #4c1d95);
            min-height: 100vh;
            color: #fff;
        }

        /* SIDEBAR */
        .sidebar {
            min-height: 100vh;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #cbd5f5;
            font-weight: 500;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.05);
            border-radius: 5px;
            color: #fff;
        }

        /* CONTEÚDO */
        .content-area {
            padding: 30px;
        }

        /* CARDS */
        .card {
            max-width: 500px;
            margin: 0 auto;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            color: #fff;
        }

        .card-body {
            padding: 15px;
        }

        /* INPUTS */
        .form-control {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        /* BOTÃO IGUAL LOGIN */
        .btn-primary {
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            border: none;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        /* TEXTO MUTED */
        .text-muted {
            color: #94a3b8 !important;
        }

        /* IMAGEM POST */
        .media-post {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            display: block;
            border-radius: 10px;
        }

        /* BADGE NOTIF */
        .badge {
            font-size: 12px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR (AGORA TRANSPARENTE) -->
    <nav class="navbar navbar-expand-lg" style="background: transparent;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-white" href="#">INTERSKILL</a>

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
            @auth
                <div class="col-md-3 col-lg-2 d-md-block sidebar collapse show">
                    <div class="pt-3">

                        <a href="{{ route('home') }}">🏠 Home</a>
                        <a href="{{ route('users.show', auth()->user()->id) }}">👤 Perfil</a>
                        <a href="{{ route('users.explore') }}">🔎 Explorar</a>
                        <a href="{{ route('chat.inbox') }}">📩 Mensagens</a>

                        <!-- 🔔 NOTIFICAÇÃO -->
                        <a href="{{ route('parcerias') }}" class="d-flex justify-content-between align-items-center">
                            🔔 Solicitações

                            <span id="notif" class="badge bg-danger">
                                {{ \App\Models\Parceria::where('user_id', auth()->id())
                                    ->where('status', 'pendente')
                                    ->count() }}
                            </span>
                        </a>

                        <a href="#">⚙️ Configurações</a>

                    </div>
                </div>
            @endauth

            <!-- CONTEÚDO -->
            <main class="col-md-9 ms-sm-auto col-lg-10 content-area">
                @yield('content')
            </main>

        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 🔔 ATUALIZA NOTIFICAÇÃO -->
    <script>
        setInterval(() => {
            fetch('/notificacoes')
                .then(res => res.json())
                .then(data => {

                    const el = document.getElementById('notif');

                    if (el) {
                        el.innerText = data.count;
                    }

                });
        }, 5000);
    </script>

</body>
</html>
