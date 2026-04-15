@extends('layouts.app')

@section('content')

<style>
.chat-container {
    max-width: 700px;
    margin: auto;
}

.chat-box {
    height: 450px;
    overflow-y: auto;
    padding: 20px;
    background: #f0f2f5;
    border-radius: 15px;
}

.msg {
    max-width: 65%;
    padding: 12px 16px;
    border-radius: 18px;
    margin-bottom: 10px;
    display: inline-block;
    font-size: 0.95rem;
}

.msg-me {
    background: #0d6efd;
    color: #fff;
    float: right;
    clear: both;
}

.msg-other {
    background: #e4e6eb;
    color: #000;
    float: left;
    clear: both;
}

.chat-header {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #0d6efd;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    font-weight: bold;
}

.typing {
    font-size: 0.8rem;
    color: gray;
    margin-top: 5px;
}
</style>

<div class="chat-container">

    <!-- HEADER -->
    <div class="chat-header">
        <div class="avatar">
            {{ strtoupper(substr($user->nome, 0, 1)) }}
        </div>
        <div>
            <strong>{{ $user->nome }}</strong><br>
            <small style="color: green;">● Online</small>
        </div>
    </div>

    <!-- CHAT -->
    <div id="chatBox" class="chat-box"></div>

    <!-- DIGITANDO -->
    <div id="typing" class="typing" style="display:none;">
        digitando...
    </div>

    <!-- INPUT -->
    <form id="chatForm">
        @csrf
        <div class="input-group mt-3">
            <input type="text" id="messageInput" class="form-control" placeholder="Digite uma mensagem..." required>
            <button class="btn btn-primary">Enviar</button>
        </div>
    </form>

</div>

<script>
let userId = {{ $user->id }};
let chatBox = document.getElementById('chatBox');
let form = document.getElementById('chatForm');
let input = document.getElementById('messageInput');
let typing = document.getElementById('typing');

// 🔄 CARREGAR MENSAGENS
function loadMessages() {
    fetch(`/chat/${userId}/messages`)
        .then(res => res.json())
        .then(data => {

            chatBox.innerHTML = '';

            data.forEach(msg => {

                let div = document.createElement('div');

                if (msg.from_id == {{ auth()->id() }}) {
                    div.className = 'msg msg-me';
                } else {
                    div.className = 'msg msg-other';
                }

                // 👇 suporta texto e imagem
                if (msg.type === 'image') {
                    div.innerHTML = `<img src="/storage/${msg.message}" style="max-width:100%;border-radius:10px;">`;
                } else {
                    div.innerText = msg.message;
                }

                chatBox.appendChild(div);
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        });
}

// 🚀 ENVIAR MENSAGEM
form.addEventListener('submit', function(e) {
    e.preventDefault();

    fetch(`/chat/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('[name=_token]').value
        },
        body: JSON.stringify({
            message: input.value
        })
    }).then(() => {
        input.value = '';
        loadMessages();
    });
});

// ✍️ DIGITANDO
input.addEventListener('input', () => {
    typing.style.display = 'block';

    clearTimeout(window.typingTimeout);

    window.typingTimeout = setTimeout(() => {
        typing.style.display = 'none';
    }, 1000);
});

// 🔄 AUTO UPDATE
setInterval(loadMessages, 2000);

// 🚀 INIT
loadMessages();
</script>

@endsection
