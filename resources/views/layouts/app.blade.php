<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Interskill')</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>

        body {
            background: linear-gradient(135deg, #0f172a, #1e293b, #4c1d95);
            min-height: 100vh;
            color: #e2e8f0;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, strong {
            color: white;
        }

        .text-muted {
            color: #94a3b8 !important;
        }

        a {
            text-decoration: none;
        }

        /* NAVBAR */

        .navbar {
            background: transparent;
            padding: 12px 20px;
        }

        .navbar-brand {
            font-size: 24px;
            margin-left: 55px;
        }

        /* SIDEBAR */

        .mini-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            background: #020617;
            border-right: 1px solid rgba(255,255,255,0.08);
            padding: 70px 10px 20px;
            transition: 0.3s;
            z-index: 1000;
            overflow-x: hidden;
        }

        .mini-sidebar.closed {
            width: 70px;
        }

        .mini-sidebar.closed span,
        .mini-sidebar.closed .notif-badge {
            display: none;
        }

        .mini-sidebar a {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
            padding: 13px;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: 0.2s;
            white-space: nowrap;
            position: relative;
        }

        .mini-sidebar a:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .mini-sidebar i {
            font-size: 22px;
            min-width: 24px;
            text-align: center;
        }

        /* BOTÃO SIDEBAR */

        .toggle-sidebar {
            position: fixed;
            top: 14px;
            left: 12px;
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 12px;
            background: #111827;
            color: white;
            z-index: 1100;
            font-size: 20px;
        }

        /* CONTEÚDO */

        .content-area {
            margin-left: 240px;
            padding: 30px;
            transition: 0.3s;
        }

        .content-area.full {
            margin-left: 70px;
        }

        /* CARDS */

        .card {
            background: rgba(15, 23, 42, 0.75);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            color: white;
        }

        /* INPUTS */

        .form-control {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            color: white;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        /* BOTÕES */

        .btn-primary {
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            border: none;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        /* BADGE */

        .notif-badge {
            margin-left: auto;
        }

        /* PAINEL NOTIFICAÇÕES */

        .notifications-panel {
            position: fixed;
            top: 0;
            right: -430px;
            width: 430px;
            height: 100vh;
            background: #020617;
            border-left: 1px solid rgba(255,255,255,0.08);
            z-index: 2000;
            transition: 0.3s;
            overflow-y: auto;
            padding: 22px;
        }

        .notifications-panel.open {
            right: 0;
        }

        .notif-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .notif-header button {
            background: transparent;
            border: none;
            color: white;
            font-size: 18px;
        }

        /* TABS */

        .notif-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }

        .notif-tabs button {
            flex: 1;
            border: none;
            border-radius: 999px;
            padding: 8px 12px;
            background: rgba(255,255,255,0.06);
            color: white;
            transition: 0.2s;
        }

        .notif-tabs button.active {
            background: #2563eb;
        }

        /* NOTIFICAÇÕES */

        .instagram-notif {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 8px;
            border-radius: 14px;
            color: white;
            margin-bottom: 12px;
            transition: 0.2s;
        }

        .instagram-notif:hover {
            background: rgba(255,255,255,0.06);
            color: white;
        }

        .notif-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .notif-text {
            flex: 1;
            font-size: 14px;
            line-height: 1.4;
        }

        .notif-text small {
            color: #94a3b8;
        }

        /* BOLINHA AZUL */

        .new-dot {
            position: absolute;
            left: -2px;
            top: 50%;
            transform: translateY(-50%);
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #3b82f6;
        }

        /* MINIATURA POST */

        .post-thumb {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }

        /* RESPONSIVO */

        @media(max-width:768px) {

            .mini-sidebar {
                width: 70px;
            }

            .mini-sidebar span,
            .mini-sidebar .notif-badge {
                display: none;
            }

            .content-area {
                margin-left: 70px;
                padding: 15px;
            }

            .notifications-panel {
                width: 100%;
                right: -100%;
            }
        }

    </style>
</head>

<body>

    <!-- NAVBAR -->

    <nav class="navbar navbar-expand-lg">

        <div class="container-fluid">

            <a class="navbar-brand fw-bold text-white"
                href="{{ route('home') }}">

                INTERSKILL

            </a>

            <div class="ms-auto">

                @auth

                    <form action="{{ route('logout') }}"
                        method="POST"
                        class="d-inline">

                        @csrf

                        <button class="btn btn-light btn-sm">
                            Sair
                        </button>

                    </form>

                @else

                    <a href="{{ route('login') }}"
                        class="btn btn-light btn-sm">

                        Login

                    </a>

                    <a href="{{ route('users.create') }}"
                        class="btn btn-light btn-sm">

                        Cadastrar

                    </a>

                @endauth

            </div>

        </div>

    </nav>

    @auth

        <!-- BOTÃO -->

        <button class="toggle-sidebar"
            onclick="toggleSidebar()">

            ☰

        </button>

        <!-- SIDEBAR -->

        <aside class="mini-sidebar"
            id="sidebar">

            <a href="{{ route('home') }}">
                <i class="bi bi-house-door"></i>
                <span>Feed</span>
            </a>

            <a href="{{ route('users.explore') }}">
                <i class="bi bi-search"></i>
                <span>Explorar</span>
            </a>

            <a href="{{ route('users.show', auth()->id()) }}">
                <i class="bi bi-person"></i>
                <span>Perfil</span>
            </a>

            <a href="{{ route('chat.inbox') }}">
                <i class="bi bi-chat-dots"></i>
                <span>Mensagens</span>
            </a>

            <a href="#"
                onclick="toggleNotifications(); return false;">

                <i class="bi bi-bell"></i>

                <span>Notificações</span>

                <b id="notif"
                    class="badge bg-danger notif-badge">

                    {{ \App\Models\Notification::where('user_id', auth()->id())->where('read', false)->count() }}

                </b>

            </a>

            <a href="{{ route('parcerias') }}">
                <i class="bi bi-people"></i>
                <span>Solicitações</span>
            </a>

            <a href="#">
                <i class="bi bi-gear"></i>
                <span>Configurações</span>
            </a>

        </aside>

        <!-- PAINEL -->

        <div id="notificationsPanel"
            class="notifications-panel">

            <div class="notif-header">

                <h4>Notificações</h4>

                <button onclick="toggleNotifications()">
                    ✖
                </button>

            </div>

            <!-- TABS -->

            <div class="notif-tabs">

                <button class="active"
                    onclick="filterNotif('all', this)">

                    Todas

                </button>

                <button onclick="filterNotif('message', this)">
                    Mensagens
                </button>

                <button onclick="filterNotif('parceria', this)">
                    Solicitações
                </button>

            </div>

            @php

                $notifications = \App\Models\Notification::with([
                    'fromUser',
                    'post.medias'
                ])
                ->where('user_id', auth()->id())
                ->latest()
                ->take(30)
                ->get();

            @endphp

            @forelse($notifications as $notification)

                <a href="{{ $notification->url ?? '#' }}"
                    class="instagram-notif"
                    data-type="{{ $notification->type }}">

                    @if(!$notification->read)

                        <span class="new-dot"></span>

                    @endif

                    <img
                        src="{{ $notification->fromUser && $notification->fromUser->foto_perfil
                            ? asset('storage/' . $notification->fromUser->foto_perfil)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($notification->fromUser->nome ?? 'User') }}"
                        class="notif-avatar">

                    <div class="notif-text">

                        <span>
                            {{ $notification->message }}
                        </span>

                        <br>

                        <small>
                            {{ $notification->created_at->diffForHumans() }}
                        </small>

                    </div>

                    {{-- MINIATURA POST --}}

                    @if(
                        in_array($notification->type, ['like', 'comment'])
                        && $notification->post
                        && $notification->post->medias->first()
                    )

                        <img
                            src="{{ asset('storage/' . $notification->post->medias->first()->caminho) }}"
                            class="post-thumb">

                    @endif

                    {{-- SEGUIR DE VOLTA --}}

                    @if(
                        $notification->type === 'follow'
                        && $notification->fromUser
                    )

                        @php
                            $jaSegue = auth()->user()
                                ->following
                                ->contains($notification->fromUser->id);
                        @endphp

                        @if(!$jaSegue)

                            <form method="POST"
                                action="{{ route('follow', $notification->fromUser->id) }}">

                                @csrf

                                <button class="btn btn-sm btn-primary rounded-pill">
                                    Seguir
                                </button>

                            </form>

                        @endif

                    @endif

                </a>

            @empty

                <p class="text-muted text-center mt-4">

                    Nenhuma notificação ainda.

                </p>

            @endforelse

        </div>

    @endauth

    <!-- CONTEÚDO -->

    <main class="content-area">

        @yield('content')

    </main>

    <!-- SCRIPT -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @auth

        <script>

            function toggleSidebar() {

                document.getElementById('sidebar')
                    .classList.toggle('closed');

                document.querySelector('.content-area')
                    .classList.toggle('full');
            }

            function toggleNotifications() {

                document.getElementById('notificationsPanel')
                    .classList.toggle('open');

                fetch('/notifications/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const badge = document.getElementById('notif');

                if (badge) {
                    badge.innerText = 0;
                }
            }

            function filterNotif(type, btn) {

                document.querySelectorAll('.notif-tabs button')
                    .forEach(b => b.classList.remove('active'));

                btn.classList.add('active');

                document.querySelectorAll('.instagram-notif')
                    .forEach(item => {

                        if (
                            type === 'all'
                            || item.dataset.type === type
                        ) {

                            item.style.display = 'flex';

                        } else {

                            item.style.display = 'none';
                        }

                    });
            }

            setInterval(() => {

                fetch('/notificacoes')
                    .then(res => res.json())
                    .then(data => {

                        const badge =
                            document.getElementById('notif');

                        if (badge) {
                            badge.innerText = data.count;
                        }

                    });

            }, 5000);

        </script>

    @endauth

</body>

</html>
