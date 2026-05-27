@extends('layouts.app')

@section('title', 'Complete seu perfil')

@section('content')

<div class="container onboarding-container">

    <div class="onboarding-card">

        <h4>Complete seu perfil</h4>

        <form method="POST" action="{{ route('onboarding.save') }}">
            @csrf

            <textarea name="bio" class="form-control custom-input"
                placeholder="Fale sobre você..."></textarea>

            <input type="text" id="search-skill"
                class="form-control custom-input"
                autocomplete="off"
                placeholder="Digite uma habilidade...">

            <div id="results"></div>

            <div id="selected-skills"></div>

            <button class="save-btn">
                Salvar perfil
            </button>

        </form>

    </div>

</div>

<style>
.onboarding-container {
    max-width: 600px;
    margin-top: 30px;
}

.onboarding-card {
    background: #1f2933;
    padding: 28px;
    border-radius: 16px;
    color: white;
    box-shadow: 0 15px 40px rgba(0,0,0,0.35);
}

.onboarding-card h4 {
    text-align: center;
    margin-bottom: 20px;
    font-weight: 700;
}

.custom-input {
    background: #020617;
    color: white;
    border: none;
    border-radius: 8px;
    margin-bottom: 16px;
    padding: 13px;
}

.custom-input:focus {
    background: #020617;
    color: white;
    box-shadow: 0 0 0 2px rgba(99,102,241,0.35);
}

#results {
    background: #0f172a;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 12px;
}

.skill-option {
    padding: 12px 14px;
    cursor: pointer;
    border-bottom: 1px solid #1f2937;
    transition: 0.2s;
}

.skill-option:hover {
    background: #334155;
}

.skill-option small {
    color: #9ca3af;
}

.selected-skill {
    margin-top: 12px;
    padding: 14px;
    border-radius: 18px;
    background: linear-gradient(135deg, #1e293b, #0f172a);
    box-shadow: 0 8px 22px rgba(0,0,0,0.35);
    animation: fadeIn 0.25s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.96);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.skill-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.skill-name {
    font-weight: 700;
    font-size: 15px;
}

.skill-category {
    font-size: 12px;
    color: #a5b4fc;
}

.remove-skill {
    background: #ef4444;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    color: white;
    cursor: pointer;
    font-size: 20px;
    line-height: 1;
    transition: 0.2s;
}

.remove-skill:hover {
    background: #dc2626;
    transform: scale(1.08);
}

.level-buttons {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.level-btn {
    background: #020617;
    color: #cbd5e1;
    border: 1px solid #334155;
    border-radius: 999px;
    padding: 6px 11px;
    font-size: 12px;
    cursor: pointer;
    transition: 0.2s;
}

.level-btn:hover {
    background: #1e293b;
}

.level-btn.active {
    background: #6366f1;
    color: white;
    border-color: #6366f1;
}

.save-btn {
    margin-top: 18px;
    width: 100%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
    transition: 0.2s;
}

.save-btn:hover {
    transform: translateY(-1px);
    opacity: 0.95;
}
</style>

<script>
let selectedSkills = [];

document.getElementById('search-skill').addEventListener('keyup', function () {
    let query = this.value.trim();

    if (query.length < 2) {
        document.getElementById('results').innerHTML = '';
        return;
    }

    fetch(`/skills?search=${query}`)
        .then(res => res.json())
        .then(data => {
            let results = document.getElementById('results');
            results.innerHTML = '';

            data.forEach(skill => {
                let name = skill.nome ?? skill.name;
                let category = skill.category ?? '';

                if (selectedSkills.some(s => s.id === skill.id)) return;

                let div = document.createElement('div');
                div.className = 'skill-option';

                div.innerHTML = `
                    <strong>${name}</strong><br>
                    <small>${category}</small>
                `;

                div.onclick = () => {
                    addSkill(skill.id, name, category);
                    document.getElementById('search-skill').value = '';
                    results.innerHTML = '';
                };

                results.appendChild(div);
            });
        });
});

function addSkill(id, name, category) {
    id = Number(id);

    if (selectedSkills.some(s => s.id === id)) return;

    selectedSkills.push({ id, name, category });
    renderSkills();
}

function removeSkill(id) {
    id = Number(id);
    selectedSkills = selectedSkills.filter(s => s.id !== id);
    renderSkills();
}

function renderLevels(id) {
    const levels = [
        'Aprendendo',
        'Básico',
        'Médio',
        'Avançado',
        'Pro',
        'Mestre'
    ];

    return levels.map((level, index) => `
        <button type="button"
            class="level-btn ${index === 0 ? 'active' : ''}"
            onclick="selectLevel(${id}, ${index}, this)">
            ${level}
        </button>
    `).join('');
}

function selectLevel(id, value, el) {
    document.getElementById(`input-${id}`).value = value;

    el.parentElement.querySelectorAll('.level-btn')
        .forEach(btn => btn.classList.remove('active'));

    el.classList.add('active');
}

function renderSkills() {
    let container = document.getElementById('selected-skills');
    container.innerHTML = '';

    selectedSkills.forEach(skill => {
        container.innerHTML += `
            <div class="selected-skill">

                <div class="skill-top">
                    <div>
                        <div class="skill-name">${skill.name}</div>
                        <div class="skill-category">${skill.category}</div>
                    </div>

                    <button type="button"
                        class="remove-skill"
                        onclick="removeSkill(${skill.id})">
                        ×
                    </button>
                </div>

                <div class="level-buttons">
                    ${renderLevels(skill.id)}
                </div>

                <input type="hidden" name="skills[${skill.id}]" value="0" id="input-${skill.id}">

            </div>
        `;
    });
}
</script>

@endsection
