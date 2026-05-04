@extends('layouts.app')

@section('title', 'Complete seu perfil')

@section('content')

<div class="container" style="max-width:600px">

    <div class="card p-4 shadow">

        <h4 class="mb-3">Complete seu perfil</h4>

        <form method="POST" action="{{ route('onboarding.save') }}">
            @csrf

            <!-- BIO -->
            <textarea name="bio" class="form-control mb-3"
                placeholder="Fale sobre você..."></textarea>

            <!-- BUSCA SKILL -->
            <input type="text" id="search-skill"
                class="form-control mb-2"
                placeholder="Digite uma habilidade...">

            <!-- RESULTADOS -->
            <div id="results" class="mt-2"></div>

            <!-- SELECIONADAS -->
            <div id="selected-skills" class="mb-3"></div>

            <button class="btn btn-primary w-100">
                Salvar perfil
            </button>

        </form>

    </div>

</div>

<script>
let selectedSkills = [];

/* 🔎 BUSCA */
document.getElementById('search-skill').addEventListener('keyup', function () {

    let query = this.value;

    if (query.length < 2) return;

    fetch(`/skills?search=${query}`)
        .then(res => res.json())
        .then(data => {

            let results = document.getElementById('results');
            results.innerHTML = '';

            data.forEach(skill => {

                let name = skill.name ?? skill.nome;

                let div = document.createElement('div');
                div.innerText = name;

                div.style.padding = "8px";
                div.style.cursor = "pointer";
                div.style.borderBottom = "1px solid #ccc";

                div.addEventListener('click', function () {
                    addSkill(skill.id, name);
                });

                results.appendChild(div);
            });

        });
});


/* ➕ ADICIONAR SKILL */
function addSkill(id, name) {

    // evita duplicar
    if (selectedSkills.some(s => s.id === id)) return;

    selectedSkills.push({ id, name });

    renderSkills();
}


/* 🧹 REMOVER SKILL */
function removeSkill(id) {

    selectedSkills = selectedSkills.filter(s => s.id !== id);

    renderSkills();
}


/* 🧱 RENDERIZA LISTA */
function renderSkills() {

    let container = document.getElementById('selected-skills');
    container.innerHTML = '';

    selectedSkills.forEach(skill => {

        container.innerHTML += `
            <div style="
                margin:5px;
                padding:8px;
                background:#eee;
                border-radius:5px;
                display:flex;
                justify-content:space-between;
                align-items:center;
            ">

                <span>${skill.name}</span>

                <div style="display:flex; gap:10px; align-items:center;">

                    <select name="skills[${skill.id}]">
                        <option value="0">Aprendendo</option>
                        <option value="1">Básico</option>
                        <option value="2">Médio</option>
                        <option value="3">Avançado</option>
                        <option value="4">Profissional</option>
                        <option value="5">Mestre</option>
                    </select>

                    <button type="button"
                        onclick="removeSkill(${skill.id})"
                        style="background:red;color:white;border:none;padding:4px 8px;border-radius:4px;cursor:pointer;">
                        Excluir
                    </button>

                </div>

            </div>
        `;
    });
}
</script>

@endsection
