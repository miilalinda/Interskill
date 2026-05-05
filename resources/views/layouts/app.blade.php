<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Interskill')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* 🔥 BACKGROUND */
        body {
            background: linear-gradient(135deg, #0f172a, #1e293b, #4c1d95);
            min-height: 100vh;
            color: #e2e8f0;
            font-family: 'Segoe UI', sans-serif;
        }

        /* 🔤 TEXTOS */
        h1, h2, h3, h4, h5, strong {
            color: #ffffff;
        }

        .text-muted {
            color: #94a3b8 !important;
        }

        a {
            color: #818cf8;
        }

        a:hover {
            color: #c7d2fe;
        }

        /* 📌 NAVBAR */
        .navbar {
            background: transparent;
        }

        /* 📂 SIDEBAR */
        .sidebar {
            min-height: 100vh;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar a {
            display: block;
            padding: 12px 18px;
            text-decoration: none;
            color: #cbd5f5;
            font-weight: 500;
            border-radius: 8px;
            margin: 5px 10px;
            transition: 0.2s;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.08);
            color: #ffffff;
        }

        /* 📄 CONTEÚDO */
        .content-area {
            padding: 30px;
        }

        /* 🧊 CARDS (SEM LIMITAÇÃO DE LARGURA 🔥) */
        .card {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            color: #ffffff;
        }

        /* ✏️ INPUTS */
        .form-control {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #e2e8f0;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        /* 🔘 BOTÃO */
        .btn-primary {
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            border: none;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        /* 🖼️ IMAGEM POST */
        .media-post {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 10px;
        }

        /* 🔔 BADGE */
        .badge {
            font-size: 12px;
        }

        /* 📱 RESPONSIVO */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .content-area {
                padding: 15px;
            }
        }

        /* PAGINAÇÃO */
        .pagination {
            margin-top: 20px;
        }

        .page-link {
            background: rgba(15, 23, 42, 0.8) !important;
            color: #c7d2fe !important;
            border: 1px solid rgba(255,255,255,0.12) !important;
        }

        .page-link:hover {
            background: rgba(99, 102, 241, 0.4) !important;
            color: #fff !important;
        }

        .page-item.active .page-link {
            background: #6366f1 !important;
            border-color: #6366f1 !important;
            color: #fff !important;
        }

        .page-item.disabled .page-link {
            background: rgba(15, 23, 42, 0.4) !important;
            color: #64748b !important;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-white" href="#">INTERSKILL</a>

            <div class="ms-auto">
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-light btn-sm">Sair</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light btn-sm">Login</a>
                    <a href="{{ route('users.create') }}" class="btn btn-light btn-sm">Cadastrar</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            @auth
                <aside class="col-md-3 col-lg-2 sidebar d-none d-md-block">
                    <div class="pt-3">

                        <a href="{{ route('home') }}">🏠 Home</a>
                        <a href="{{ route('users.show', auth()->id()) }}">👤 Perfil</a>
                        <a href="{{ route('users.explore') }}">🔎 Explorar</a>
                        <a href="{{ route('chat.inbox') }}">📩 Mensagens</a>

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
                </aside>
            @endauth

            <!-- CONTEÚDO -->
            <main class="col-12 col-md-9 col-lg-10 content-area">
                @yield('content')
            </main>

        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 🔔 NOTIFICAÇÃO -->
    <script>
        setInterval(() => {
            fetch('/notificacoes')
                .then(res => res.json())
                .then(data => {
                    const el = document.getElementById('notif');
                    if (el) el.innerText = data.count;
                });
        }, 5000);
    </script>

</body>

</html>
