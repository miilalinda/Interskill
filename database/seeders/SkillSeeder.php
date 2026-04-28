<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            "Tecnologia", "Design", "Marketing", "Administração",
            "Idiomas", "Saúde", "Engenharia", "Educação", "Soft Skills"
        ];

        $baseSkills = [
            "Análise", "Gestão", "Desenvolvimento", "Planejamento",
            "Comunicação", "Criação", "Otimização", "Estratégia",
            "Implementação", "Monitoramento"
        ];

        $areas = [
            "de Sistemas", "Web", "Mobile", "de Dados", "Digital",
            "de Projetos", "Empresarial", "Educacional", "Industrial",
            "Criativo"
        ];

        $skills = [];

        // Geração base (combinações)
        foreach ($categories as $category) {
            foreach ($baseSkills as $base) {
                foreach ($areas as $area) {
                    $skills[] = [
                        'name' => "$base $area",
                        'category' => $category,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Garantir exatamente 5000
        $i = 1;
        while (count($skills) < 5000) {
            $skills[] = [
                'name' => "Habilidade Extra $i",
                'category' => $categories[array_rand($categories)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $i++;
        }

        // Inserção em massa (mais seguro para grande volume)
        foreach (array_chunk($skills, 500) as $chunk) {
            Skill::insert($chunk);
        }
    }
}
