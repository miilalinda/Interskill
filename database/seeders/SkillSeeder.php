<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [];

        $data = [

            'Programação & Tecnologia' => [
                'PHP', 'Python', 'JavaScript', 'TypeScript', 'Java', 'Kotlin', 'Swift',
                'C', 'C++', 'C#', 'Go', 'Rust', 'Ruby', 'Scala', 'Perl', 'Dart',
                'R', 'MATLAB', 'Lua', 'Elixir', 'Haskell', 'Clojure', 'F#',
                'Objective-C', 'Assembly', 'COBOL', 'Fortran', 'Groovy', 'Julia',
                'HTML', 'CSS', 'Sass/SCSS', 'React', 'Vue.js', 'Angular', 'Svelte',
                'Next.js', 'Nuxt.js', 'Tailwind CSS', 'Bootstrap', 'jQuery',
                'Web Components', 'WebAssembly', 'Three.js', 'D3.js',
                'Node.js', 'Laravel', 'Django', 'Flask', 'FastAPI', 'Spring Boot',
                'Ruby on Rails', 'ASP.NET', 'Express.js', 'NestJS', 'Symfony',
                'Phoenix (Elixir)', 'Gin (Go)',
                'React Native', 'Flutter', 'Android (nativo)', 'iOS (nativo)',
                'Xamarin', 'Ionic', 'Capacitor',
                'MySQL', 'PostgreSQL', 'SQLite', 'MongoDB', 'Redis', 'Cassandra',
                'Oracle Database', 'SQL Server', 'MariaDB', 'DynamoDB', 'Firebase',
                'Elasticsearch', 'Neo4j',
                'Docker', 'Kubernetes', 'Terraform', 'Ansible', 'Jenkins',
                'GitHub Actions', 'GitLab CI/CD', 'CircleCI', 'AWS', 'Azure',
                'Google Cloud Platform', 'Heroku', 'Vercel', 'Netlify',
                'Linux', 'Nginx', 'Apache', 'Bash/Shell Script', 'PowerShell',
                'Machine Learning', 'Deep Learning', 'Computer Vision',
                'Processamento de Linguagem Natural (NLP)', 'TensorFlow', 'PyTorch',
                'Scikit-learn', 'Pandas', 'NumPy', 'Keras', 'Análise de Dados',
                'Engenharia de Dados', 'ETL', 'Apache Spark', 'Hadoop',
                'Power BI', 'Tableau', 'Looker Studio',
                'Git', 'REST APIs', 'GraphQL', 'gRPC', 'WebSockets',
                'Microserviços', 'Arquitetura de Software', 'Clean Code',
                'Testes Unitários', 'TDD', 'BDD', 'Segurança da Informação',
                'Criptografia', 'Blockchain', 'Smart Contracts', 'Web Scraping',
                'Automação de Testes', 'Selenium', 'Cypress', 'Playwright',
            ],

            'Design' => [
                'Design de Interface (UI)', 'Design de Experiência do Usuário (UX)',
                'Design Gráfico', 'Design de Logotipo', 'Identidade Visual',
                'Design Editorial', 'Tipografia', 'Design de Embalagem',
                'Design de Moda', 'Design de Interiores', 'Design de Produto',
                'Design de Joias', 'Design de Mobiliário', 'Design de Calçados',
                'Arquitetura de Informação', 'Prototipagem', 'Wireframing',
                'Motion Design', 'Design de Animação', 'Storyboard',
                'Illustrator', 'Photoshop', 'Figma', 'Sketch', 'InVision',
                'Adobe XD', 'Canva', 'CorelDRAW', 'Affinity Designer',
                'Cinema 4D', 'Blender', 'After Effects',
                'Branding', 'Design Responsivo', 'Acessibilidade Digital',
                'Design de Games', 'Concept Art', 'Design de Personagens', 'Pixel Art',
            ],

            'Artes Visuais' => [
                'Pintura a Óleo', 'Pintura em Aquarela', 'Pintura Acrílica',
                'Pintura Guache', 'Pintura Digital', 'Ilustração',
                'Ilustração Infantil', 'Desenho Técnico', 'Desenho Artístico',
                'Esboço / Sketch', 'Retrato', 'Caricatura', 'Mangá / HQ',
                'Arte de Rua (Graffiti)', 'Grafite Artístico', 'Muralismo',
                'Gravura', 'Serigrafia', 'Xilogravura', 'Litografia',
                'Escultura', 'Modelagem em Argila', 'Cerâmica', 'Pintura em Tela',
                'Arte em Mosaico', 'Colagem', 'Decoupage', 'Arte Têxtil',
                'Bordado Artístico', 'Origami', 'Kirigami', 'Arte em Papel', 'Vitral',
                'Arte em Resina', 'Arte em Couro', 'Arte Indígena', 'Arte Afro-brasileira',
                'Fotografia Artística', 'Fotografia de Retrato', 'Fotografia de Paisagem',
                'Fotografia de Moda', 'Fotografia Documental', 'Fotografia de Produto',
                'Fotografia de Casamento', 'Fotografia Aérea com Drone',
                'Edição de Foto (Lightroom)', 'Edição de Foto (Photoshop)',
            ],

            'Artes Cênicas' => [
                'Teatro', 'Teatro Musical', 'Comédia Stand-up', 'Improvisação Teatral',
                'Teatro de Rua', 'Teatro de Bonecos e Fantoches', 'Teatro de Sombras',
                'Mímica', 'Pantomima', 'Ballet Clássico', 'Dança Contemporânea',
                'Dança de Salão', 'Samba', 'Forró', 'Funk', 'Hip Hop (Dança)',
                'Street Dance', 'Breakdance', 'Danças Folclóricas', 'Sapateado',
                'Dança do Ventre', 'Flamenco', 'Dança Indiana', 'Dança Africana',
                'Dança Irlandesa', 'Dança Cigana',
                'Circo', 'Acrobacia', 'Malabarismo', 'Contorcionismo',
                'Equilibrismo', 'Palhaçaria', 'Ópera',
                'Direção Teatral', 'Dramaturgia', 'Cenografia', 'Figurino Teatral',
                'Maquiagem Artística para Teatro', 'Iluminação Cênica',
                'Sonoplastia', 'Produção Cultural', 'Gestão de Espetáculos',
            ],

            'Música' => [
                'Canto (Voz)', 'Teoria Musical', 'Leitura de Partitura',
                'Composição Musical', 'Arranjo Musical', 'Regência Coral',
                'Regência de Orquestra', 'Produção Musical', 'Mixagem de Áudio',
                'Masterização de Áudio', 'DJ', 'Produção de Música Eletrônica',
                'Violão', 'Guitarra Elétrica', 'Guitarra Clássica', 'Baixo Elétrico',
                'Piano', 'Teclado', 'Bateria', 'Percussão', 'Pandeiro', 'Cajón',
                'Flauta Transversal', 'Flauta Doce', 'Clarinete', 'Saxofone',
                'Trompete', 'Trombone', 'Tuba', 'Corno Francês',
                'Violino', 'Viola', 'Violoncelo', 'Contrabaixo Acústico',
                'Harpa', 'Acordeão', 'Sanfona', 'Gaita de Boca', 'Ukulele',
                'Bandolim', 'Cavaquinho', 'Berimbau', 'Agogô', 'Atabaque',
                'Canto Lírico', 'Canto Popular', 'Canto Coral', 'Beatbox',
                'Rap e Vocal de Hip Hop', 'Solfejo', 'Improvisação Musical',
                'Gravação em Estúdio', 'Sound Design', 'Foley',
            ],

            'Idiomas' => [
                'Inglês', 'Espanhol', 'Francês', 'Italiano', 'Alemão',
                'Mandarim', 'Japonês', 'Coreano', 'Árabe', 'Russo',
                'Hindi', 'Bengalês', 'Suaíle', 'Turco', 'Hebraico', 'Grego',
                'Holandês', 'Sueco', 'Norueguês', 'Dinamarquês', 'Polonês',
                'Tcheco', 'Húngaro', 'Romeno', 'Catalão', 'Galego',
                'Português Europeu', 'Latim', 'Grego Antigo', 'Esperanto',
                'Língua Brasileira de Sinais (Libras)', 'ASL (American Sign Language)',
                'Tradução Inglês-Português', 'Interpretação Simultânea',
                'Legendagem', 'Revisão Textual',
            ],

            'Carpintaria & Marcenaria' => [
                'Marcenaria', 'Carpintaria', 'Entalhe em Madeira', 'Tornearia em Madeira',
                'Construção de Móveis', 'Restauração de Móveis', 'Pintura em Madeira',
                'Verniz e Acabamentos', 'Entalhamento Artístico', 'Intarsia',
                'Marchetaria', 'Luthieria (Construção de Instrumentos)',
                'Fabricação de Brinquedos de Madeira', 'Carpintaria Naval',
                'Estruturas de Telhado', 'Esquadrias de Madeira',
                'Deck e Pergolado', 'CNC em Madeira', 'Pirografia',
            ],

            'Construção Civil & Reformas' => [
                'Alvenaria', 'Assentamento de Tijolos', 'Concretagem', 'Pedreiro',
                'Azulejista', 'Pintura de Paredes', 'Pintura Imobiliária',
                'Gesseiro', 'Instalações Hidráulicas', 'Encanamento',
                'Instalações Elétricas', 'Eletricista Residencial', 'Eletricista Industrial',
                'Instalação de Ar-Condicionado', 'Refrigeração e Climatização',
                'Serralheria', 'Soldagem MIG/MAG', 'Soldagem TIG',
                'Soldagem Elétrica', 'Soldagem Oxiacetilênica',
                'Instalação de Drywall', 'Forro de Gesso',
                'Telhados e Coberturas', 'Impermeabilização',
                'Piso Laminado', 'Piso Vinílico', 'Porcelanato',
                'Paisagismo', 'Jardinagem', 'Irrigação', 'Manutenção de Piscinas',
            ],

            'Culinária' => [
                'Culinária Brasileira', 'Culinária Italiana', 'Culinária Francesa',
                'Culinária Japonesa', 'Sushi', 'Culinária Asiática', 'Culinária Árabe',
                'Culinária Mexicana', 'Culinária Indiana', 'Culinária Vegana',
                'Culinária Vegetariana', 'Panificação', 'Confeitaria',
                'Cake Design', 'Chocolateria', 'Sorveteria',
                'Fermentação (Kombucha e Kefir)', 'Charcutaria', 'Defumação',
                'Gastronomia Molecular', 'Barismo', 'Coquetelaria (Bartender)',
                'Sommelier de Vinhos', 'Sommelier de Cervejas',
                'Produção de Cerveja Artesanal', 'Produção de Destilados Artesanais',
                'Conservas e Geleias', 'Cozinha Funcional',
                'Buffet e Gastronomia para Eventos',
            ],

            'Saúde & Bem-estar' => [
                'Primeiros Socorros', 'RCP (Reanimação Cardiopulmonar)', 'Enfermagem',
                'Fisioterapia', 'Nutrição', 'Psicologia', 'Psicopedagogia',
                'Fonoaudiologia', 'Terapia Ocupacional', 'Odontologia',
                'Farmácia', 'Biomedicina', 'Análises Clínicas', 'Medicina Veterinária',
                'Acupuntura', 'Homeopatia', 'Fitoterapia', 'Aromaterapia',
                'Massagem Relaxante', 'Massagem Terapêutica', 'Reflexologia',
                'Shiatsu', 'Drenagem Linfática', 'Ayurveda',
                'Yoga', 'Meditação', 'Mindfulness', 'Pilates',
                'Reeducação Postural Global (RPG)', 'Personal Trainer',
                'Treinamento Funcional', 'Natação (Instrutor)',
                'Corrida (Coach)', 'Nutrição Esportiva',
                'Cuidados com Idosos', 'Cuidados Paliativos',
                'Primeiros Cuidados em Saúde Mental',
            ],

            'Esportes' => [
                'Futebol', 'Futsal', 'Voleibol', 'Basquetebol', 'Handebol',
                'Natação', 'Atletismo', 'Ciclismo', 'Ciclismo de Montanha (MTB)',
                'Corrida de Rua', 'Triathlon', 'Remo', 'Canoagem', 'Vela',
                'Surfe', 'Windsurf', 'Kitesurf', 'Stand Up Paddle (SUP)',
                'Mergulho', 'Escalada Esportiva', 'Escalada em Rocha', 'Rappel',
                'Parapente', 'Asa Delta', 'Equitação',
                'Tênis', 'Tênis de Mesa', 'Badminton', 'Squash', 'Golf',
                'Hipismo', 'Hóquei', 'Rugby', 'Beisebol',
                'Tiro com Arco', 'Tiro Esportivo',
                'Judô', 'Karatê', 'Jiu-Jitsu', 'Muay Thai', 'Boxe',
                'Capoeira', 'Taekwondo', 'Kung Fu', 'Esgrima',
                'Ginástica Artística', 'Ginástica Rítmica', 'Pole Dance (Esportivo)',
                'Patinação no Gelo', 'Snowboard', 'Esqui',
                'Xadrez', 'Dama', 'Go (Jogo)', 'E-Sports',
            ],

            'Educação & Ensino' => [
                'Docência na Educação Infantil', 'Docência no Ensino Fundamental',
                'Docência no Ensino Médio', 'Docência no Ensino Superior',
                'Educação Especial e Inclusiva', 'Pedagogia', 'Andragogia',
                'Tutoria Acadêmica', 'Preparação para o ENEM',
                'Preparação para Vestibular', 'Preparação para Concursos Públicos',
                'Aulas de Matemática', 'Aulas de Física', 'Aulas de Química',
                'Aulas de Biologia', 'Aulas de História', 'Aulas de Geografia',
                'Aulas de Filosofia', 'Aulas de Sociologia', 'Aulas de Português',
                'Aulas de Redação', 'Aulas de Literatura',
                'Planejamento Pedagógico', 'EAD e Educação Online',
                'Criação de Cursos Online', 'Treinamento Corporativo',
                'Facilitação de Workshops', 'Coaching Educacional', 'Mentoria',
            ],

            'Administração & Negócios' => [
                'Gestão de Projetos', 'Scrum', 'Kanban',
                'Gestão de Equipes', 'Liderança', 'Gestão de Pessoas',
                'Recrutamento e Seleção', 'Departamento Pessoal', 'Folha de Pagamento',
                'Contabilidade', 'Finanças Pessoais', 'Finanças Empresariais',
                'Planejamento Financeiro', 'Análise de Investimentos', 'Valuation',
                'Controladoria', 'Auditoria', 'Compliance', 'Gestão de Riscos',
                'Logística', 'Supply Chain', 'Compras e Procurement',
                'Gestão de Estoque', 'Atendimento ao Cliente', 'Customer Success',
                'Vendas B2B', 'Vendas B2C', 'Negociação', 'Prospecção de Clientes',
                'CRM', 'Gestão Empresarial', 'Empreendedorismo',
                'Modelagem de Negócios (Canvas)', 'Inovação',
                'Gestão de Qualidade (ISO)', 'Lean Manufacturing', 'Six Sigma',
            ],

            'Marketing & Comunicação' => [
                'Marketing Digital', 'SEO (Search Engine Optimization)',
                'Google Ads', 'Meta Ads (Facebook e Instagram)',
                'TikTok Ads', 'Email Marketing', 'Marketing de Conteúdo',
                'Copywriting', 'Redação Publicitária', 'Storytelling',
                'Social Media', 'Gestão de Redes Sociais', 'Community Management',
                'Influencer Marketing', 'Marketing de Afiliados',
                'Inbound Marketing', 'Growth Hacking', 'Funil de Vendas',
                'Google Analytics', 'Pesquisa de Mercado',
                'Branding', 'Relações Públicas', 'Assessoria de Imprensa',
                'Jornalismo', 'Produção de Podcast', 'Locução',
                'Produção de Vídeo', 'Edição de Vídeo (Adobe Premiere)',
                'Edição de Vídeo (DaVinci Resolve)', 'Roteiro para Vídeo',
                'Direção de Fotografia', 'YouTube e Video Marketing', 'Streaming e Lives',
            ],

            'Engenharia' => [
                'Engenharia Civil', 'Estruturas', 'Fundações', 'Topografia',
                'Geotecnia', 'Saneamento Básico', 'Engenharia Elétrica',
                'Automação Industrial', 'Robótica', 'Eletrônica', 'Arduino', 'Raspberry Pi',
                'Engenharia Mecânica', 'AutoCAD', 'SolidWorks', 'CATIA',
                'Simulação de Elementos Finitos (FEA)',
                'Engenharia de Produção', 'Gestão da Produção', 'Ergonomia',
                'Engenharia Química', 'Processos Industriais', 'Petróleo e Gás',
                'Engenharia Ambiental', 'Gestão Ambiental', 'ISO 14001',
                'Energia Solar Fotovoltaica', 'Energia Eólica',
                'Engenharia de Alimentos', 'Biotecnologia', 'Nanotecnologia',
                'Impressão 3D e Prototipagem Rápida', 'Operação de Drones',
            ],

            'Artesanato & Ofícios' => [
                'Crochê', 'Tricô', 'Bordado', 'Macramê', 'Tapeçaria',
                'Tecelagem', 'Costura', 'Corte e Costura', 'Costura Artística',
                'Patchwork', 'Quilting', 'Renda de Bilro', 'Biscuit e Porcelana Fria',
                'Amigurumi', 'Sapataria', 'Artesanato em Couro',
                'Joalheria', 'Bijuteria Artesanal', 'Modelagem de Cera',
                'Velas Artesanais', 'Sabonetes Artesanais', 'Cosméticos Naturais',
                'Flores de EVA', 'Flores de Tecido', 'Arte em Balões',
                'Scrapbook', 'Caligrafia', 'Lettering',
                'Encadernação Artesanal', 'Arte em Vidro', 'Pinturas em Rochas',
            ],

            'Direito & Jurídico' => [
                'Direito Civil', 'Direito Penal', 'Direito Trabalhista',
                'Direito Tributário', 'Direito Empresarial', 'Direito Ambiental',
                'Direito de Família', 'Direito do Consumidor', 'Direito Internacional',
                'Direito Digital', 'Proteção de Dados (LGPD e GDPR)', 'Contratos',
                'Propriedade Intelectual', 'Mediação e Arbitragem', 'Compliance Jurídico',
                'Advocacia Previdenciária', 'Processo Judicial Eletrônico',
                'Registros Públicos',
            ],

            'Soft Skills' => [
                'Comunicação Assertiva', 'Escuta Ativa', 'Empatia',
                'Resolução de Conflitos', 'Trabalho em Equipe', 'Colaboração',
                'Liderança', 'Liderança Situacional', 'Inteligência Emocional',
                'Adaptabilidade', 'Resiliência', 'Criatividade', 'Pensamento Crítico',
                'Pensamento Analítico', 'Tomada de Decisão', 'Gestão do Tempo',
                'Organização', 'Planejamento', 'Proatividade', 'Autodidatismo',
                'Negociação', 'Persuasão', 'Oratória', 'Apresentações Públicas',
                'Facilitação de Reuniões', 'Feedback', 'Coaching',
                'Gestão de Stress', 'Networking',
            ],

            'Outros' => [
                'Astrologia', 'Tarô', 'Numerologia', 'Feng Shui',
                'Fotografia com Smartphone', 'Edição de Vídeo Mobile',
                'Design de Jogos de Tabuleiro', 'RPG (Game Master)',
                'Bushcraft e Sobrevivência na Natureza', 'Pesca Esportiva',
                'Apicultura', 'Aquicultura', 'Agricultura Familiar', 'Permacultura',
                'Horta Orgânica', 'Compostagem', 'Avicultura',
                'Criação de Bovinos', 'Criação de Suínos',
                'Adestramento de Animais', 'Pet Care', 'Jardinagem Doméstica', 'Bonsai',
                'Astronomia Amadora', 'Meteorologia Amadora',
                'Escrita Criativa', 'Poesia', 'Escrita de Roteiros', 'Fanfiction',
                'Revisão e Edição de Textos', 'Produção Editorial',
                'Biblioteconomia', 'Arquivologia', 'Museologia',
                'Gestão Cultural', 'Produção de Eventos', 'Cerimonial',
                'Segurança Privada', 'Socorrismo Aquático', 'Guia de Turismo',
                'Mecânica Automotiva', 'Mecânica de Motos',
                'Funilaria e Pintura Automotiva', 'Customização de Veículos',
                'Detalhamento Automotivo',
            ],
        ];

        foreach ($data as $category => $skillNames) {
            foreach ($skillNames as $name) {
                $skills[] = [
                    'nome'       => $name,
                    'category'   => $category,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Remove duplicatas por nome (case-insensitive)
        $seen   = [];
        $unique = [];
        foreach ($skills as $skill) {
            $key = mb_strtolower(trim($skill['nome']));
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $unique[]   = $skill;
            }
        }

        foreach (array_chunk($unique, 200) as $chunk) {
            Skill::insert($chunk);
        }

        $this->command->info(count($unique) . ' habilidades inseridas com sucesso!');
    }
}
