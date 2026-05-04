@extends('auth.template')

@section('title', 'Cadastro')

@section('content')

<style>
body {
    margin: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #0f172a;
    font-family: Arial, sans-serif;
    overflow-y: auto;
    padding: 10px;
}

/* BLOBS */
.bg-blob {
    position: fixed;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.4;
    z-index: 0;
}

.blob-1 { background: #6366f1; top: -80px; left: -80px; }
.blob-2 { background: #ec4899; bottom: -80px; right: -80px; }

/* CARD */
.glass-panel {
    width: 100%;
    max-width: 360px;
    padding: 14px 16px; /* 🔥 menor */
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    color: white;
    z-index: 2;
}

/* TITULO */
h1 {
    text-align: center;
    font-size: 15px;
    margin: 0;
}

.subtitle {
    text-align: center;
    font-size: 10px;
    opacity: 0.7;
    margin: 2px 0 6px 0;
}

/* FORM */
.form-group {
    margin-bottom: 2px; /* 🔥 bem reduzido */
}

.form-group label {
    display: block;
    font-size: 10px;
    margin-bottom: 1px; /* 🔥 menor */
}

.form-input {
    width: 100%;
    padding: 5px; /* 🔥 menor */
    border-radius: 6px;
    border: none;
    outline: none;
    font-size: 11px;
}

/* BOTÃO */
.btn-primary {
    width: 100%;
    padding: 7px;
    background: #6366f1;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 11px;
    margin-top: 4px; /* 🔥 menos espaço */
}

/* UPLOAD */
.upload-box {
    border: 1px dashed #aaa;
    padding: 5px;
    text-align: center;
    border-radius: 8px;
    cursor: pointer;
    font-size: 10px;
    position: relative;
}

/* PREVIEW */
.preview-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin: 5px auto 0;
    display: none;
    border: 2px solid #6366f1;
}
</style>

<div class="bg-blob blob-1"></div>
<div class="bg-blob blob-2"></div>

<div class="glass-panel">

    <h1>INTERSKILL</h1>
    <p class="subtitle">Crie sua conta</p>

    @if ($errors->any())
        <div style="color:#f87171; font-size:10px; margin-bottom:4px;">
            @foreach ($errors->all() as $erro)
                <div>{{ $erro }}</div>
            @endforeach
        </div>
    @endif

    <form id="formCadastro" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" class="form-input" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-input" required>
        </div>

        <div class="form-group">
            <label>CPF</label>
            <input type="text" id="cpf" name="cpf" class="form-input" required>
            <small id="cpfErro" style="color:red; display:none; font-size:9px;">CPF inválido</small>
        </div>

        <div class="form-group">
            <label>Usuário</label>
            <input type="text" name="user_nome" class="form-input" required>
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="password" class="form-input" required>
        </div>

        <div class="form-group">
            <label>Foto</label>

            <div class="upload-box">
                <input type="file" id="foto" name="foto_perfil" accept="image/*"
                    style="position:absolute;width:100%;height:100%;opacity:0;">
                📸 Foto
            </div>

            <img id="preview" class="preview-img">
        </div>

        <button type="submit" class="btn-primary">Criar Conta</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const cpfInput = document.getElementById('cpf');
    const form = document.getElementById('formCadastro');
    const erro = document.getElementById('cpfErro');
    const fotoInput = document.getElementById('foto');
    const preview = document.getElementById('preview');

    cpfInput.addEventListener('input', function () {
        let v = this.value.replace(/\D/g, '').slice(0, 11);

        if (v.length > 3) v = v.replace(/(\d{3})(\d)/, '$1.$2');
        if (v.length > 6) v = v.replace(/(\d{3})(\d)/, '$1.$2');
        if (v.length > 9) v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        this.value = v;
    });

    function validarCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');
        if (cpf.length !== 11) return false;
        if (/^(\d)\1+$/.test(cpf)) return false;

        let soma = 0, resto;

        for (let i = 1; i <= 9; i++)
            soma += parseInt(cpf[i-1]) * (11 - i);

        resto = (soma * 10) % 11;
        if (resto >= 10) resto = 0;
        if (resto !== parseInt(cpf[9])) return false;

        soma = 0;
        for (let i = 1; i <= 10; i++)
            soma += parseInt(cpf[i-1]) * (12 - i);

        resto = (soma * 10) % 11;
        if (resto >= 10) resto = 0;

        return resto === parseInt(cpf[10]);
    }

    form.addEventListener('submit', function (e) {
        if (!validarCPF(cpfInput.value)) {
            e.preventDefault();
            erro.style.display = 'block';
        }
    });

    fotoInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

});
</script>

@endsection
